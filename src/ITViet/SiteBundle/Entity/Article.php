<?php

namespace ITViet\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ITViet\SiteBundle\Model\UrlSlugger;
use ITViet\SiteBundle\Model\CharConverter;

/**
 * ITViet\SiteBundle\Entity\Article
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="ITViet\SiteBundle\Repository\ArticleRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Article
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string $urlPart
     *
     * @ORM\Column(name="urlPart", type="string", length=255, unique=true)
     */
    private $urlPart;

    /**
     * @var integer $sort
     *
     * @ORM\Column(name="sort", type="integer")
     */
    private $sort = 0;

    /**
     * @var string $image
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @var boolean $isFeatured
     *
     * @ORM\Column(name="isFeatured", type="boolean")
     */
    private $isFeatured = false;

    /**
     * @var boolean $isActive
     *
     * @ORM\Column(name="isActive", type="boolean")
     */
    private $isActive = true;

    /**
     * @var datetime $postedAt
     *
     * @ORM\Column(name="postedAt", type="datetime")
     */
    private $postedAt;

    /**
     * @var datetime $modifiedAt
     *
     * @ORM\Column(name="modifiedAt", type="datetime", nullable=true)
     */
    private $modifiedAt;

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
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set urlPart
     *
     * @param string $urlPart
     */
    public function setUrlPart($urlPart)
    {
        $this->urlPart = $urlPart;
    }

    /**
     * Get urlPart
     *
     * @return string 
     */
    public function getUrlPart()
    {
        return $this->urlPart;
    }

    /**
     * Set sort
     *
     * @param integer $sort
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
    }

    /**
     * Get sort
     *
     * @return integer 
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Set image
     *
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
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
     * Set isFeatured
     *
     * @param boolean $isFeatured
     */
    public function setIsFeatured($isFeatured)
    {
        $this->isFeatured = $isFeatured;
    }

    /**
     * Get isFeatured
     *
     * @return boolean 
     */
    public function getIsFeatured()
    {
        return $this->isFeatured;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set postedAt
     *
     * @param datetime $postedAt
     */
    public function setPostedAt($postedAt)
    {
        $this->postedAt = $postedAt;
    }

    /**
     * Get postedAt
     *
     * @return datetime 
     */
    public function getPostedAt()
    {
        return $this->postedAt;
    }

    /**
     * Set modifiedAt
     *
     * @param datetime $modifiedAt
     */
    public function setModifiedAt($modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;
    }

    /**
     * Get modifiedAt
     *
     * @return datetime 
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    /**
     * @ORM\PrePersist
     */
    public function setPostedAtValue() {
        if (! $this->getPostedAt()) {
            $this->postedAt = new \DateTime();
        }
    }

    /**
     * @ORM\PreUpdate
     */
    public function setModifiedAtValue() {
        $this->modifiedAt = new \DateTime();
    }

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="articles")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    protected $category;
    public function getCategory() {
        return $this->category;
    }
    public function setCategory($category) {
        $this->category = $category;
    }

    /**
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="articles")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id")
     */
    protected $member;
    public function getMember() {
        return $this->member;
    }
    public function setMember($member) {
        $this->member = $member;
    }

    /**
     * @ORM\Column(name="content", type="text")
     */
    private $content;
    public function getContent() {
        return $this->content;
    }
    public function setContent($content) {
        $this->content = $content;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setUrlPathValue() {
        //TODO using listener instead of
        $charConverter = new CharConverter();
        $urlSlugger = new UrlSlugger($charConverter);
        $slugParams = array('toascii' => true, 'tolower' => true);
        $this->urlPart = $urlSlugger->slug($this->title, $slugParams);
    }

    /**
     * @ORM\Column(name="isDeleted", type="boolean")
     */
    private $isDeleted = false;
    public function getIsDeleted() {
        return $this->isDeleted;
    }
    public function setIsDeleted($val) {
        $this->isDeleted = $val;
    }
}
