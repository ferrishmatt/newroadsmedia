<?php
/* Here Dec 21 9:41*/
namespace NewRoadsMedia\FrontendBundle\Controller;

use NewRoadsMedia\FrontendBundle\Entity\Industry;
use NewRoadsMedia\FrontendBundle\Entity\Job;
use NewRoadsMedia\FrontendBundle\Entity\Location;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use GuzzleHttp\Client;

use NewRoadsMedia\FrontendBundle\Form\Type\CreateJobSeekerAccountFormType;
use Symfony\Component\Form\FormError;

class JobSearchController extends Controller
{
    /**
   	 * @Route("/job-listings/{media}", name="ViewMediaJobs")
	 */
    public function showJobPosts($media)
    {
        // catch the old URL for job listing (e.g. job-listings/1635643)
        if (is_numeric($media)){
            $job = $this->get('journalismjobs.manager.job')->findActive($media);
            if ($job){
                return $this->redirect('/' . $job->getPermLink());
            }
        }
        //replace underscore with space.
        $media = str_replace("_", " ", $media);

        if ($media == 'freelance') {
            return $this->forward('NewRoadsMediaFrontendBundle:JobSearch:search', $this->getRequest()->attributes->all(), array(
                'jobType' => 4,
            ));
        }

        if ($media == 'Diversity') {
            return $this->forward('NewRoadsMediaFrontendBundle:JobSearch:search', $this->getRequest()->attributes->all(), array(
                'diversity' => 1,
            ));
        }

        if ($media == 'PR Media Relations Communications') {
            return $this->forward('NewRoadsMediaFrontendBundle:JobSearch:search', $this->getRequest()->attributes->all(), array(
                'pr' => 1,
            ));
        }

        //hard-coded list of industry IDs to show for each media.
        $industry_ids = array(
            "Newspapers" => 1,
            "TV" => array(2,3),
            "Magazines" => 4,
            "Online Media" => 5,
            "Public Relations" => array(10,12),
            "Nonprofit" => array(7,8,11,13),
            "Finance" => array(14,11),
            "Internships" => null,
        );

        $position_ids = array(
            "Internships" => 82,
        );

        //for these 2 media types, it is referred to "Public Relations" in the
        //database. But these types have different industry IDs above. So we
        //take the somewhat messy route by having two separate media types that
        //are then reset at this point in the code.
        if ($media == "Trade Publications Newsletters") {
            $media = "Public Relations";
        }

        if (!array_key_exists($media, $industry_ids)) {
            throw new NotFoundHttpException(sprintf('Job Listing for "%s" not found.', $media));
        }

        return $this->forward('NewRoadsMediaFrontendBundle:JobSearch:search', $this->getRequest()->attributes->all(), array(
            'industry' => $industry_ids[$media],
            'position' => array_key_exists($media, $position_ids) ? $position_ids[$media] : null,
        ));
    }

    /**
     * @Route("/job-listings/industry/{slug}", name="IndustryJobPosts")
     */
    public function industrySearchAction($slug)
    {
        /** @var Industry $industry */
        $industry = $this->get('journalismjobs.manager.industry')->findOneBySlug($slug);
        if (!$industry) {
            throw new NotFoundHttpException(sprintf('Industry slug %s not found.', $slug));
        }

        return $this->forward('NewRoadsMediaFrontendBundle:JobSearch:search', $this->getRequest()->attributes->all(), array(
            'industry' => $industry->getId(),
        ));
    }

    public function returnApiJobs($jobs)
    {
        foreach ($jobs as $job) {
            if ($job->getIsExternal()){
                $link = $job->getWebsite();
            }
            else {
                $link = $this->generateUrl('ViewJobPost', array('jobId' => $job->getId()), true);
            }
            if ($job->getCompany()){
                $company = $job->getCompany();
            }
            else {
                $company = "Not Specified";
            }
            if ($job->getJobCity() || $job->getLocation()){
                $city = ($job->getJobCity() ? $job->getJobCity() . ', ' : '')
                    . ($job->getLocation() ? $job->getLocation()->getLocationDescription() :  '');
            }
            else {
                $city = '';
            }
            $jobsArray[] = array(
                'title' => $job->getTitleOfPositionOpen(),
                'city' => $city,
                'createDate' => $job->getCreateDate()->format('Y-m-d H:i:s'),
                'listedToday' => $job->wasPostedToday(),
                'link' => $link,
                'company' => $company,
                );
        }
        return new JsonResponse($jobsArray);
    }

    /**
     * @Route("/job-listings", name="JobPosts", defaults={"isRss": false, "isApi": false})
     * @Route("/job-listings.rss", name="RssJobPosts", defaults={"isRss": true, "isApi": false})
     * @Route("/api/job-listings/{maxResults}", name="JobPostsApi", defaults={"isRss": false, "isApi": true, "maxResults": 6})
     * @Template()
     */
    public function searchAction($maxResults = 6)
    {
        $request = $this->getRequest();
        $isRss = $request->get('isRss') === true;
        $isApi = $request->get('isApi') === true;
        
        if ($request->get('job_posts_search')) {
            $route = $isRss ? 'RssJobPosts' : 'JobPosts';

            return $this->redirect($this->generateUrl($route, $request->get('job_posts_search')));
        }
        $jobManager = $this->get('journalismjobs.manager.job');

        $searchForm = $this->get('form.factory')->createNamed('', 'job_posts_search', null, array(
            'action' => $this->generateUrl('JobPosts'),
            'method' => 'GET',
        ));
        $searchForm->handleRequest($request);

        $page = $request->get('page', 1);
        if ($page < 1) {
            $page = 1;
        }
        // $max = $this->get('journalismjobs.frontend.device')->isMobile() ? 25 : 50;
        $max = 20;

        if ($isApi){
            $max = $maxResults;
        }
        $start = $max * ($page - 1);
        $end = $max * $page;
        $params = $request->query->all() ?: array();
        $searchOptions =  array_merge($params, $searchForm->getData() ?: array());

        $searchOptions['maxResults']          = $max;
        $searchOptions['firstResult']         = $start;

        $title = null;

        // if industry is a comma separated list
        if (isset($searchOptions['industry']) and is_string($searchOptions['industry']) and strpos($searchOptions['industry'], ',') !== false){
            $params['industry'] = $searchOptions['industry'] = explode(',', $searchOptions['industry']);
        }

        $debug = false;

        $count = $jobManager->searchJobsCount($searchOptions);

        // $searchOptions['excludedEmployerIDs'] = [1614835, 654158];
        /** @var Job[] $jobs */
        $jobs = $jobManager->searchJobs($searchOptions, $debug);

        if ($request->get('test')){
            // file_put_contents('/tmp/dump.txt', var_export($searchOptions, true));
            // echo count($jobs);
            // echo '<!--';
            // \Doctrine\Common\Util\Debug::dump($searchOptions, 4);
            // echo '-->';
            // $debug = true;
        }

        if ($isRss) {
            $response = $this->render('NewRoadsMediaFrontendBundle:JobSearch:rss.xml.twig', array(
                'jobs' => $jobs,
            ));
            $response->headers->add(array(
                'Content-Type' => 'text/xml',
            ));

            return $response;
        }
        elseif ($isApi) {
            return $this->returnApiJobs($jobs);
        }

        /*if(getenv('HTTP_APP') == 'teachingjobs.com') {

            //Get Location//Get Location
            if( $request->get('location') ) {
                $location = $this->getDoctrine()
                ->getRepository(Location::class)
                ->find($request->get('location'));
            } else {
                $location = null;
            }

            $subject = str_replace(' ', ' +', $request->get('keywords'));

            $client = new \GuzzleHttp\Client();
            if($location) {
                //https://api.ziprecruiter.com/jobs/v1?search=Math Teacher&location=GA&days_ago=&jobs_per_page=10&page=5&api_key=z3enj2ak8y3ghk5y6j9hysdt2qfxud4n
                $url = 'https://api.ziprecruiter.com/jobs/v1?search='.$subject.'&location='.$location->getLocationAbbreviation().'&days_ago=1&jobs_per_page=10&page='.$page.'&api_key=z3enj2ak8y3ghk5y6j9hysdt2qfxud4n';
            } else {
                $url = 'https://api.ziprecruiter.com/jobs/v1?search='.$subject.'&location=&days_ago=1&jobs_per_page=10&page='.$page.'&api_key=z3enj2ak8y3ghk5y6j9hysdt2qfxud4n';
            }
            $res = $client->get($url);
            $zipRecruiterData = json_decode((string) $res->getBody(), false);
            $count = $count + $zipRecruiterData->total_jobs;
        }*/

        $pageCount = ceil($count / $max);
        $start++;
        $end = min($end, $count);
        if ($page <= 6) {
            $pageStart = 1;
            $pageEnd = min(10, $pageCount);
        } else {
            $pageStart = $pageCount - $page > 4 ? $page - 5 : $pageCount - 9;
            $pageEnd = min($page + 4, $pageCount);
        }
        if ($pageStart < 1) {
            $pageStart = 1;
        }
        
        // clear search options if there are no results found so that left navigation will have proper links
        if ($count == 0 && $zipRecruiterData->total_jobs == 0) {
            $searchOptions = array();
            $params = array();
        }

        $industryCounts   = $jobManager->getIndustryCounts($searchOptions);
        $locationCounts   = $jobManager->getLocationCounts($searchOptions);
        $jobTypeCounts    = $jobManager->getJobTypeCounts($searchOptions);
        $datePostedCounts = $jobManager->getDatePostedCounts($searchOptions);
        $featuredJobs     = $jobManager->getFeaturedJobs();

        
        //echo print_r((string) $res->getBody(), true);
        //die('WTG');

        // $industryId = $request->get('industry');
        // $industryId = is_array($industryId) ? array_shift($industryId) : $industryId;

        // if ($industryId) {
        //    /** @var Industry $industry */
        //     $industry = $this->get('journalismjobs.manager.industry')->find($industryId);
        //     if ($industry) {
        //         $title = $industry->getTitle();
        //     }
        // }

        if (!$title && $request->get('pr') == 1) {
            $title = 'Industry: Public Relations / Communications';
        }

        $resumeManager = $this->get('journalismjobs.manager.resume');
        $accountForm = $this->createForm(new CreateJobSeekerAccountFormType($resumeManager), null, array(
            'attr' => array('novalidate' => 'novalidate'),
            'method' => 'POST',
            'action' => $this->generateUrl('JobPosts'),
        ));

        $accountForm->handleRequest($this->getRequest());


        if ($accountForm->isValid()) {
            $resume = $resumeManager->create();
            $resume->setEmail($accountForm->get('email')->getData());
            $resume->setPassword($accountForm->get('password')->getData());
            $resume->setName($accountForm->get('name')->getData());
            $resume->setDateEntered(new \DateTime('now'));
            $resumeManager->save($resume);
            $this->get('journalismjobs.frontend.user')->loginUser($resume, 'job_seekers');

            return $this->redirect($this->generateUrl('JobNotification'));
        }
        
        return array(
            'searchBox' => $searchForm->createView(),
            'start' => $start,
            'end' => $end,
            'count' => $count,
            'page' => $page,
            'pageStart' => $pageStart,
            'pageEnd' => $pageEnd,
            'jobs' => $jobs,
            //'zipRecruiterData' => (getenv('HTTP_APP') == 'teachingjobs.com') ? $zipRecruiterData->jobs : null,
            'pageCount' => $pageCount,
            'hasNextButton' => $page < $pageCount,
            'hasPreviousButton' => $page > 1,
            'featuredJobs' => $featuredJobs,
            'params' => $params,
            'industryCounts' => $industryCounts,
            'locationCounts' => $locationCounts,
            'jobTypeCounts' => $jobTypeCounts,
            'datePostedCounts' => $datePostedCounts,
            'jobManager' => $jobManager,
            'title' => $title,
            'accountForm' => $accountForm->createView(),
            'formErrors' => count($accountForm->getErrors())
        );
    }

    /**
     * @Route("/advanced-search", name="AdvancedSearch")
     * @Template()
     */
    public function advancedSearchAction()
    {
        $searchForm = $this->get('form.factory')->createNamed('', 'job_posts_search', null, array(
            'action' => $this->generateUrl('JobPosts'),
            'method' => 'GET',
        ));

        return array(
            'form' => $searchForm->createView(),
        );
    }
}