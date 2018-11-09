<?php

namespace NewRoadsMedia\FrontendBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use NewRoadsMedia\FrontendBundle\Entity\Resume;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\Security\Core\SecurityContextInterface;

class ActivityEventListener
{
    /** @var SecurityContextInterface */
    protected $context;

    /** @var EntityManagerInterface*/
    protected $em;

    public function __construct(SecurityContextInterface $context, EntityManagerInterface $em)
    {
        $this->context = $context;
        $this->em = $em;
    }

    public function onCoreController(FilterControllerEvent $event)
    {
        if (!$this->context->getToken()) {
            return;
        }

        $user = $this->context->getToken()->getUser();
        if (!$user instanceof Resume) {
            return;
        }

        $now = new \DateTime('now');
        if ($user->getLastActivity()) {
            $seconds = $now->getTimestamp() - $user->getLastActivity()->getTimestamp();
            if ($seconds < 86400) {
                return;
            }
        }

        $user->setLastActivity($now);
        $this->em->persist($user);
        $this->em->flush();
    }
}