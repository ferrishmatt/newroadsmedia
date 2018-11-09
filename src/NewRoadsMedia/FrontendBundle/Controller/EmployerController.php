<?php

namespace NewRoadsMedia\FrontendBundle\Controller;

use NewRoadsMedia\FrontendBundle\Entity\Employer;
use NewRoadsMedia\FrontendBundle\Entity\Job;
use NewRoadsMedia\FrontendBundle\Form\Type\ResumeSearchFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @Route("/employers/")
 */
class EmployerController extends Controller
{
	/**
   	 * @Route("manager", name="EmployerManager")
     * @Template
	 */
    public function managerAction()
    {
        $employer = $this->getEmployer();
        $request = $this->getRequest();
        $page = $request->get('page', 1);
        $max = 20;
        $firstResult = $max * ($page - 1);

        $jobManager = $this->get('journalismjobs.manager.job');
        $jobListings = $jobManager->getEmployerJobs($employer, $max, $firstResult);
        $jobCount = $jobManager->getEmployerJobCount($employer);

		return array(
            'employer' => $employer,
            'jobListings' => $jobListings,
            'jobViewManager' => $this->get('journalismjobs.manager.job_view'),
            'firstResult' => $firstResult,
            'showNext' => $firstResult + count($jobListings) < $jobCount,
            'showPrev' => $page > 1,
            'page' => $page,
        );
    }

    /**
     * @Route("resume-search", name="ResumeSearch")
     * @Template
     */
    public function resumeSearchAction()
    {
        $employer = $this->getEmployer();

        if (!$this->get('journalismjobs.manager.employer')->hasResumeAccess($employer)) {
            return $this->render('NewRoadsMediaFrontendBundle:Employer:resumeAccessDenied.html.twig');
        }

        $resumeManager = $this->get('journalismjobs.manager.resume');
        $request = $this->getRequest();
        $form = $this->createForm(new ResumeSearchFormType(), null, array(
            'method' => 'GET',
            'action' => $this->generateUrl('ResumeSearch'),
        ));
        $form->handleRequest($request);

        $showResults = $request->get($form->getName()) !== null;

        $page = $request->get('page', 1);
        $max = 100;
        $start = $max * ($page - 1);
        $end = $max * $page;
        $options = $form->getData();
        $options['maxResults'] = $end;
        $options['firstResult'] = $start;
        $resumes = $showResults ? $resumeManager->searchResumes($options) : array();
        $count = $resumeManager->searchResumesCount($options);
        $pageCount = ceil($count / $max);
        $start++;
        $end = min($end, $count);

        $response = array(
            'form' => $form->createView(),
            'start' => $start,
            'end' => $end,
            'count' => $count,
            'page' => $page,
            'results' => $resumes,
            'pageCount' => $pageCount,
            'hasNextButton' => $page < $pageCount,
            'params' => $request->query->all(),
            'max' => $max,
        );

        if ($showResults) {
            return $this->render('NewRoadsMediaFrontendBundle:Employer:resumeSearchResults.html.twig', $response);
        }

        return $response;
    }

    /**
     * @Route("edit-billing", name="EditEmployerBilling")
     * @Template
     */
    public function editBillingAction()
    {
        $employer = $this->getEmployer();
        $form = $this->createFormBuilder($employer, array(
                'method' => 'POST',
                'action' => $this->generateUrl('EditEmployerBilling'),
                'attr' => array('novalidate' => 'novalidate'),
            ))
            ->add('name', 'text', array(
                'constraints' => array(
                    new NotBlank(array('message' => 'Name cannot be blank.')),
                ),
                'error_bubbling' => true,
                'required' => true,
            ))
            ->add('company', 'text', array(
                'constraints' => array(
                    new NotBlank(array('message' => 'Company cannot be blank.')),
                ),
                'error_bubbling' => true,
                'required' => true,
            ))
            ->add('billingCompany', 'text')
            ->add('title', 'text')
            ->add('phone', 'text')
            ->add('phoneArea', 'text')
            ->add('faxNumber', 'text')
            ->add('address', 'text')
            ->add('city', 'text')
            ->add('state', 'text')
            ->add('zip', 'text')
            ->add('zipPlus', 'text', array(
                'required' => false,
            ))
            ->add('specialBillingInstructions', 'textarea')
            ->getForm()
        ;
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            $employer = $form->getData();
            $this->get('journalismjobs.manager.employer')->save($employer);
            $this->get('session')->getFlashBag()->add('notice', 'Your changes have been saved.');

            return $this->redirect($this->generateUrl('EmployerManager'));
        }

        return array(
            'employer' => $employer,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("applications/{jobId}", name="ViewAllApplications")
     * @Template
     */
    public function applicationsAction($jobId)
    {
        $job = $this->getJob($jobId);

        $request = $this->getRequest();
        if ($request->isMethod('POST')) {
            $deleteItems = $request->get('delete_items');
            if ($deleteItems) {
                $count = count($deleteItems);
                $this->get('journalismjobs.manager.application')->deleteApplications($job, $deleteItems);
                $this->get('session')->getFlashBag()->add('notice', sprintf('%d application%s deleted.'
                    , $count
                    , $count == 1 ? '' : 's'
                ));

                return $this->redirect($this->generateUrl('ViewAllApplications', array(
                    'jobId' => $jobId,
                )));
            }
        }

        return array(
            'job' => $job,
        );
    }

    /**
     * @Route("applications/{jobId}/application/{applicationId}", name="ViewApplication")
     * @Template
     */
    public function applicationAction($jobId, $applicationId)
    {
        $job = $this->getJob($jobId);
        $applicationManager = $this->get('journalismjobs.manager.application');
        $application = $applicationManager->find($applicationId);

        if ($application->getJob() != $job) {
            throw new NotFoundHttpException(sprintf('Application %d found but is not for job %d.'
                , $applicationId
                , $jobId
            ));
        }

        $user = $this->getUser();
        if ($user != $job->getEmployer()) {
            throw new AccessDeniedHttpException(sprintf('Employer does not have access to this application. User id %d, job id %d, application id %d.'
                , $user->getId()
                , $job->getId()
                , $application->getId()
            ));
        }

        if (!$application->getIsViewed()) {
            $application->setIsViewed(true);
            $applicationManager->save($application);
        }

        $resumeFileLink = null;
        if ($application->getResumeFile()) {
            $resumeFileLink = $this->generateUrl('DownloadApplicationResume', array('applicationId' => $applicationId));
        } else if ($application->getResume() && $application->getResume()->getResumeFile()) {
            $resumeFileLink = $this->generateUrl('DownloadResume', array('resumeId' => $application->getResume()->getId()));
        }

        return array(
            'application' => $application,
            'resumeFileLink' => $resumeFileLink,
        );
    }

    /**
     * @Route("/applications/download/{applicationId}", name="DownloadApplicationResume")
     */
    public function downloadApplicationResumeAction($applicationId)
    {
        $applicationManager = $this->get('journalismjobs.manager.application');
        $application = $applicationManager->find($applicationId);
        if (!$application) {
            throw new NotFoundHttpException(sprintf('Application %d not found.', $applicationId));
        }

        $path = $applicationManager->getResumeFilePath($application);
        $content = file_get_contents($path);
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $type = finfo_file($finfo, $path);

        $response = new Response();
        $response->headers->set('Content-Type', $type);
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $application->getResumeFile());
        $response->setContent($content);

        return $response;
    }

    /**
     * @param $jobId
     * @return Job
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    protected function getJob($jobId)
    {
        $employer = $this->getEmployer();

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
     * @return Employer
     */
    protected function getEmployer()
    {
        return $this->getUser();
    }
}