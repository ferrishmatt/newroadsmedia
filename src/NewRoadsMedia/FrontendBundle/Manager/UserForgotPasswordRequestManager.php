<?php

namespace NewRoadsMedia\FrontendBundle\Manager;

use Doctrine\ORM\EntityManager;
use NewRoadsMedia\FrontendBundle\Doctrine\ObjectManager;
use NewRoadsMedia\FrontendBundle\Entity\UserForgotPasswordRequest;
use NewRoadsMedia\FrontendBundle\Service\Mailer;

/**
 * @method \NewRoadsMedia\FrontendBundle\Entity\UserForgotPasswordRequest create()
 */
class UserForgotPasswordRequestManager extends ObjectManager
{
    /** @var Mailer */
    protected $mailer;

    public function __construct(EntityManager $entityManager, $class, $mailer)
    {
        parent::__construct($entityManager, $class);
        $this->mailer = $mailer;
    }

    public function createUserForgotPassword($email)
    {
        $token = md5(uniqid());
        $userForgotPassword = $this->create();
        $userForgotPassword->setToken($token);
        $userForgotPassword->setEmail($email);
        $userForgotPassword->setTimeCreated(new \DateTime('now'));
        $userForgotPassword->setTimeExpires(new \DateTime('+1 day'));
        $this->save($userForgotPassword);
        $this->mailer->sendForgotPasswordEmail($email, $token);
    }

    /**
     * @param $token
     * @return UserForgotPasswordRequest
     */
    public function getRequest($token)
    {
        /** @var UserForgotPasswordRequest $userForgotPasswordRequest */
        $userForgotPasswordRequest = $this->getRepository()->findOneBy(array(
            'token' => $token,
        ));

        return $userForgotPasswordRequest;
    }
}