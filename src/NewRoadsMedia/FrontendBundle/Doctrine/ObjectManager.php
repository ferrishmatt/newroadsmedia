<?php

namespace NewRoadsMedia\FrontendBundle\Doctrine;

use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class ObjectManager
{
    /** @var string */
    private $class;

    /** @var EntityManager */
    private $entityManager;

    /** @var EntityRepository */
    private $repository;

    public function __call($name, $arguments)
    {
        $repository = $this->getRepository();
        try {
            return call_user_func_array(array($repository, $name), $arguments);
        } catch (\BadMethodCallException $e) {
            throw new \BadMethodCallException(sprintf('Method "%s" was not found in service "%s" or repository "%s".'
                , $name
                , get_class($this)
                , get_class($repository)
            ));
        }
    }

    public function __construct(EntityManager $entityManager, $class)
    {
        $this->class = $class;
        $this->entityManager = $entityManager;
    }

    protected function afterSave($entity) {}

    protected function beforeSave($entity) {}

    public function clear($entityName = null)
    {
        $this->getManager()->clear($entityName);
    }

    public function create()
    {
        $class = $this->getClass();

        return new $class;
    }

    public function delete($entity)
    {
        $this->getManager()->remove($entity);
        $this->getManager()->flush();
    }

    public function find($id, $lockMode = LockMode::NONE, $lockVersion = null)
    {
        return $this->getRepository()->find($id, $lockMode, $lockVersion);
    }

    public function flush()
    {
        $this->getManager()->flush();
    }

    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return EntityManager
     */
    public function getManager()
    {
        return $this->entityManager;
    }

    public function getReference($id)
    {
        return $this->getManager()->getReference($this->class, $id);
    }

    /**
     * @return EntityRepository
     */
    public function getRepository()
    {
        if ($this->repository === null) {
            $this->repository = $this->getManager()->getRepository($this->getClass());
        }

        return $this->repository;
    }

    public function persist($entity)
    {
        $this->getManager()->persist($entity);
    }

    public function remove($entity)
    {
        $this->getManager()->remove($entity);
    }

    public function save($entity)
    {
        $this->beforeSave($entity);
        $this->getManager()->persist($entity);
        $this->getManager()->flush();
        $this->afterSave($entity);
    }
}