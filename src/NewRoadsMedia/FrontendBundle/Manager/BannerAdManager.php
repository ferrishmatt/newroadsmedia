<?php

namespace NewRoadsMedia\FrontendBundle\Manager;

use Doctrine\ORM\EntityManager;
use NewRoadsMedia\FrontendBundle\Doctrine\ObjectManager;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @method \NewRoadsMedia\FrontendBundle\Entity\BannerAd find()
 */
class BannerAdManager extends ObjectManager
{
    /** @var SessionInterface */
    protected $session;

    public function __construct(EntityManager $em, $class, SessionInterface $session)
    {
        parent::__construct($em, $class);
        $this->session = $session;
    }

    public function getSessionId($id)
    {
        $key = $this->getKey($id);
        if ($this->session->has($key)) {
            return $this->session->get($key);
        }

        return $this->generateNewSessionId($id);
    }

    public function generateNewSessionId($id)
    {
        $sessionId = uniqid();
        $this->session->set($this->getKey($id), $sessionId);

        return $sessionId;
    }

    public function isMatch($id, $sid)
    {
        $realSid = $this->getSessionId($id);

        return $sid == $realSid;
    }

    public function increment($id)
    {
        $affectedRows = $this->getManager()
            ->createQuery('UPDATE ' . $this->getClass() . ' b SET b.counter = b.counter + 1 WHERE b.id = :bannerId AND b.isRawCode = FALSE')
            ->setParameter('bannerId', $id)
            ->execute()
        ;
        $this->generateNewSessionId($id);

        return $affectedRows;
    }

    public function incrementOnMatch($id, $sid)
    {
        if ($this->isMatch($id, $sid)) {
            $this->increment($id);

            return true;
        }

        return false;
    }

    public function reset($id)
    {
        return $this->getManager()
            ->createQuery('UPDATE ' . $this->getClass() . ' b SET b.counter = 0 WHERE b.id = :bannerId')
            ->setParameter('bannerId', $id)
            ->execute()
        ;
    }

    protected function getKey($id)
    {
        return 'banner-session-id-' . $id;
    }
}