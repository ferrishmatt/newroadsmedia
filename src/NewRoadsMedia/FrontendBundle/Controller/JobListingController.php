<?php

namespace NewRoadsMedia\FrontendBundle\Controller;

use NewRoadsMedia\FrontendBundle\Entity\Application;
use NewRoadsMedia\FrontendBundle\Entity\Job;
use NewRoadsMedia\FrontendBundle\Entity\Resume;
use NewRoadsMedia\FrontendBundle\Form\Type\JobListingEmailFormType;
use NewRoadsMedia\FrontendBundle\Form\Type\LoginFormType;
use NewRoadsMedia\FrontendBundle\Form\Type\NonRegisteredApplicationFormType;
use NewRoadsMedia\FrontendBundle\Form\Type\RegisteredApplicationFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;


class JobListingController extends Controller
{
    /**
     * @Route("/{jobLink}", name="ViewJob", requirements={"jobLink": "^\d+?\-.*"})
     * @Route("/job-listing/{jobId}", name="ViewJobPost", requirements={"jobId": "\d+"})
     */
    public function viewAction($jobId = false, $jobLink = false)
    {
        $request = $this->getRequest();

        if ($jobLink){
            $jobId = explode('-', $jobLink)[0];
            $job   = $this->getJob($jobId);

            if ($job and $job->getPermLink() !== $jobLink){
                $job = false;
            }
        }elseif($jobId){
            $job = $this->getJob($jobId);
        }else{
            return $this->forward('NewRoadsMediaFrontendBundle:JobSearch:search', [
                'query'       => $request->query,
                'test'          => $request->get('test'),
                'industry'      => $request->get('industry'),
                '_route'        => $request->attributes->get('_route'),
                '_route_params' => $request->attributes->get('_route_params'),
            ]);
        }

        if (!$job) {
            return new Response($this->renderView('NewRoadsMediaFrontendBundle:JobListing:notFoundListing.html.twig'), 404);
        }
        if ($job->getIsExternal()) {
            return $this->redirect($job->getWebsite());
        }

        if ($job->hasExpired()) {
            $user = $this->getUser();
            if (!$this->get('security.context')->isGranted('ROLE_EMPLOYER') || $job->getEmployer() != $user) {
                return $this->render('NewRoadsMediaFrontendBundle:JobListing:expiredListing.html.twig', array(
                    'job' => $job,
                ));
            }
        }

        $uploadCare = $this->get('journalismjobs.frontend.uploadcare');
        $applicationManager = $this->get('journalismjobs.manager.application');
        $isLoggedIn = $this->get('security.context')->isGranted('ROLE_JOB_SEEKER');
        $isUpload = in_array($job->getApplyType(), array('email', 'b'));
        $alreadyApplied = $isLoggedIn && $applicationManager->findOneBy(array(
            'job' => $job,
            'resume' => $this->getUser(),
        )) !== null;
        $nonRegisteredForm = $this->createForm(new NonRegisteredApplicationFormType($uploadCare), null, array(
            'action' => $this->generateUrl('ViewJob', array('jobLink' => $job->getPermLink())),
            'method' => 'POST',
            'attr' => array('novalidate' => 'novalidate'),
            'upload' => $isUpload,
            'is_mobile' => $this->get('journalismjobs.frontend.device')->isMobile(),
        ));
        $registeredForm = $this->createForm(new RegisteredApplicationFormType(), null, array(
            'action' => $this->generateUrl('ViewJob', array('jobLink' => $job->getPermLink())),
            'method' => 'POST',
            'attr' => array('novalidate' => 'novalidate'),
            'login' => !$isLoggedIn,
        ));
        $nonRegisteredForm->handleRequest($request);
        $registeredForm->handleRequest($request);

        // non-registered form submission
        if ($nonRegisteredForm->isSubmitted()) {
            if ($nonRegisteredForm->isValid()) {
                if ($isUpload) {
                    $path = null;
                    if ($nonRegisteredForm->has('resumeFile')) {
                        $fileId = $nonRegisteredForm->get('resumeFile')->getData();
                        if ($fileId) {
                            $file = $uploadCare->getFile($fileId);
                            $fileInfo = $file->updateInfo();
                            $path = tempnam(sys_get_temp_dir(), 'resume-') . '-' . $fileInfo['original_filename'];
                            file_put_contents($path, file_get_contents($file->getUrl()));
                            $file->delete();
                        }
                    }
                    if ($job->getApplyType() == 'email') {
                        $this->get('journalismjobs.frontend.mailer')->sendEmailApplication($job, $nonRegisteredForm->getData(), $path);
                        $this->get('journalismjobs.manager.job')->incrementEmailApplicationCount($job);
                    } else {
                        $email = $nonRegisteredForm->get('email')->getData();
                        $coverLetter = $nonRegisteredForm->get('coverLetter')->getData();
                        $resumeText = $nonRegisteredForm->has('resumeText') ? $nonRegisteredForm->get('resumeText')->getData() : null;
                        $applicationManager->apply($job, $email, $coverLetter, $resumeText, null, $path);
                    }
                }
                $this->get('session')->getFlashBag()->add('notice', 'Your job application has been successfully submitted!');

                return $this->redirect($this->generateUrl('ViewJob', array(
                    'jobLink' => $job->getPermLink(),
                )));
            } else {
                $errors = trim($nonRegisteredForm->getErrorsAsString());
                $fileId = $nonRegisteredForm->get('resumeFile')->getData();
                if ($fileId && strpos($errors, 'Captcha') === false) {
                    $file = $uploadCare->getFile($fileId);
                    $fileInfo = $file->updateInfo();
                    $mimeType = strtolower($fileInfo['mime_type']);
                    $parts = explode('.', $fileInfo['original_filename']);
                    $extension = $parts ? array_pop($parts) : 'EXPLOSION FAILED';
                    $userAgent = $request->headers->get('User-Agent');
                    $logger = $this->get('journalismjobs.application_logger');
                    $message = 'Job: ' . $jobId . ', '
                        . 'Name: ' . $nonRegisteredForm->get('name')->getData() . ', '
                        . 'Email: ' . $nonRegisteredForm->get('email')->getData() . ', '
                        . 'File ID: ' . $fileId . ', '
                        . 'Errors: ' . $errors  . ', '
                        . 'Mime: ' . $mimeType . ', '
                        . 'Ext: ' . $extension . ', '
                        . 'User Agent: ' . $userAgent;
                    $logger->err($message);
                }
            }
        }

        // registered form submission
        if ($registeredForm->isValid()) {
            if ($isLoggedIn) {
                $resume = $this->getUser();
            } else {
                $userService = $this->get('journalismjobs.frontend.user');
                $username = $registeredForm->get('login')->get('_username')->getData();
                $password = $registeredForm->get('login')->get('_password')->getData();
                $resume = $userService->getResumeFromUsernameAndPassword($username, $password);
                if ($resume) {
                    $userService->loginUser($resume, 'job_seekers');
                    $isLoggedIn = true;
                }
            }
            if ($resume) {
                $existingApplication = $applicationManager->findOneBy(array(
                    'job' => $job,
                    'resume' => $resume,
                ));
                if ($existingApplication) {
                    $this->get('session')->getFlashBag()->add('error', 'You have already submitted an application to this job posting.');
                } else {
                    $coverLetter = $registeredForm->get('coverLetter')->getData();
                    if ($job->getApplyType() == 'email') {
                        $path = $this->get('journalismjobs.manager.resume')->getResumePath($resume);
                        $this->get('journalismjobs.frontend.mailer')->sendEmailApplication($job, array(
                            'name' => $resume->getName(),
                            'email' => $resume->getEmail(),
                            'coverLetter' => $coverLetter,
                            'resumeText' => $path ? null : $resume->getResumeWorkExperience(),
                        ), $path);
                        $this->get('journalismjobs.manager.job')->incrementEmailApplicationCount($job);
                    } else {
                        $applicationManager->apply($job, $resume->getEmail(), $coverLetter, null, $resume);
                    }
                    $this->get('session')->getFlashBag()->add('notice', 'Your job application has been successfully submitted!');
                }

                return $this->redirect($this->generateUrl('ViewJob', array( 'jobLink' => $job->getPermLink() )));
            }

            $registeredForm->addError(new FormError('The username and/or password you entered is incorrect. Please try again.'));
        }

        $emailForm = $this->createForm(new JobListingEmailFormType(), null, array(
            'action' => $this->generateUrl('ViewJob', array('jobLink' => $job->getPermLink())),
            'method' => 'POST',
        ));
        $emailForm->add('captcha', 'captcha', [
           'length' => 5,
           'attr' => ['maxlength' => 10],
           'invalid_message' => 'Security code is invalid',
           'background_color' => [255, 255, 255],
           'height' => 30,
           'width' => 80,
           'error_bubbling' => true,
           'reload' => false,
           'label' => 'Type in this Security Code Below'
        ]);
        $emailForm->handleRequest($this->getRequest());
        if ($emailForm->isSubmitted()) {
            if ($emailForm->isValid()) {
                $fromEmail = $emailForm->get('your_email')->getData();
                $toEmail = $emailForm->get('recipient_email')->getData();
                $comments = $emailForm->get('comments')->getData();
                $this->get('journalismjobs.frontend.mailer')->sendJobListingEmail($fromEmail, $toEmail, $comments, $job);

                return new JsonResponse(array('message' => 'This job listing was successfully emailed.'));
            } else {
                $errors = array();
                foreach ($emailForm->getErrors() as $emailFormError) {
                    $errors[] = $emailFormError->getMessage();
                }

                return new JsonResponse($errors, 400);
            }
        }

        $response = $this->render('NewRoadsMediaFrontendBundle:JobListing:view.html.twig', array(
            'emailForm' => $emailForm->createView(),
            'nonRegisteredForm' => $nonRegisteredForm->createView(),
            'registeredForm' => $registeredForm->createView(),
            'job' => $job,
            'isLoggedIn' => $isLoggedIn,
            'alreadyApplied' => $alreadyApplied,
        ));

        $userAgent = $request->headers->get('User-Agent');
        $isABot = preg_match('/bot/i', $userAgent) || preg_match('/crawler/i', $userAgent);
        $employerViewing = $this->get('security.context')->isGranted('ROLE_EMPLOYER') && $job->getEmployer() == $this->getUser();
        if (!$isABot && !$employerViewing) {
            $userGuid = $request->cookies->get('UserGUID');
            if (!$userGuid) {
                $userGuid = uniqid();
                $cookie = new Cookie('UserGUID', $userGuid);
                $response->headers->setCookie($cookie);
            }

            $this->get('journalismjobs.manager.job_view')->incrementJobViewCount($job, $userGuid);
        }

        if ($job->wasPostedToday()) {
            $this->get('journalismjobs.frontend.last_activity')->setJobView($job);
        }
        // $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

	/**
   	 * @Route("/job-listings/{jobId}/preview", name="PreviewJobPost", requirements={"jobId": "\d+"})
     * @Template()
	 */
    public function previewAction($jobId)
    {
        $job = $this->getJob($jobId);
        if (!$job) {
            return new Response($this->renderView('NewRoadsMediaFrontendBundle:JobListing:notFoundListing.html.twig'), 404);
        }
        if ($job->hasExpired()) {
            return $this->render('NewRoadsMediaFrontendBundle:JobListing:expiredListing.html.twig', array(
                'job' => $job,
            ));
        }

        return array(
            'job' => $job,
        );
    }

    /**
     * @Route("/job-listings/{jobId}/email", name="EmailJobPost", requirements={"jobId": "\d+"})
     * @Template()
     */
    public function emailAction($jobId)
    {
        $job = $this->getJob($jobId);
        if (!$job) {
            return new Response($this->renderView('NewRoadsMediaFrontendBundle:JobListing:notFoundListing.html.twig'), 404);
        }
        if ($job->hasExpired()) {
            return $this->render('NewRoadsMediaFrontendBundle:JobListing:expiredListing.html.twig', array(
                'job' => $job,
            ));
        }

        $form = $this->createForm(new JobListingEmailFormType(), array(
            'action' => $this->generateUrl('EmailJobPost', array('jobId' => $jobId)),
            'method' => 'POST',
        ));
        $form->handleRequest($this->getRequest());

        //if this form is valid, then save the new data.
        if ($form->isValid()) {
            $fromEmail = $form->get('your_email')->getData();
            $toEmail = $form->get('recipient_email')->getData();
            $comments = $form->get('comments')->getData();
            $this->get('journalismjobs.frontend.mailer')->sendJobListingEmail($fromEmail, $toEmail, $comments, $job);
            $this->get('session')->getFlashBag()->add('notice', 'This job listing was successfully emailed.');

            return $this->redirect($this->generateUrl('ViewJob', ['jobLink' => $job->getPermLink()]));
        }

        return array(
            'form' => $form->createView(),
            'job' => $job,
        );
    }

    /**
     * @param $jobId
     * @return Job
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    protected function getJob($jobId)
    {
        /** @var Job $job */
        $job = $this->get('journalismjobs.manager.job')->findActive($jobId);

        return $job;
    }
}