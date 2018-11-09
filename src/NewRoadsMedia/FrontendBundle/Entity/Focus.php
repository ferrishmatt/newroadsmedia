<?php

namespace NewRoadsMedia\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Industry
 *
 * @ORM\Table(name="Focus")
 * @ORM\Entity
 */
class Focus
{
    /**
     * @var integer
     *
     * @ORM\Column(name="FocusID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Focus", type="string", length=100, nullable=false)
     */
    protected $focus;

    /**
     * @var integer
     *
     * @ORM\Column(name="DisplayOrder", type="integer", nullable=true)
     */
    protected $displayOrder;

    /**
     * @var integer
     *
     * @ORM\Column(name="Priority", type="integer", nullable=true)
     */
    protected $priority;

    /**
     * @var string
     *
     * @ORM\Column(name="Keywords", type="text", nullable=true)
     */
    protected $keywords;

    public function __toString()
    {
        return $this->getFocus() ?: '';
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
     * Set focus
     *
     * @param string $focus
     * @return Focus
     */
    public function setFocus($focus)
    {
        $this->focus = $focus;

        return $this;
    }

    /**
     * Get focus
     *
     * @return string 
     */
    public function getFocus()
    {
        return $this->focus;
    }

    /**
     * Set displayOrder
     *
     * @param integer $displayOrder
     * @return Focus
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

    /**
     * Set priority
     *
     * @param integer $priority
     * @return Focus
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return integer 
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @return string
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * @param string $keywords
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }

    public function getKeywordsArray()
    {
        $keywords = explode(',', $this->getKeywords());
        foreach ($keywords as &$keyword) {
            $keyword = trim($keyword);
        }

        return $keywords;
    }
}
