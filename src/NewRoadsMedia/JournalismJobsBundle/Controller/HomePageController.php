<?php

namespace NewRoadsMedia\JournalismJobsBundle\Controller;

use NewRoadsMedia\FrontendBundle\Controller\HomePageController as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

class HomePageController extends BaseController
{
    /**
     * @Route("/media_news", name="MediaNews")
     */
    public function mediaNewsAction()
    {
        return $this->forward('NewRoadsMediaFrontendBundle:HomePage:newsArticles', array_merge(
            $this->getRequest()->attributes->all(),
            $this->getRequest()->query->all()
        ));
    }

    /**
     * @Route("/magazine_links", name="MagazineLinks")
     * @Template
     */
    public function magazineLinksAction()
    {
        return array();
    }

    /**
     * @Route("/tv_links", name="TvLinks")
     * @Template
     */
    public function tvLinksAction()
    {
        return array();
    }

    /**
     * @Route("/tv_abc_links", name="TvLinksAbc")
     * @Template
     */
    public function tvAbcLinksAction()
    {
        return array();
    }

    /**
     * @Route("/tv_cbs_links", name="TvLinksCbs")
     * @Template
     */
    public function tvCbsLinksAction()
    {
        return array();
    }

    /**
     * @Route("/tv_fox_links", name="TvLinksFox")
     * @Template
     */
    public function tvFoxLinksAction()
    {
        return array();
    }

    /**
     * @Route("/tv_independent_links", name="TvLinksIndependent")
     * @Template
     */
    public function tvIndependentLinksAction()
    {
        return array();
    }

    /**
     * @Route("/tv_nbc_links", name="TvLinksNbc")
     * @Template
     */
    public function tvNbcLinksAction()
    {
        return array();
    }

    /**
     * @Route("/radio_links", name="RadioLinks")
     * @Template
     */
    public function radioLinksAction()
    {
        return array();
    }

    /**
     * @Route("/college_papers", name="CollegePapers")
     * @Template
     */
    public function collegePapersAction()
    {
        return array();
    }

    /**
     * @Route("/alternative_newspapers", name="AlternativeNewspapers")
     * @Template
     */
    public function altNewspaperLinksAction()
    {
        return array();
    }

    /**
     * @Route("/newspaper_links", name="NewspaperLinks")
     * @Template
     */
    public function newspaperLinksAction()
    {
        return array();
    }

    /**
     * @Route("/fellowship_listings", name="Fellowship")
     * @Template
     */
    public function fellowshipListingsAction()
    {
        return array();
    }

    /**
     * @Route("/awards_contests", name="Awards")
     * @Template
     */
    public function awardsAction()
    {
        return array();
    }

    /**
     * @Route("/ownership", name="Ownership")
     * @Template
     */
    public function ownershipAction()
    {
        return array();
    }

    /**
     * @Route("/about-journalism-jobs", name="AboutUs")
     * @Template
     */
    public function aboutUsAction()
    {
        return array();
    }

    /**
     * @Route("/training", name="Training")
     * @Template
     */
    public function trainingAction()
    {
        return array();
    }

    /**
     * @Route("/career-advice", name="CareerAdvice")
     * @Template
     */
    public function careerAdviceAction()
    {
        return array();
    }

    /**
     * @Route("/ethics", name="Ethics")
     * @Template
     */
    public function ethicsAction()
    {
        return array();
    }

    /**
     * @Route("/journalism-schools", name="JournalismSchools")
     * @Template
     */
    public function journalismSchoolsAction()
    {
        return array();
    }

    /**
     * @Route("/research", name="Research")
     * @Template
     */
    public function researchAction()
    {
        return array();
    }

    /**
     * @Route("/media-resources", name="Resources")
     * @Template
     */
    public function resourcesAction()
    {
        return array();
    }

    /**
     * @Route("/testimonials", name="Testimonials")
     * @Template
     */
    public function testimonialsAction()
    {
        return array();
    }

    /**
     * @Route("/negotiating_salary_offers", name="Negotiating")
     * @Template
     */
    public function negotiatingAction()
    {
        return array();
    }

    /**
     * @Route("/ten_indispensable_websites_for_journalists", name="IndispensableWebsites")
     * @Template
     */
    public function indispensableWebsitesAction()
    {
        return array();
    }

    /**
     * @Route("/switching_from_journalism_to_PR", name="JournalismToPr")
     * @Template
     */
    public function journalismToPrAction()
    {
        return array();
    }

    /**
     * @Route("/quitting_your_job_to_become_a_freelancer", name="QuitJobToBecomeFreelancer")
     * @Template
     */
    public function quitJobToBecomeFreelancerAction()
    {
        return array();
    }

    /**
     * @Route("/graduate_degree_needed_for_journalism", name="GraduateDegreeJournalism")
     * @Template
     */
    public function graduateDegreeJournalismAction()
    {
        return array();
    }

    /**
     * @Route("/using_social_media_to_find_a_job", name="UsingSocialMediaToFindAJob")
     * @Template
     */
    public function usingSocialMediaToFindAJobAction()
    {
        return array();
    }

    /**
     * @Route("/applying_for_jobs_online", name="ApplyingForJobsOnline")
     * @Template
     */
    public function applyingForJobsOnlineAction()
    {
        return array();
    }

    /**
     * @Route("/successful_interview_tips", name="SuccessfulInterviewTips")
     * @Template
     */
    public function successfulInterviewTipsAction()
    {
        return array();
    }

    /**
     * @Route("/reasons_for_teaching", name="ReasonsForTeaching")
     * @Template
     */
    public function reasonsForTeachingAction()
    {
        return array();
    }

    /**
     * @Route("/tax_tips_for_freelance_writers", name="TaxTipsForFreelanceWriters")
     * @Template
     */
    public function taxTipsForFreelanceWritersAction()
    {
        return array();
    }

    /**
     * @Route("/tips_for_doing_a_video_interview", name="VideoInterviewTips")
     * @Template
     */
    public function videoInterviewTipsAction()
    {
        return array();
    }
}
