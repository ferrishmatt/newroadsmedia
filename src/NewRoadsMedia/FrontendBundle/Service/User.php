<?php

namespace NewRoadsMedia\FrontendBundle\Service;

use NewRoadsMedia\FrontendBundle\Entity\Resume;
use NewRoadsMedia\FrontendBundle\Manager\ResumeManager;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class User
{
    /** @var EncoderFactoryInterface */
    protected $encoderFactory;

    /** @var EventDispatcher*/
    protected $eventDispatcher;

    /** @var Request */
    protected $request;

    /** @var ResumeManager */
    protected $resumeManager;

    /** @var  SecurityContextInterface */
    protected $securityContext;

    public function __construct($eventDispatcher, $request, $securityContext, $encoderFactory, $resumeManager)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->request = $request;
        $this->securityContext = $securityContext;
        $this->encoderFactory = $encoderFactory;
        $this->resumeManager = $resumeManager;
    }

    public function loginUser(UserInterface $user, $firewall)
    {
        $token = new UsernamePasswordToken($user, null, $firewall, $user->getRoles());
        $this->securityContext->setToken($token);                               //now the user is logged in
        $event = new InteractiveLoginEvent($this->request, $token);
        $this->eventDispatcher->dispatch("security.interactive_login", $event); //now dispatch the login event
    }

    public function getResumeFromUsernameAndPassword($username, $password)
    {
        /** @var Resume $resume */
        $resume = $this->resumeManager->findOneByEmail($username);
        if ($resume) {
            $encoder = $this->encoderFactory->getEncoder($resume);
            if ($encoder->isPasswordValid($resume->getPassword(), $password, $resume->getSalt())) {
                return $resume;
            }
        }

        return null;
    }
}