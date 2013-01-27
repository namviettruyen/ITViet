<?php

namespace ITViet\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ITViet\SiteBundle\Entity\Category
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Category
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
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string $urlPart
     *
     * @ORM\Column(name="urlPart", type="string", length=255)
     */
    private $urlPart;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var integer $sort
     *
     * @ORM\Column(name="sort", type="integer")
     */
    private $sort = 0;

    /**
     * @var boolean $isActive
     *
     * @ORM\Column(name="isActive", type="boolean")
     */
    private $isActive = true;

    /**
     * @var boolean $isSpecial
     *
     * @ORM\Column(name="isSpecial", type="boolean")
     */
    private $isSpecial = false;


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
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
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
     * Set isSpecial
     *
     * @param boolean $isSpecial
     */
    public function setIsSpecial($isSpecial)
    {
        $this->isSpecial = $isSpecial;
    }

    /**
     * Get isSpecial
     *
     * @return boolean 
     */
    public function getIsSpecial()
    {
        return $this->isSpecial;
    }

    /**
     * @ORM\OneToMany(targetEntity="Article", mappedBy="category")
     */
    protected $articles;
}
