<?php

namespace ITViet\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ITViet\SiteBundle\Entity\Comment
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="ITViet\SiteBundle\Repository\CommentRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Comment
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
     * @var text $content
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

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
     * @ORM\Column(type="integer")
     */
    private $parentId = 0;

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
     * Set content
     *
     * @param text $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get content
     *
     * @return text 
     */
    public function getContent()
    {
        return $this->content;
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
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="comments")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id")
     */
    private $member;

    /**
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="comments")
     * @ORM\JoinColumn(name="article_id", referencedColumnName="id")
     */
    private $article;

    /**
     * Set member
     *
     * @param ITViet\SiteBundle\Entity\Member $member
     */
    public function setMember(\ITViet\SiteBundle\Entity\Member $member)
    {
        $this->member = $member;
    }

    /**
     * Get member
     *
     * @return ITViet\SiteBundle\Entity\Member 
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * Set article
     *
     * @param ITViet\SiteBundle\Entity\Article $article
     */
    public function setArticle(\ITViet\SiteBundle\Entity\Article $article)
    {
        $this->article = $article;
    }

    /**
     * Get article
     *
     * @return ITViet\SiteBundle\Entity\Article 
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @ORM\PrePersist()
     */
    public function updateMetaData() {
        $this->setPostedDateValue();
        $this->article->increaseCount();
    }

    private function setPostedDateValue() {
        if (! $this->postedAt) {
            $this->postedAt = new \DateTime();
        }
    }

    public function getParentId() {
        return $this->parentId;
    }
    public function setParentId($val) {
        $this->parentId = $val;
    }

}
