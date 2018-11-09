<?php

namespace NewRoadsMedia\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="BannerAd")
 * @ORM\Entity(repositoryClass="NewRoadsMedia\FrontendBundle\Repository\BannerAdRepository")
 */
class BannerAd
{
    const HOME_PAGE_TOP_LARGE_BANNER    = 1;
    const HOME_PAGE_TOP_BUTTON          = 2;
    const HOME_PAGE_MIDDLE_LARGE_BUTTON = 3;
    const HOME_PAGE_BOTTOM_LARGE_BANNER = 4;
    const SUB_PAGE_TOP_LARGE_BANNER     = 5;
    const SUB_PAGE_TOP_BUTTON           = 6;
    const SUB_PAGE_MIDDLE_LARGE_BANNER  = 7;
    const SUB_PAGE_MIDDLE_MEDIUM_BUTTON = 8;
    const SUB_PAGE_TOP_LEFT = 9;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="string", length=400, nullable=false)
     */
    protected $link;

    /**
     * @var string
     *
     * @ORM\Column(name="link2", type="string", length=400, nullable=false)
     */
    protected $link2;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=100, nullable=true)
     */
    protected $image;

    /**
     * @var string
     *
     * @ORM\Column(name="image2", type="string", length=100, nullable=true)
     */
    protected $image2;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_raw_code", type="boolean", nullable=false)
     */
    protected $isRawCode = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_raw_code2", type="boolean", nullable=false)
     */
    protected $isRawCode2 = false;

    /**
     * @var string
     *
     * @ORM\Column(name="raw_code", type="text", nullable=true)
     */
    protected $rawCode;

    /**
     * @var string
     *
     * @ORM\Column(name="raw_code2", type="text", nullable=true)
     */
    protected $rawCode2;

    /**
     * @var int
     *
     * @ORM\Column(name="Counter", type="integer", nullable=false)
     */
    protected $counter;

    /**
     * @var int
     *
     * @ORM\Column(name="ads_num", type="integer", nullable=false)
     */
    protected $adsNum;

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
     * Set name
     *
     * @param string $name
     * @return BannerAd
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set link
     *
     * @param string $link
     * @return BannerAd
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set link2
     *
     * @param string $link
     * @return BannerAd
     */
    public function setLink2($link)
    {
        $this->link2 = $link;

        return $this;
    }

    /**
     * Get link2
     *
     * @return string
     */
    public function getLink2()
    {
        return $this->link2;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return BannerAd
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set image2
     *
     * @param string $image2
     * @return BannerAd
     */
    public function setImage2($image)
    {
        $this->image2 = $image;

        return $this;
    }

    /**
     * Get image2
     *
     * @return string
     */
    public function getImage2()
    {
        return $this->image2;
    }

    /**
     * @return boolean
     */
    public function getIsRawCode()
    {
        return $this->isRawCode;
    }

    /**
     * @param boolean $isRawCode
     */
    public function setIsRawCode($isRawCode)
    {
        $this->isRawCode = $isRawCode;
    }

    /**
     * @return boolean
     */
    public function getIsRawCode2()
    {
        return $this->isRawCode2;
    }

    /**
     * @param boolean $isRawCode
     */
    public function setIsRawCode2($isRawCode)
    {
        $this->isRawCode2 = $isRawCode;
    }

    /**
     * @return string
     */
    public function getRawCode()
    {
        return $this->rawCode;
    }

    /**
     * @param string $rawCode
     */
    public function setRawCode($rawCode)
    {
        $this->rawCode = $rawCode;
    }

    /**
     * @return string
     */
    public function getRawCode2()
    {
        return $this->rawCode2;
    }

    /**
     * @param string $rawCode
     */
    public function setRawCode2($rawCode)
    {
        $this->rawCode2 = $rawCode;
    }

    /**
     * @return int
     */
    public function getCounter()
    {
        return $this->counter;
    }

    /**
     * @param int $counter
     */
    public function setCounter($counter)
    {
        $this->counter = $counter;
    }

    /**
     * @return string
     */
    public function getAdsNum()
    {
        return $this->adsNum;
    }

    /**
     * @param string $rawCode
     */
    public function setAdsNum($adsNum)
    {
        $this->adsNum = $adsNum;
    }
}
