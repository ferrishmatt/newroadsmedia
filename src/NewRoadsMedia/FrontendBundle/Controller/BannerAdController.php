<?php

namespace NewRoadsMedia\FrontendBundle\Controller;

use NewRoadsMedia\FrontendBundle\Entity\BannerAd;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BannerAdController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction($id)
    {
        $bannerAdManager = $this->get('journalismjobs.manager.banner_ad');
        $banner = $bannerAdManager->find($id);
        if (!$banner) {
            throw new NotFoundHttpException(sprintf('Banner id %d not found.', $id));
        }

        $sid = $bannerAdManager->getSessionId($id);

        return array(
            'banner' => $banner,
            'sid' => $sid,
        );
    }

    /**
     * @Route("/url/{id}/", name="BannerAd")
     */
    public function urlAction($id)
    {
        $bannerAdManager = $this->get('journalismjobs.manager.banner_ad');
        $banner = $bannerAdManager->find($id);

        if (!$banner) {
            throw new NotFoundHttpException(sprintf('Banner id %d not found.', $id));
        }

        if ($banner->getIsRawCode()) {
            return $this->redirect($this->generateUrl('Index'));
        }

        $bannerAdManager->incrementOnMatch($id, $this->getRequest()->get('sid'));

        $link = $this->getRequest()->get('ad_num', 1) == 1 ? $banner->getLink() : $banner->getLink2();

        return $this->redirect($link);
    }

    /**
     * @Template("@NewRoadsMediaFrontend/BannerAd/topAd.html.twig")
     *
     */
    public function topAdIndexAction()
    {
        return $this->manyBanners(array(
            BannerAd::HOME_PAGE_TOP_LARGE_BANNER,
            BannerAd::HOME_PAGE_TOP_BUTTON,
        ));
    }

    /**
     * @Template("@NewRoadsMediaFrontend/BannerAd/topAd.html.twig")
     */
    public function topAdSubAction()
    {
        return $this->manyBanners(array(
            BannerAd::SUB_PAGE_TOP_LARGE_BANNER,
            BannerAd::SUB_PAGE_TOP_BUTTON,
        ));
    }

    /**
     * @Template("@NewRoadsMediaFrontend/BannerAd/topLeftAd.html.twig")
     */
     public function topLeftAdAction()
     {
         return $this->manyBanners(array(
             BannerAd::SUB_PAGE_TOP_LARGE_BANNER,
             BannerAd::SUB_PAGE_TOP_BUTTON,
         ));
     }

    protected function manyBanners(array $bannerIds)
    {
        $bannerAdManager = $this->get('journalismjobs.manager.banner_ad');
        /** @var BannerAd[] $banners */
        $banners = $bannerAdManager->getBannersById($bannerIds);
        $sids = array();
        foreach ($banners as $banner) {
            $sids[$banner->getId()] = $bannerAdManager->getSessionId($banner->getId());
        }

        return array(
            'banners' => $banners,
            'sids' => $sids,
        );
    }
}