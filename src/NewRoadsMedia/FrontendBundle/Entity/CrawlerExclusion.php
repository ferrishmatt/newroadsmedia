<?php

namespace NewRoadsMedia\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="CrawlerExclusions", uniqueConstraints={@ORM\UniqueConstraint(columns={"Exclusion"})})
 * @ORM\Entity
 * @UniqueEntity("exclusion")
 */
class CrawlerExclusion
{
    /**
     * @var integer
     *
     * @ORM\Column(name="CrawlerExclusionID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Exclusion", type="string", length=200, nullable=false)
     */
    protected $exclusion;

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
     * Set exclusion
     *
     * @param string $exclusion
     * @return CrawlerExclusion
     */
    public function setExclusion($exclusion)
    {
        $this->exclusion = $exclusion;

        return $this;
    }

    /**
     * Get exclusion
     *
     * @return string 
     */
    public function getExclusion()
    {
        return $this->exclusion;
    }
}
