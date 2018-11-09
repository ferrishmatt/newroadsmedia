<?php

namespace NewRoadsMedia\FrontendBundle\Controller;

use NewRoadsMedia\FrontendBundle\Entity\Resume;
use NewRoadsMedia\FrontendBundle\Form\Type\UploadResumeFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ResumeController extends Controller
{
	/**
   	 * @Route("/view-resume/{resumeId}", name="ViewResume", requirements={"resumeId" = "\d+"})
     * @Template()
	 */
    public function viewAction($resumeId)
    {
        $resumeManager = $this->get('journalismjobs.manager.resume');
        $resume = $resumeManager->find($resumeId);
        if (!$resume) {
            throw new NotFoundHttpException(sprintf('Resume %d not found.', $resumeId));
        }

        $hasResumeAccess = false;
        if ($this->get('security.context')->isGranted('ROLE_EMPLOYER')) {
            $resume->incrementViewCount();
            $resumeManager->save($resume);
            if ($this->get('journalismjobs.manager.employer')->hasResumeAccess($this->getUser())) {
                $hasResumeAccess = true;
            }
        } else {
            $user = $this->getUser();
            if ($user != $resume) {
                throw new AccessDeniedException('Job seekers only have access to their own resume.');
            }
            $hasResumeAccess = true;
        }

        return array(
            'resume' => $resume,
            'user' => $this->getUser(),
            'hasResumeAccess' => $hasResumeAccess,
        );
    }

    /**
     * @Route("/job-seeker/edit-resume", name="EditResume")
     * @Route("/job-seeker/post-resume", name="PostResume")
     * @Template()
     */
    public function editAction()
    {
        if (!$this->get('security.context')->isGranted('ROLE_JOB_SEEKER')) {
            return $this->redirect($this->generateUrl('LoginPostResume'));
        }

        /** @var Resume $resume */
        $resume = $this->getUser();

        $action = $resume && $resume->getOkd() ? 'edit' : 'publish';
        $form = $this->createForm('resume', $resume, array(
            'action' => $this->generateUrl('EditResume'),
            'method' => 'POST',
            'attr'   => array('novalidate' => 'novalidate', 'id' => 'resume_form'),
        ));
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            /** @var Resume $resume */
            $resume = $form->getData();
            $resume->setExpiration(new \DateTime('+12 months'));
            $resume->setOkd(true);
            $this->get('journalismjobs.manager.resume')->save($resume);
            $this->get('session')->getFlashBag()->add('notice', 'Your resume has been saved.');
            if ($action == 'publish') {
		$jobSeekerLoginUrl = $this->generateUrl('LoginJobSeeker');
                $this->get('journalismjobs.frontend.mailer')->sendNewResumeEmail($resume, $jobSeekerLoginUrl);

                return $this->redirect($this->generateUrl('JobSeekerFolder'));
            }

            return $this->redirect($this->generateUrl('EditResume'));
        } else if ($this->getRequest()->isMethod('POST') && !$form->getErrors()) {
            $this->get('session')->getFlashBag()->add('failure', 'There were errors on your submission.  Please correct the errors below and resubmit.');
        }

        return array(
            'action' => $action,
            'form' => $form->createView(),
            'resume' => $resume,
        );
    }

    /**
     * @Route("/job-seeker/resume/delete", name="DeleteResume")
     */
    public function deleteAction()
    {
        /** @var Resume $resume */
        $resume = $this->getUser();
        $resume->setOkd(false);
        $this->get('journalismjobs.manager.resume')->save($resume);

        $resumeManager = $this->get('journalismjobs.manager.resume');
        $resumeManager->deleteResumeFile($resume);

        $this->get('session')->getFlashBag()->add('notice', 'Your resume has been removed from all listings.');

        return $this->redirect($this->generateUrl('JobSeekerFolder'));
    }

    /**
     * @Route("/job-seeker/delete-image", name="DeleteProfileImage")
     * @Template()
     */
    public function deleteImageAction()
    {
        /** @var Resume $resume */
        $resume = $this->getUser();
        if (!$resume->getProfilePicture()) {
            $this->get('session')->getFlashBag()->add('notice', 'Your profile photo has already been deleted.');

            return $this->redirect($this->generateUrl('EditResume'));
        }

        $form = $this->createFormBuilder(null, array(
            'method' => 'POST',
            'action' => $this->generateUrl('DeleteProfileImage'),
        ))
            ->add('submit', 'submit', array(
                'label' => 'Delete Image',
            ))
            ->getForm()
        ;
        $form->handleRequest($this->getRequest());
        if ($this->getRequest()->isMethod('POST')) {
            if ($form->isValid() && $form->get('submit')->isClicked()) {
                $resumeManager = $this->get('journalismjobs.manager.resume');
                $resumeManager->deleteProfilePicture($resume);
                $resumeManager->save($resume);
                $this->get('session')->getFlashBag()->add('notice', 'Your profile photo has been deleted.');

                return $this->redirect($this->generateUrl('EditResume'));
            }
        }

        return array(
            'form' => $form->createView(),
            'resume' => $resume,
        );
    }

    /**
     * @Route("/job-seeker/resume/renew", name="RenewResume")
     */
    public function renewAction()
    {
        /** @var Resume $resume */
        $resume = $this->getUser();
        $resume->setDateEntered(new \DateTime());
        $resume->setExpiration(new \DateTime('+60 days'));
        $resume->setViewCount(0);
        $this->get('journalismjobs.manager.resume')->save($resume);
        $this->get('session')->getFlashBag()->add('notice', 'Your resume has been repositioned in the resume database.');

        return $this->redirect($this->generateUrl('JobSeekerFolder'));
    }

    /**
     * @Route("/job-seeker/upload-profile", name="UploadProfile")
     * @Template()
     */
    public function uploadAction()
    {
        /** @var Resume $resume */
        $resume = $this->getUser();
        $form = $this->createForm(new UploadResumeFormType(), $resume, array(
            'method' => 'POST',
            'action' => $this->generateUrl('UploadProfile'),
            'attr' => array('novalidate' => 'novalidate'),
        ));
        $form->handleRequest($this->getRequest());
        if ($this->getRequest()->isMethod('POST')) {
            if ($form->isValid()) {
                $resumeManager = $this->get('journalismjobs.manager.resume');

                if ($form->has('deleteResumeFile') && $form->get('deleteResumeFile')->getData()) {
                    $resumeManager->deleteResumeFile($resume);
                }

                /** @var UploadedFile $file */
                $file = $form->get('resumeFile')->getData();
                if ($file) {
                    $directory = $resumeManager->getResumeDirectory();
                    $rand = sha1(uniqid(mt_rand(), true));
                    $filename = 'resume.' . $rand . '.' . $file->guessClientExtension();
                    $file->move($directory, $filename);
                    $resumeManager->deleteResumeFile($resume);
                    $resume->setResumeFile($filename);
                }

                $resumeManager->save($resume);

                return $this->redirect($this->generateUrl('EditResume'));
            }
        }

        return array(
            'form' => $form->createView(),
            'resume' => $resume,
        );
    }

    /**
     * @Route("/resume/download/{resumeId}", name="DownloadResume")
     */
    public function downloadResumeAction($resumeId)
    {
        $resume = $this->get('journalismjobs.manager.resume')->find($resumeId);
        if (!$resume) {
            throw new NotFoundHttpException(sprintf('Resume %d not found.', $resumeId));
        }

        $path = $this->getResumeFilePath($resume);
        $content = file_get_contents($path);
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $type = finfo_file($finfo, $path);

        $response = new Response();
        $response->headers->set('Content-Type', $type);
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $resume->getResumeFile());
        $response->setContent($content);

        return $response;
    }

    /**
     * @Route("/resume/preview/{resumeId}", name="PreviewResumeFile")
     */
    // public function previewResumeAction($resumeId)
    // {
    //     $resume = $this->get('journalismjobs.manager.resume')->find($resumeId);
    //     if (!$resume) {
    //         throw new NotFoundHttpException(sprintf('Resume %d not found.', $resumeId));
    //     }

    //     $path = $this->getResumeFilePath($resume);
    //     $cloudConvert = $this->get('journalismJobs.frontend.cloudconvert');
    //     $html = $cloudConvert->convertToHtml($path, $resume->getResumeFile());

    //     $response = new Response();
    //     $response->setContent($html);

    //     return $response;
    // }
    public function previewResumeAction($resumeId)
    {
        $resume = $this->get('journalismjobs.manager.resume')->find($resumeId);
        if (!$resume) {
            throw new NotFoundHttpException(sprintf('Resume %d not found.', $resumeId));
        }

        $path            = $this->getResumeFilePath($resume);
        $pathinfo        = pathinfo($path);
        $ps_filename     = $pathinfo['filename'] . '.ps';
        $pdf_resume_path = str_replace('/resumes', '/pdf-resumes', $pathinfo['dirname']) . '/' . $pathinfo['filename'] . '.pdf';

        if (!file_exists($pdf_resume_path)){
            if ($pathinfo['extension'] == 'pdf'){
                copy($path, $pdf_resume_path);
            }else{
                // $command = 'export HOME=/tmp && soffice --headless --convert-to html --outdir ' . $path_parts['dirname'] . ' ' . $path . ' 2>&1';
                $command = 'export HOME=/tmp && soffice --headless --print-to-file --outdir /tmp ' . $path . ' 2>&1';
                exec($command);

                // $command = 'gs -sDEVICE=jpeg -dJPEGQ=100 -dNOPAUSE -dBATCH -dSAFER -r140 -sOutputFile=' . $jpg_resume_path . ' /tmp/' . $ps_filename;
                $command = 'ps2pdf /tmp/' . $ps_filename . ' ' . $pdf_resume_path . ' 2>&1';

                print exec($command);
            }
        }
        $response = new BinaryFileResponse($pdf_resume_path);
        return $response;
    }

    protected function getResumeFilePath(Resume $resume)
    {
        $path = $this->get('journalismjobs.manager.resume')->getResumePath($resume);
        if (!$path) {
            throw new NotFoundHttpException(sprintf('There was a problem getting the resume file for id %d.', $resume->getId()));
        }

        $user = $this->getUser();
        $accessDeniedException = new AccessDeniedHttpException('Access Denied to Resume Download.');
        if ($this->get('security.context')->isGranted('ROLE_EMPLOYER')) {
            if (!$this->get('journalismjobs.manager.employer')->hasResumeAccess($user)) {
                throw $accessDeniedException;
            }
        } else if ($this->get('security.context')->isGranted('ROLE_JOB_SEEKER')) {
            if ($user != $resume) {
                throw $accessDeniedException;
            }
        } else {
            throw $accessDeniedException;
        }

        return $path;
    }
}
