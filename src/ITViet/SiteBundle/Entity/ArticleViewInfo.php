<?php

namespace ITViet\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ITViet\SiteBundle\Entity\ArticleViewInfo
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="ITViet\SiteBundle\Repository\ArticleViewInfoRepository")
 */
class ArticleViewInfo
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
     * @var integer $count
     *
     * @ORM\Column(name="count", type="integer")
     */
    private $count = 0;


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
     * Set count
     *
     * @param integer $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     * Get count
     *
     * @return integer 
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @ORM\OneToMany(targetEntity="BaseLog", mappedBy="articleViewInfo")
     */
    private $baseLogs;

}
