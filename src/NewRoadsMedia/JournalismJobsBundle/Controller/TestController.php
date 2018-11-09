<?php

namespace NewRoadsMedia\JournalismJobsBundle\Controller;

use NewRoadsMedia\FrontendBundle\Controller\HomePageController as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/test")
 */
class TestController extends BaseController
{
    /**
     * @Route("/reasons_for_teaching", name="TestReasonsForTeaching")
     * @Template
     */
    public function reasonsForTeachingAction()
    {
        die('here');
        return array();
    }

    /**
     * @Route("/{jobLink}", name="ViewJob")
     * @Template
     */
    public function viewJobAction($jobLink)
    {
        $job_id = explode('-', $jobLink)[0];
        $job    = $this->get('journalismjobs.manager.job')->findActive($job_id);

        if ($job->getPermLink() !== $jobLink){
            return new Response($this->renderView('NewRoadsMediaFrontendBundle:JobListing:notFoundListing.html.twig'), 404);
        }

        $response = $this->forward('NewRoadsMediaFrontendBundle:JobListing:view', [
            'jobId'  => $job_id,
            '_route' => $this->getRequest()->attributes->get('_route'),
            '_route_params' => $this->getRequest()->attributes->get('_route_params')
        ]);

        return $response;
    }
}