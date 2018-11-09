<?php

namespace NewRoadsMedia\FrontendBundle\Controller;

use NewRoadsMedia\FrontendBundle\Entity\Employer;
use NewRoadsMedia\FrontendBundle\Entity\Industry;
use NewRoadsMedia\FrontendBundle\Entity\Job;
use NewRoadsMedia\FrontendBundle\Entity\JobEdit;
use NewRoadsMedia\FrontendBundle\Form\Type\CreateEmployerAccountFormType;
use NewRoadsMedia\FrontendBundle\Form\Type\PostNewJobBillingFormType;
use NewRoadsMedia\FrontendBundle\Form\Type\PostNewJobFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

use Gregwar\CaptchaBundle\Type\CaptchaType;

class PostNewJobController extends Controller
{
    /**
     * @Route("/post-new-job", name="PostNewJob")
     * @Template
     */
    public function disclaimerAction()
    {
        if ($this->get('security.context')->isGranted('ROLE_EMPLOYER')) {
            return $this->redirect($this->generateUrl('PostNewJobInfo'));
        }

        return array();
    }
    /**
     * @Route("/post-new-job/create-account", name="PostNewJobCreateAccount")
     * @Template()
     */
    public function createAccountAction()
    {
        if ($this->get('security.context')->isGranted('ROLE_EMPLOYER')) {
            return $this->redirect($this->generateUrl('PostNewJobInfo'));
        }

        $employerManager = $this->get('journalismjobs.manager.employer');
        $accountForm = $this->createForm(new CreateEmployerAccountFormType($employerManager), null, array(
            'method' => 'POST',
            'action' => $this->generateUrl('PostNewJobCreateAccount'),
            'attr' => array('novalidate' => 'novalidate'),
            'error_bubbling' => true,
        ));
        $accountForm->add('captcha', 'captcha', [
           'length' => 5,
           'attr' => ['maxlength' => 10, 'placeholder' => 'Security code'],
           'invalid_message' => 'Security code is invalid',
           'background_color' => [255, 255, 255],
           'height' => 30,
           'width' => 80,
           'error_bubbling' => true,
           'reload' => false
        ]);
        $accountForm->handleRequest($this->getRequest());
        if ($accountForm->isValid()) {
            /** @var Employer $employer */
            $employer = $accountForm->getData();
            $employer->setMembershipExpirationDate(new \DateTime('+1 year'));
            $employerManager->save($employer);

            // log in the user
            $this->get('journalismjobs.frontend.user')->loginUser($employer, 'employers');
            $this->get('session')->getFlashBag()->add('notice', 'Your account has been created. Complete the fields below to post your ad.');

            return $this->redirect($this->generateUrl('PostNewJobInfo'));
        }

        $encoderService = $this->get('security.encoder_factory');
        $form = $this->get('form.factory')->createNamedBuilder('', 'form', null, array(
                'attr' => array('novalidate' => 'novalidate'),
                'method' => 'POST',
                'action' => $this->generateUrl('PostNewJobCreateAccount'),
            ))
            ->add('_username', 'email', array(
                'label' => 'Email Address',
                'constraints' => array(
                    new NotBlank(array('message' => 'Email Address cannot be blank.')),
                    new Email(array('message' => 'A valid email address is required.')),
                ),
            ))
            ->add('_password', 'password', array(
                'label' => 'Password',
                'constraints' => array(
                    new NotBlank(array('message' => 'Password cannot be blank.')),
                ),
            ))
            ->getForm()
        ;
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            /** @var Employer $employer */
            $employer = $employerManager->findOneByEmail($form->get('_username')->getData());
            if ($employer) {
                $password = $form->get('_password')->getData();
                $encoder = $encoderService->getEncoder($employer);
                $encodedPass = $encoder->encodePassword($password, '');
                if ($encodedPass == $employer->getPassword()) {
                    $this->get('journalismjobs.frontend.user')->loginUser($employer, 'employers');

                    return $this->redirect($this->generateUrl('PostNewJobInfo'));
                }
            }

            $form->addError(new FormError('The username and/or password you entered is incorrect. Please try again.'));
        }

        return array(
            'accountForm' => $accountForm->createView(),
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/employers/job-info", name="PostNewJobInfo")
     * @Route("/employers/job-info/{jobId}", name="EditJobInfo")
     * @Template()
     */
    public function editJobAction($jobId = null)
    {
        /** @var Employer $employer */
        $employer = $this->getUser();
        $jobManager = $this->get('journalismjobs.manager.job');

        /** @var Job $job */
        if ($jobId) {
            $job = $this->getJob($jobId);
            $formAction = $this->generateUrl('EditJobInfo', array(
                'jobId' => $jobId,
            ));
        } else {
            $job = $jobManager->create();
            $formAction = $this->generateUrl('PostNewJobInfo');
        }
        $oldTotal = $job ? $jobManager->getJobChargeAmount($job, false) : 0;
        $action = $job && !$job->isNew() ? 'edit' : 'publish';
        $isDiversity = $job->isDiversityJob();
        $isFeatured = $job->isFeaturedJob();
        $isFrontPage = $job->getIsFrontPage();
        $industries = array();
        foreach ($job->getIndustries() as $industry) {
            $industries[] = $industry;
        }
        if (!$job->isActive()) {
            $oldTotal = 0;
            $isDiversity = false;
            $isFeatured = false;
            $industries = array();
        }

        $form = $this->createForm('post_new_job', $job, array(
            'method' => 'POST',
            'action' => $formAction,
        ));
        $form->handleRequest($this->getRequest());

        if ($form->isValid()) {
            /** @var Job $job */
            $job = $form->getData();
            $job->setEmployer($employer);
            if (!$job->getTitleOfPositionOpen()) {
                $job->setTitleOfPositionOpen('Not Specified');
            }

            if ($form->has('image')) {
                /** @var UploadedFile $image */
                $image = $form->get('image')->getData();
                if ($image) {
                    $jobManager->uploadCompanyImage($job, $image);
                } else if ($job->getCompanyImage() && $form->get('deleteImage')->getData()) {
                    $jobManager->deleteCompanyImage($job);
                }
            }

            if ($action == 'edit' && $job->isActive()) {
                $total = $jobManager->getJobChargeAmount($job, false);
                if ($total > $oldTotal) {
                    // set up the job edit
                    $jobEdit = new JobEdit();
                    $jobEdit->setOriginalIndustryCount(count($industries));
                    $jobEdit->setAddedDiversity(!$isDiversity && $job->isDiversityJob());
                    $jobEdit->setAddedFeatured(!$isFeatured && $job->isFeaturedJob());
                    $jobEdit->setAddedFrontPage(!$isFrontPage && $job->getIsFrontPage());

                    foreach ($job->getIndustries() as $industry) {
                        if (!in_array($industry, $industries)) {
                            $jobEdit->addIndustry($industry);
                        }
                    }

                    // if employer can pay for the changes using credits then do so, otherwise this process will happen at billing
                    $newIndustryCount = count($jobEdit->getIndustries());
                    if (   !$jobEdit->getAddedFrontPage()
                        && !$jobEdit->getAddedDiversity()
                        && !$jobEdit->getAddedFeatured()
                        && $newIndustryCount > 0
                        && $employer->getAdsRemaining() >= $newIndustryCount
                    ) {
                        $deduct = min($newIndustryCount, $employer->getAdsRemaining());
                        $employer->deductAdsRemaining($deduct);
                        $jobManager->save($job);

                        return $this->redirect($this->generateUrl('EditJobConfirmation', array(
                            'jobId' => $job->getId()
                        )));
                    }

                    // reset job changes that cost money, these will be restored at billing
                    if ($jobEdit->getAddedDiversity()) {
                        $job->setIsDiversity(false);
                    }
                    if ($jobEdit->getAddedFeatured()) {
                        $job->setIsFeatured(false);
                    }
                    if ($jobEdit->getAddedFrontPage()) {
                        $job->setIsFrontPage(false);
                    }
                    foreach ($jobEdit->getIndustries() as $industryId) {
                        foreach ($job->getIndustries() as $industry) {
                            if ($industry->getId() == $industryId) {
                                $job->removeIndustry($industry);
                            }
                        }
                    }

                    // save the job edit to the session
                    $key = sprintf('nrm_job_edit_%d', $job->getId());
                    $this->get('session')->set($key, $jobEdit);

                    $jobManager->save($job);

                    return $this->redirect($this->generateUrl('PostNewJobBilling', array(
                        'jobId' => $jobId
                    )));
                }

                $jobManager->save($job);

                return $this->redirect($this->generateUrl('EditJobConfirmation', array(
                    'jobId' => $jobId
                )));
            }

            $jobManager->save($job);

            return $this->redirect($this->generateUrl('PostNewJobPreview', array(
                'jobId' => $job->getId(),
            )));
        }

        $newTotal = $job ? $jobManager->getJobChargeAmount($job, false) : 0;

        return array(
            'form' => $form->createView(),
            'amountDue' => max($newTotal - $oldTotal, 0),
            'action' => $action,
            'job' => $job,
            'adsRemaining' => $employer->hasPackagePlan() ? $employer->getAdsRemaining() : 0,
            'originalIndustryCount' => count($industries),
            'originalIsFeatured' => $isFeatured,
            'originalIsDiversity' => $isDiversity,
            'originalIsFrontPage' => $isFrontPage,
            'employer' => $employer,
        );
    }

    /**
     * @Route("/employers/job-info/{jobId}/preview", name="PostNewJobPreview")
     * @Template()
     */
    public function previewAction($jobId)
    {
        $job = $this->getJob($jobId);
        $continuePath = $this->generateUrl('PostNewJobOrderSummary', array(
            'jobId' => $jobId,
        ));
        $editPath = $this->generateUrl('EditJobInfo', array(
            'jobId' => $jobId,
        ));
        $postPath = $this->generateUrl('PostNewJobPreview', array(
            'jobId' => $jobId,
        ));

        /** @var Employer $employer */
        $employer = $this->getUser();
        if ($employer->getFreeOption()) {
            $request = $this->getRequest();
            if ($request->isMethod('POST') && $request->get('action') == 'post') {
                $jobManager = $this->get('journalismjobs.manager.job');
                $jobs = $this->getJobs($jobId);
                foreach ($jobs as $job) {
                    $job->updateExpirationDate();
                    $job->complete();
                    $job->setCreateDate(new \DateTime('now'));
                    $jobManager->save($job);
                    $this->get('journalismjobs.manager.job_view')->clearJobViewCount($job);
                }

                return $this->redirect($this->generateUrl('PostNewJobCreditConfirmation', array(
                    'jobId' => $jobId,
                )));
            }
        }

        return array(
            'job' => $job,
            'continuePath' => $continuePath,
            'editPath' => $editPath,
            'postPath' => $postPath,
            'employer' => $employer,
        );
    }

    /**
     * @Route("/employers/job-info/{jobId}/order-summary", name="PostNewJobOrderSummary", defaults={"isRenewal": false})
     * @Route("/employers/job-info/{jobId}/renew-order-summary", name="PostNewJobRenewOrderSummary", defaults={"isRenewal": true})
     * @Template()
     */
    public function orderSummaryAction($jobId)
    {
        /** @var Employer $employer */
        $employer = $this->getUser();
        $jobManager = $this->get('journalismjobs.manager.job');
        $jobs = $this->getJobs($jobId);
        if ($this->getRequest()->get('isRenewal')) {
            $action = 'PostNewJobRenewOrderSummary';
        } else {
            $action = 'PostNewJobOrderSummary';
        }

        $form = $this->createFormBuilder(null, array(
                'method' => 'POST',
                'action' => $this->generateUrl($action, array(
                    'jobId' => $jobId,
                ))
            ))
            ->add('submit', 'submit', array(
                'label' => $employer->getFreeOption() ? 'Finish' : 'Proceed to Checkout',
            ))
            ->getForm()
        ;
        $form->handleRequest($this->getRequest());
        if ($form->isValid() && $form->get('submit')->isClicked()) {
            $jobSelections = $this->getRequest()->get('job');
            if ($jobSelections) {
                foreach ($jobSelections as $jobSelectionId => $enhancement) {
                    foreach ($jobs as $job) {
                        if ($job->getId() == $jobSelectionId) {
                            $job->setIsFrontPage(isset($enhancement['is_frontpage']) && $enhancement['is_frontpage'] == 1);
                            $job->setIsDiversity(isset($enhancement['is_diversity']) && $enhancement['is_diversity'] == 1);
                            $jobManager->persist($job);
                        }
                    }
                }
                $jobManager->flush();
            }
            if ($employer->getFreeOption()) {
                foreach ($jobs as $job) {
                    $job->updateExpirationDate();
                    $job->complete();
                    $job->setCreateDate(new \DateTime('now'));
                    $jobManager->save($job);
                    $this->get('journalismjobs.manager.job_view')->clearJobViewCount($job);
                }

                return $this->redirect($this->generateUrl('PostNewJobCreditConfirmation', array(
                    'jobId' => $jobId,
                )));
            }
            if ($employer->hasPackagePlan() && $employer->getAdsRemaining() > 0) {
                $amount = $jobManager->getJobsChargeAmount($employer, $jobs);
                if ($amount == 0) {
                    foreach ($jobs as $job) {
                        $employer->deductAdsRemaining($job->getIndustries()->count());
                        $job->updateExpirationDate();
                        $job->complete();
                        $job->setCreateDate(new \DateTime('now'));
                        $jobManager->save($job);
                        $this->get('journalismjobs.manager.job_view')->clearJobViewCount($job);
                    }
                    $this->get('journalismjobs.frontend.mailer')->sendNewJobsPostingsByCreditEmail($employer);

                    return $this->redirect($this->generateUrl('PostNewJobCreditConfirmation', array(
                        'jobId' => $jobId,
                    )));
                }
            }

            foreach ($jobs as $job) {
                $jobManager->save($job);
            }

            return $this->redirect($this->generateUrl('PostNewJobBilling', array(
                'jobId' => $jobId,
            )));
        }

        $jobIds = array();
        foreach ($jobs as $job) {
            $jobIds[] = $job->getId();
        }
        $postAnotherAdPath = $this->generateUrl('PostNewJob', array('jobs' => $jobIds));

        $subtotal = $jobManager->getJobsChargeAmount($employer, $jobs, false);
        $total = $jobManager->getJobsChargeAmount($employer, $jobs, true);

        return array(
            'employer' => $employer,
            'jobs' => $jobs,
            'jobManager' => $jobManager,
            'total' => $total,
            'subtotal' => $subtotal,
            'form' => $form->createView(),
            'postAnotherAdPath' => $postAnotherAdPath,
        );
    }

    /**
     * @Route("/employers/job-info/{jobId}/billing", name="PostNewJobBilling", requirements={"jobId" = "\d+"})
     * @Template
     */
    public function billingAction($jobId)
    {
        $industryManager = $this->get('journalismjobs.manager.industry');
        /** @var Employer $employer */
        $employer = $this->getUser();
        $jobs = $this->getJobs($jobId);
        $session = $this->get('session');
        $key = sprintf('nrm_job_edit_%d', $jobId);
        /** @var JobEdit $jobEdit */
        $jobEdit = count($jobs) == 1 && $session->has($key) ? $session->get($key) : null;
        $renew = true;

        $form = $this->createForm(new PostNewJobBillingFormType(), $employer, array(
            'method' => 'POST',
            'action' => $this->generateUrl('PostNewJobBilling', array('jobId' => $jobId)),
            'attr' => array('novalidate' => 'novalidate'),
            'invoice' => $employer->hasInvoiceOption(),
        ));
        $form->handleRequest($this->getRequest());

        //if this form is valid, then save the new data.
        if ($form->isValid()) {
            $jobManager = $this->get('journalismjobs.manager.job');
            if ($jobEdit) {
                $total = $jobManager->getJobsChargeAmount($employer, $jobs, false);
                foreach ($jobs as $job) {
                    if ($job->getId() == $jobId) {
                        foreach ($jobEdit->getIndustries() as $industryId) {
                            /** @var Industry $industry */
                            $industry = $industryManager->find($industryId);
                            if ($industry) {
                                $job->addIndustry($industry);
                            }
                        }
                        if ($job->getIndustries()->count() <= $jobEdit->getOriginalIndustryCount()) {
                            $renew = false;
                        }
                        if ($jobEdit->getAddedDiversity()) {
                            $job->setIsDiversity(true);
                        }
                        if ($jobEdit->getAddedFeatured()) {
                            $job->setIsFeatured(true);
                        }
                        if ($jobEdit->getAddedFrontPage()) {
                            $job->setIsFrontPage(true);
                        }
                    }
                }
                $newTotal = $jobManager->getJobsChargeAmount($employer, $jobs, false);
                $total = $newTotal - $total;
                if ($employer->hasPackagePlan() && $employer->getAdsRemaining() > 0) {
                    $deduct = min($employer->getAdsRemaining(), count($jobEdit->getIndustries()));
                    $total -= ($deduct * $this->container->getParameter('ad_cost'));
                }
            } else {
                $total = $jobManager->getJobsChargeAmount($employer, $jobs, true);
            }

            $email = $form->get('billingEmail')->getData();
            $paymentMethod = $form->get('paymentMethod')->getData();

            $paymentManager = $this->get('journalismjobs.manager.payment');
            $payment = $paymentManager->create();
            $payment->setPaymentMethod($paymentMethod);
            $payment->setEmployer($employer);
            $payment->setCreditCardNumber($form->get('creditNumber')->getData());
            $payment->setExpirationDateMonth($form->get('creditMonth')->getData());
            $payment->setExpirationDateYear($form->get('creditYear')->getData());
            $payment->setNameOnCard($form->get('name')->getData());
            $payment->setApproval(false);
            $payment->setTransactionDate(new \DateTime());
            $payment->setChargeAmount($total);
            foreach ($jobs as $job) {
                $payment->addJob($job);
            }

            $isPaid = false;
            if ($paymentMethod == 'creditcard') {
                $payment->setCreditCard($form->get('creditCard')->getData()->getCardName());
                $response = $paymentManager->chargeCreditCard($payment, $form->get('cardCode')->getData(), $form->get('billingCompany')->getData());
                if ($response['response_code'] == 1) {
                    $isPaid = true;
                } else {
                    $this->get('session')->getFlashBag()->add('error', $response['response_msg']);
                    // remove edits so persisting the payment will not persist the edits
                    if ($jobEdit) {
                        foreach ($jobs as $job) {
                            if ($job->getId() == $jobId) {
                                foreach ($jobEdit->getIndustries() as $industryId) {
                                    /** @var Industry $industry */
                                    $industry = $industryManager->find($industryId);
                                    if ($industry) {
                                        $job->removeIndustry($industry);
                                    }
                                }
                                if ($jobEdit->getAddedDiversity()) {
                                    $job->setIsDiversity(false);
                                }
                                if ($jobEdit->getAddedFeatured()) {
                                    $job->setIsFeatured(false);
                                }
                                if ($jobEdit->getAddedFrontPage()) {
                                    $job->setIsFrontPage(false);
                                }
                            }
                        }
                    }
                }
            } else {
                $isPaid = true;
            }
            $paymentManager->save($payment);

            if ($isPaid) {
            // if ($isPaid or $form->get('name')->getData()=='Erlan Beken') {
                foreach ($jobs as $job) {
                    $job->setContact($employer->getName());
                    $job->setAddress2($employer->getAddress());
                    $job->setAccountPhone($employer->getPhone());
                    $job->setAccountPhoneArea($employer->getPhoneArea());
                    $job->setAccountZip($employer->getZip());
                    $job->setAccountZipPlus($employer->getZipPlus());
                    $job->setBillingCompany($employer->getBillingCompany());
                    $job->setBillingEmail($email);
                    $job->setBillingInstructions($employer->getSpecialBillingInstructions());
                    $job->setCity($employer->getCity() . ', ' . $employer->getState());
                    $job->setBilling($paymentMethod);
                    $job->setEmployer($employer);

                    // reset viewed and resumes counters
                    $job->setEmailApplicantCount(0);
                    $job->clearJobViews();

                    $job->complete();
                    if ($jobEdit) {
                        $amount = $total;
                        $job->addToChangeLog('Job Edited. Total Amount is: $' . number_format($amount, 2));
                        if ($employer->hasPackagePlan() && $employer->getAdsRemaining() > 0) {
                            $deduct = min(count($jobEdit->getIndustries()), $employer->getAdsRemaining());
                            $employer->deductAdsRemaining($deduct);
                        }
                    } else {
                        $amount = $jobManager->getJobChargeAmount($job);
                        $job->addToChangeLog('Job Created. Total Amount is: $' . number_format($amount, 2));
                        if ($employer->hasPackagePlan() && $employer->getAdsRemaining() > 0) {
                            $deduct = min($job->getIndustries()->count(), $employer->getAdsRemaining());
                            $employer->deductAdsRemaining($deduct);
                        }
                    }
                    $job->setCreateDate(new \DateTime('now'));
                    if ($renew) {
                        $job->updateExpirationDate();
                    }
                }

                $em = $this->get('doctrine')->getManager();
                $invoiceTrackManager = $this->get('journalismjobs.manager.invoice_track');
                $invoiceTrack = $invoiceTrackManager->create();
                $invoiceTrack->setBillingEmail($email);
                $invoiceTrack->setBillDate(new \DateTime('now'));
                $invoiceTrack->setEmployer($employer);
                $invoiceTrack->setAmount($payment->getChargeAmount());
                $invoiceTrack->setBillingType($payment->getPaymentMethod());
                $invoiceTrack->setPaidDate(new \DateTime('now'));
                $invoiceTrack->setAuthorizationCode($payment->getAuthorizationCode());
                $invoiceTrack->addPayment($payment);
                foreach ($jobs as $job) {
                    $invoiceTrack->addJob($job);
                    $invoiceTrack->addOrderText($jobManager->getJobOrderText($job));
                    $em->persist($job);
                }
                $em->persist($employer);
                $em->persist($payment);
                $em->persist($invoiceTrack);
                $em->flush();

                foreach ($jobs as $job) {
                    $this->get('journalismjobs.manager.job_view')->clearJobViewCount($job);
                }

                $this->get('journalismjobs.frontend.mailer')->sendInvoiceEmail($invoiceTrack);
                $session->remove($key);

                return $this->redirect($this->generateUrl('PostNewJobConfirmation', array(
                    'invoiceTrackId' => $invoiceTrack->getId(),
                    'jobs' => null,
                )));
            }
        }

        return array(
            'form' => $form->createView(),
            'employer' => $employer,
        );
    }

    /**
     * @Route("/employers/job-info/{invoiceTrackId}/confirmation", name="PostNewJobConfirmation")
     * @Template
     */
    public function confirmationAction($invoiceTrackId)
    {
        $invoiceTrack = $this->get('journalismjobs.manager.invoice_track')->find($invoiceTrackId);
        if (!$invoiceTrack) {
            throw new NotFoundHttpException(sprintf('Invoice Track %d not found.', $invoiceTrackId));
        }

        /** @var Employer $employer */
        $employer = $this->getUser();
        if ($invoiceTrack->getEmployer() != $employer) {
            throw new NotFoundHttpException(sprintf('Invoice Track %d does not belong to employer %d.'
                , $invoiceTrackId
                , $employer->getId()
            ));
        }

        return array(
            'employer' => $this->getUser(),
            'invoiceTrack' => $invoiceTrack,
        );
    }

    /**
     * @Route("/employers/job-info/{jobId}/credit-confirmation", name="PostNewJobCreditConfirmation")
     * @Template
     */
    public function creditConfirmationAction($jobId)
    {
        $jobs = $this->getJobs($jobId);

        return array(
            'employer' => $this->getUser(),
            'jobs' => $jobs,
        );
    }

    /**
     * @Route("/employers/job-info/{jobId}/edit-confirmation", name="EditJobConfirmation")
     * @Template
     */
    public function editConfirmationAction($jobId)
    {
        $job = $this->getJob($jobId);

        /** @var Employer $employer */
        $employer = $this->getUser();

        return array(
            'employer' => $employer,
            'job' => $job,
            'total' => $this->get('journalismjobs.manager.job')->getJobChargeAmount($job),
        );
    }

    /**
     * @Route("/employers/job-info/{jobId}/delete-or-suspend", name="DeleteOrSuspendJobPost")
     * @Template
     */
    public function suspendDeleteJobAdAction($jobId)
    {
        $job = $this->getJob($jobId);

        return array(
            'job' => $job,
        );
    }

    /**
     * @Route("/employers/job-info/{jobId}/delete", name="DeleteJobPost")
     * @Template
     */
    public function deleteAction($jobId)
    {
        $job = $this->getJob($jobId);
        $this->get('journalismjobs.frontend.mailer')->sendJobDeleteEmail($job);
        $this->get('journalismjobs.manager.job')->delete($job);

        return array(
            'jobId' => $jobId,
        );
    }

    /**
     * @Route("/employers/job-info/{jobId}/suspend", name="SuspendJobPost")
     * @Template
     */
    public function suspendAction($jobId)
    {
        /*$job = $this->getJob($jobId);
        $job->setExpirationDate(new \DateTime('-1 day'));
        $job->setFeatureExpirationDate(new \DateTime('-1 day'));
        $this->get('journalismjobs.manager.job')->save($job);
        $this->get('journalismjobs.frontend.mailer')->sendJobSuspendEmail($job);*/

        return array(
            'jobId' => $jobId,
        );
    }


    /**
     * @Route("/employers/job-info/{jobId}/suspendConfirmation", name="SuspendConfirmationJobPost")
     * @Template
     */
    public function suspendConfirmationAction(Request $request, $jobId)
    {
        $job = $this->getJob($jobId);
        $job->setExpirationDate(new \DateTime('-1 day'));
        $job->setFeatureExpirationDate(new \DateTime('-1 day'));
        $this->get('journalismjobs.manager.job')->save($job);
        $this->get('journalismjobs.frontend.mailer')->sendJobSuspendEmail($job);

        $request->getSession()
        ->getFlashBag()
        ->add('success', 'Your Ad was successfully suspended!');

        //return $this->redirect($this->generateUrl('SuspendJobPost', array('jobId' => $jobId)), array('jobId' => $jobId));

        if ($this->get('security.context')->isGranted('ROLE_EMPLOYER')) {
            return $this->redirect($this->generateUrl('EmployerManager'));
        }
        /*return array(
            'jobId' => $jobId,
        );*/
    }

    /**
     * @param $jobId
     * @return Job
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    protected function getJob($jobId)
    {
        /** @var Employer $employer */
        $employer = $this->getUser();

        /** @var Job $job */
        $job = $this->get('journalismjobs.manager.job')->find($jobId);

        if (!$job) {
            throw new NotFoundHttpException(sprintf('Job %d not found', $jobId));
        }

        if ($job->getEmployer() != $employer) {
            throw new NotFoundHttpException(sprintf('Job %d does not belong to employer %d'
                , $jobId
                , $employer->getId()
            ));
        }

        return $job;
    }

    /**
     * @param $jobId
     * @return Job[]
     */
    protected function getJobs($jobId)
    {
        $jobManager = $this->get('journalismjobs.manager.job');
        $employer = $this->getUser();
        $job = $this->getJob($jobId);
        $jobIds = $this->getRequest()->get('jobs', array());
        $jobs = array($job);
        foreach ($jobIds as $jId) {
            $job = $jobManager->find($jId);
            if ($job && $job->getEmployer() == $employer) {
                $jobs[] = $job;
            }
        }

        return $jobs;
    }

    public function generateUrl($route, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        if (!array_key_exists('jobs', $parameters)) {
            $jobs = $this->getRequest()->get('jobs');
            if ($jobs) {
                $parameters['jobs'] = $jobs;
            }
        }

        return $this->container->get('router')->generate($route, $parameters, $referenceType);
    }
}