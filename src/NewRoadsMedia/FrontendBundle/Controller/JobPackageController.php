<?php

namespace NewRoadsMedia\FrontendBundle\Controller;

use NewRoadsMedia\FrontendBundle\Entity\Employer;
use NewRoadsMedia\FrontendBundle\Form\Type\PostNewJobBillingFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class JobPackageController extends Controller
{
    /**
     * @Route("/job-packages/purchase", name="MultipleJobAds")
     * @Template
     */
    public function multipleJobAdsAction()
    {
        /** @var Employer $employer */
        $employer = $this->getUser();
        $form = $this->createFormBuilder(null, array(
                'method' => 'POST',
                'action' => $this->generateUrl('MultipleJobAds'),
            ))
            ->add('jobPackage', 'choice', array(
                'expanded' => true,
                'multiple' => false,
                'choices' => array(
                    4 => 'Buy 4 job ads, receive 1 free',
                    9 => 'Buy 9 job ads, receive 3 free',
                    16 => 'Buy 16 job ads, receive 5 free',
                    25 => 'Buy 25 job ads, receive 8 free',
                ),
                'label' => 'Select Job Package',
                'data' => $this->getRequest()->get('jobPackage'),
            ))
            ->add('billing', new PostNewJobBillingFormType(), array(
                'payment_methods' => false,
                'label' => false,
                'data' => $employer,
            ))
            ->getForm()
        ;
        $form->handleRequest($this->getRequest());
        $jobPackage = $form->get('jobPackage')->getData();
        $total =  $jobPackage * $this->container->getParameter('ad_cost');
        if ($form->isValid()) {
            $billing = $form->get('billing');
            $paymentManager = $this->get('journalismjobs.manager.payment');
            $payment = $paymentManager->create();
            $payment->setPaymentMethod('creditcard');
            $payment->setEmployer($employer);
            $payment->setCreditCardNumber($billing->get('creditNumber')->getData());
            $payment->setExpirationDateMonth($billing->get('creditMonth')->getData());
            $payment->setExpirationDateYear($billing->get('creditYear')->getData());
            $payment->setNameOnCard($billing->get('name')->getData());
            $payment->setApproval(false);
            $payment->setTransactionDate(new \DateTime());
            $payment->setChargeAmount($total);
            $payment->setCreditCard($billing->get('creditCard')->getData()->getCardName());
            $response = $paymentManager->chargeCreditCard($payment, $billing->get('cardCode')->getData(), $billing->get('billingCompany')->getData());
            if ($response['response_code'] == 1) {
                switch ($jobPackage) {
                    case 4:
                        $jobPackage += 1;
                        break;
                    case 9:
                        $jobPackage += 3;
                        break;
                    case 16:
                        $jobPackage += 5;
                        break;
                    case 25:
                        $jobPackage += 8;
                        break;
                }

                $this->get('session')->getFlashBag()->add('notice', sprintf('%d ads have been added to your account!', $jobPackage));
                $employer->setHasPackagePlan(true);
                $employer->addAdsRemaining($jobPackage);
                $this->get('journalismjobs.manager.employer')->persist($employer);
                $paymentManager->save($payment);

                $this->get('journalismjobs.frontend.mailer')->sendJobPackageReceipt($payment, $jobPackage);

                return $this->redirect($this->generateUrl('EmployerManager'));
            } else {
                $this->get('session')->getFlashBag()->add('error', $response['response_msg']);
                $paymentManager->save($payment);
            }
        }

        return array(
            'form' => $form->createView(),
            'total' => $total,
        );
    }

    /**
     * @Route("/unlimited-jobs", name="UnlimitedJobs")
     * @Template
     */
    public function unlimitedJobsAction()
    {
        $form = $this->createFormBuilder(null, array(
                'attr' => array('novalidate' => 'novalidate'),
            ))
            ->add('name', 'text', array(
                'required' => true,
                'constraints' => array(
                    new NotBlank(array('message' => 'Name is required.')),
                ),
                'label' => 'Name*',
            ))
            ->add('company', 'text', array(
                'required' => true,
                'label' => 'Organization / Company*',
                'constraints' => array(
                    new NotBlank(array('message' => 'Organization / Company is required.')),
                ),
            ))
            ->add('organizationType', 'choice', array(
                'choices' => array(
                    'For-profit' => 'For-profit',
                    'Nonprofit' => 'Nonprofit',
                ),
                'multiple' => false,
                'expanded' => false,
                'empty_value' => 'Select One',
                'required' => true,
                'label' => 'Organization Type*',
                'constraints' => array(
                    new NotBlank(array('message' => 'Organization Type is required.')),
                ),
            ))
            ->add('numberOfEmployees', 'text', array(
                'required' => true,
                'label' => 'Number of Employees*',
                'constraints' => array(
                    new NotBlank(array('message' => 'Number of Employees is required.')),
                ),
            ))
            ->add('phone', 'text', array(
                'required' => true,
                'label' => 'Telephone*',
                'constraints' => array(
                    new NotBlank(array('message' => 'Telephone is required.')),
                    new Regex(array(
                        'pattern' => '/^(\((\d{3})\)|(\d{3}))\s*[-\.]?\s*(\d{3})\s*[-\.]?\s*(\d{4})$/',
                        'message' => 'A valid telphone number is required.'
                    )),
                ),
            ))
            ->add('email', 'email', array(
                'required' => true,
                'label' => 'Email Address*',
                'constraints' => array(
                    new NotBlank(array('message' => 'Email Address is required.')),
                    new Email(array('message' => 'A valid email address is required.')),
                ),
            ))
            ->add('info', 'textarea', array(
                'required' => true,
                'label' => 'Additional Information',
            ))
            ->add('submit', 'submit', array(
                'label' => 'Request Information',
            ))
            ->getForm()
        ;
        $form->handleRequest($this->getRequest());
        if ($form->isValid() && $form->get('submit')->isClicked()) {
            $data = array();
            foreach ($form as $child) {
                /** @var Form $child */
                if ($child->getName() != 'submit') {
                    $key = str_replace('*', '', $child->getConfig()->getOption('label'));
                    $value = $child->getData();
                    $data[$key] = $value;
                }
            }
            $email = $form->get('email')->getData();
            $this->get('journalismjobs.frontend.mailer')->sendUnlimitedJobAdsEmail($email, $data);
            $this->get('session')->getFlashBag()->add('notice', 'Your request has been sent!');

            return $this->redirect($this->generateUrl('UnlimitedJobs'));
        }

        return array(
            'form' => $form->createView(),
        );
    }
}