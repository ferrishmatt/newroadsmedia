<?php

namespace NewRoadsMedia\FrontendBundle\Controller;

use NewRoadsMedia\FrontendBundle\Form\Type\JobSearchFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

use GuzzleHttp\Client;

class HomePageController extends Controller
{
    /**
     * @Route("/index.php", name="Index")
     * @Template
     */
    public function indexAction()
    {   
        $searchForm = $this->createForm(new JobSearchFormType(), null, array(
            'simple' => true,
            'method' => 'GET',
            'action' => $this->generateUrl('JobPosts')
        ));

        /*$searchForm = $this->createFormBuilder()
        ->add('keywords', Symfony\Component\Form\Extension\Core\Type\TextType::class, array(
            'label' => 'Enter Keywords',
            'required' => false,
        ))
        ->add('location', 'location_with_count', array(
            'empty_value' => 'Select All',
            'label' => 'Select Location',
            'required' => false,
        ))->getForm();*/
        $totalCount = $this->get('journalismjobs.manager.job')->getJobCount();

        $searchOptions = array();
        $searchOptions['maxResults'] = 60;
            $searchOptions['firstResult'] = 0;

        $jobManager = $this->get('journalismjobs.manager.job');
        $locationCounts = $jobManager->getLocationCounts($searchOptions);
        usort($locationCounts, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });
        $locationCounts = array_filter($locationCounts, function($locationCount) {
            return $locationCount['name'] != 'Africa' && $locationCount['name'] != 'All Locations' && $locationCount['name'] != 'Other';
        });
        /*if(getenv('HTTP_APP') == 'teachingjobs.com') {
            $client = new \GuzzleHttp\Client();
            $url = 'https://api.ziprecruiter.com/jobs/v1?search=teacher&location=&days_ago=1&jobs_per_page=10&page=&api_key=z3enj2ak8y3ghk5y6j9hysdt2qfxud4n';
            $res = $client->get($url);
            $zipRecruiterData = json_decode((string) $res->getBody(), false);
            $totalCount = $totalCount + $zipRecruiterData->total_jobs;
        }*/
        return array(
            'searchForm' => $searchForm->createView(),
            'totalCount' => $totalCount,
	        'locationCounts' => $locationCounts
        );
    }

    /**
     * @Route("/", name="IndexRedirect")
     */
    public function indexRedirectAction()
    {
        return $this->redirect($this->generateUrl('Index'), 301);
    }

    /**
     * @Route("/unsubscribe", name="Unsubscribe")
     */
    public function unsubscribe()
    {
        $email = $this->getRequest()->get('email');

        $em = $this->getDoctrine()->getManager();
        $em
            ->createQuery('DELETE FROM NewRoadsMediaFrontendBundle:Notification n WHERE n.email = :email')
            ->setParameter('email', $email)
            ->execute();

        $this->get('session')->getFlashBag()->add('notice', 'You have been unsubscribed from all job alerts.');

        return $this->redirect($this->generateUrl('Index'));
        // return 'You have been unsubscribed from all job alerts.';
    }

    /**
     * @Route("/job_listings_latest", name="LatestJobPosts")
     * @Template()
     */
    public function latestJobPostsAction()
    {
        $jobs = $this->get('journalismjobs.manager.job')->getLatestJobListings();

        return array(
            'jobs' => $jobs,
        );
    }

    /**
     * @Template()
     */
    public function newsAction()
    {
        $newsManager = $this->get('journalismjobs.manager.news');
        $max = $this->get('journalismjobs.manager.configuration')->get('max_news_articles', 5);

        return array(
            'allNewsArticles' => $newsManager->getLatestNewsArticles('medianews', $max),
        );
    }

    /**
     * @Template()
     */
    public function newsArticlesAction()
    {
        $max = 12;
        $offset = $this->get('journalismjobs.manager.configuration')->get('max_news_articles', 5);
        $page = (int) $this->getRequest()->get('page', 1);
        if ($page < 1) {
            $page = 1;
        }
        $firstResult = ($page - 1) * $max + $offset;
        $articles = $this->get('journalismjobs.manager.news')->getNewsArticles('medianews', $firstResult, $max);
        $jobs = $this->get('journalismjobs.manager.job')->searchJobs(array(
            'maxResults' => 10,
        ));

        return array(
            'articles' => $articles,
            'jobs' => $jobs,
            'page' => $page,
        );
    }

    /**
     * @Template()
     */
    public function featuredJobsAction()
    {
        $featuredJobs = $this->get('journalismjobs.manager.job')->getFrontPageFeaturedJobListings();
        return array(
            'featuredJobs' => $featuredJobs,
            'showAllLink' => true,
        );
    }

    /**
     * @Template()
     */
    public function careerAdviceAction()
    {
        return array();
    }

    /**
     * @Route("/faq_about_employer_folder", name="FaqAboutEmployerFolder")
     * @Template
     */
    public function faqAboutEmployerFolderAction()
    {
        return array();
    }

    /**
     * @Route("/contact-us", name="ContactUs")
     * @Template
     */
    public function contactUsAction()
    {
        $form = $this->createFormBuilder(null, array(
                'method' => 'POST',
                'action' => $this->generateUrl('ContactUs'),
                'attr' => array('novalidate' => 'novalidate'),
            ))
            ->add('email', 'email', array(
                'label' => 'Your Email Address',
                'constraints' => array(
                    new NotBlank(array('message' => 'Please enter the email address to which you would like us to reply.')),
                    new Email(array('message' => 'Please enter a valid email address.')),
                ),
                'required' => true,
            ))
            ->add('subject', 'text', array(
                'label' => 'Subject',
                'required' => true,
                'constraints' => array(
                    new NotBlank(array('message' => 'Please enter a subject.')),
                ),
            ))
            ->add('message', 'textarea', array(
                'label' => 'Message',
                'required' => true,
                'constraints' => array(
                    new NotBlank(array('message' => 'Please enter a message.')),
                    new Length(array(
                        'max' => 2000,
                        'maxMessage' => 'Message cannot be longer than {{ limit }} characters long',
                    )),
                ),
            ))
            ->getForm()
        ;
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            $email = $form->get('email')->getData();
            $subject = $form->get('subject')->getData();
            $body = $form->get('message')->getData();
            $this->get('journalismjobs.frontend.mailer')->sendContactUsEmail($email, $subject, $body);
            $this->get('session')->getFlashBag()->add('notice', 'Email sent. We\'ll get back to you shortly');

            return $this->redirect($this->generateUrl('ContactUs'));
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/privacy_policy", name="PrivacyPolicy")
     * @Template
     */
    public function privacyPolicyAction()
    {
        return array();
    }

    /**
     * @Route("/terms_and_conditions", name="Terms")
     * @Template
     */
    public function termsAction()
    {
        return array();
    }

    /**
     * @Route("/advertising", name="Advertising")
     * @Template
     */
    public function advertisingAction()
    {
        return array();
    }

    /**
     * @Route("/search-resumes", name="SearchResumes")
     * @Template
     */
    public function searchResumesAction()
    {
        return array();
    }

    /**
     * @Route("/salaries", name="Salaries")
     * @Template
     */
    public function salariesAction()
    {
        return array();
    }
}
