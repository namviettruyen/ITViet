<?php

namespace ITViet\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ITViet\SiteBundle\Entity\BaseLog
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="ITViet\SiteBundle\Repository\BaseLogRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class BaseLog
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
     * @var datetime $timestamp
     *
     * @ORM\Column(name="timestamp", type="datetime")
     */
    private $timestamp;

    /**
     * @var integer $ipAddr
     *
     * @ORM\Column(name="ipAddr", type="string", length=255)
     */
    private $ipAddr;

    /**
     * @ORM\ManyToOne(targetEntity="ArticleViewInfo", inversedBy="baseLogs")
     * @ORM\JoinColumn(name="article_view_info_id", referencedColumnName="id")
     */
    private $articleViewInfo;
    public function getArticleViewInfo() {
        return $this->articleViewInfo;
    }
    public function setArticleViewInfo($var) {
        $this->articleViewInfo = $var;
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
     * Set timestamp
     *
     * @param datetime $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * Get timestamp
     *
     * @return datetime 
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set ipAddr
     *
     * @param integer $ipAddr
     */
    public function setIpAddr($ipAddr)
    {
        $this->ipAddr = $ipAddr;
    }

    /**
     * Get ipAddr
     *
     * @return integer 
     */
    public function getIpAddr()
    {
        return $this->ipAddr;
    }

    /**
     * @ORM\PrePersist
     */
    public function setTimestampValue() {
        $this->timestamp = new \DateTime();
    }
}
