<?php

namespace NewRoadsMedia\FrontendBundle\Service;

use SunCat\MobileDetectBundle\DeviceDetector\MobileDetector;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Device
{
    /** @var MobileDetector */
    protected $mobileDetector;

    /** @var SessionInterface */
    protected $session;

    public function __construct(MobileDetector $mobileDetector, SessionInterface $session)
    {
        $this->mobileDetector = $mobileDetector;
        $this->session = $session;
    }

    public function isMobile()
    {
        $isMobile = $this->mobileDetector->isMobile();
        if ($this->session->has('device-redirect')) {
            $isMobile = $this->session->get('device-redirect') == 'mobile';
        }

        return $isMobile;
    }
}