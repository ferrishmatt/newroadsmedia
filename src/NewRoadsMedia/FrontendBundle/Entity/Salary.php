<?php

namespace NewRoadsMedia\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Salary
 *
 * @ORM\Table(name="Salary")
 * @ORM\Entity
 */
class Salary
{
    /**
     * @var integer
     *
     * @ORM\Column(name="SalaryID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Salary", type="string", length=100, nullable=true)
     */
    protected $salary;

    /**
     * @var integer
     *
     * @ORM\Column(name="DisplayOrder", type="integer", nullable=true)
     */
    protected $displayOrder;

    public function __toString()
    {
        return $this->getSalary() ?: '';
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set salary
     *
     * @param string $salary
     * @return Salary
     */
    public function setSalary($salary)
    {
        $this->salary = $salary;

        return $this;
    }

    /**
     * Get salary
     *
     * @return string
     */
    public function getSalary()
    {
        return $this->salary;
    }

    /**
     * Set displayOrder
     *
     * @param integer $displayOrder
     * @return Salary
     */
    public function setDisplayOrder($displayOrder)
    {
        $this->displayOrder = $displayOrder;

        return $this;
    }

    /**
     * Get displayOrder
     *
     * @return integer
     */
    public function getDisplayOrder()
    {
        return $this->displayOrder;
    }
}