<?php

namespace ITViet\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ITViet\SiteBundle\Model\User as AbstractUser;
use ITViet\SiteBundle\Model\CharConverter;

/**
 * @ORM\Entity(repositoryClass="ITViet\SiteBundle\Repository\MemberRepository")
 * @ORM\Table()
 */

class Member extends AbstractUser
{
    /**
     * @ORM\Column(type="boolean")
     */
    protected $isDeleted = false;

    public function getIsDeleted() {
        return $this->isDeleted;
    }
    public function setIsDeleted($val) {
        $this->isDeleted = $val;
    }

    /**
     * @ORM\OneToOne(targetEntity="MemberLoginInfo", cascade={"all"}, fetch="LAZY")
     * @ORM\JoinColumn(name="member_login_info_id", referencedColumnName="id")
     */
    protected $loginInfo;
    public function getLoginInfo() {
        return $this->loginInfo;
    }
    public function setLoginInfo($val) {
        $this->loginInfo = $val;
    }

    /**
     * @ORM\Column(type="string")
     */
    protected $fullName;
    public function getFullName() {
        return $this->fullName;
    }
    public function setFullName($val) {
        $this->fullName = $val;
    }

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $fullNameInAscii;
    public function getFullNameInAscii() {
        return $this->fullNameInAscii;
    }
    public function setFullNameInAscii($val) {
        $this->fullNameInAscii = $val;
    }

    /**
     * @ORM\Column(type="string", columnDefinition="CHAR(1) NOT NULL")
     */
    protected $gender;
    public function getGender() {
        return $this->gender;
    }
    public function setGender($val) {
        $this->gender = $val;
    }

    /**
     * @ORM\Column(type="date")
     */
    protected $birthDate;
    public function getBirthDate() {
        return $this->birthDate;
    }
    public function setBirthDate($val) {
        $this->birthDate = $val;
    }

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $address;
    public function getAddress() {
        return $this->address;
    }
    public function setAddress($val) {
        $this->address = $val;
    }

    //virtual
    //public $upImage;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $image;
    public function getImage() {
        return $this->image;
    }
    public function setImage($val) {
        $this->image = $val;
    }

    //TODO ONE TO MANY

    /**
     * ORM\Column(type="boolean")
     */
    protected $isSpecial = false;
    public function getIsSpecial() {
        return $this->isSpecial;
    }
    public function setIsSpecial($val) {
        $this->isSpecial = $val;
    }

    public function getRoles() {
        return array('ROLE_MEMBER');
    }

    public function getId() {
        return parent::getId();
    }

    public function updateMetaInfo() {
        $this->fullNameInAscii = $this->getSearchableInAscii($this->fullName);
    }
    private function getSearchableInAscii($str) {
        $str = str_replace("\n", ' ', $str);
        $str = preg_replace('/[\s\n]{2,}/', ' ', $str);
        $str = trim($str);

        $searchableInVn = strtolower($str);
        $charConv = new CharConverter();
        return $charConv->toPlainLatin($searchableInVn);
    }
}
