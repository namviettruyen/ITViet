<?php

namespace ITViet\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ITViet\SiteBundle\Model\User as AbstractUser;
use ITViet\SiteBundle\Model\CharConverter;
use ITViet\SiteBundle\Model\Utils as Utils;

/**
 * @ORM\Entity(repositoryClass="ITViet\SiteBundle\Repository\MemberRepository")
 * @ORM\Table()
 * @ORM\HasLifecycleCallbacks()
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

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
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

    //return the Gravatar (http://gravatar.com/)
    public function getGravatar( $size = 128 ) {
        $gravatar_id = md5(strtolower(trim($this->email)));
        $gravatar_url = "https://secure.gravatar.com/avatar/{$gravatar_id}?s={$size}";
        return $gravatar_url;
    }

    protected function getListBirthDate() {
        if ($this->birthDate) {
            return explode('/', $this->birthDate->format('Y/n/j'));
        } else {
            return array(null, null, null);
        }
    }

    public function getBirthDay() {
        $dateList = $this->getListBirthDate();
        return $dateList[2];
    }
    public function setBirthDay($day) {
        $dateList = $this->getListBirthDate();
        $date = new \DateTime();
        $this->birthDate = $date->setDate($dateList[0], $dateList[1], intval($day));
    }

    public function getBirthMonth() {
        $dateList = $this->getListBirthDate();
        return $dateList[1];
    }
    public function setBirthMonth($month) {
        $dateList = $this->getListBirthDate();
        $date = new \DateTime();
        $this->birthDate = $date->setDate($dateList[0], intval($month), $dateList[2]);
    }

    public function getBirthYear() {
        $dateList = $this->getListBirthDate();
        return $dateList[0];
    }
    public function setBirthYear($year) {
        $dateList = $this->getListBirthDate();
        $date = new \DateTime();
        $this->birthDate = $date->setDate(intval($year), $dateList[1], $dateList[2]);
    }

    /**
     * @ORM\PrePersist
     */
    public function setSinceValue() {
        if (!$this->getSince()) {
            $this->since = new \DateTime();
        }
    }

    public function setLogInfo() {
        $this->loginInfo->increaseCount();
        $this->loginInfo->setLastLogin($this->loginInfo->getCurrentLogin());
        $this->loginInfo->setCurrentLogin(new \DateTime());
    }

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $profileDescription;
    public function getProfileDescription() {
        return $this->profileDescription;
    }
    public function setProfileDescription($val) {
        $this->profileDescription = $val;
    }

    /**
     * @ORM\OneToMany(targetEntity="Article", mappedBy="member")
     */
    protected $articles;

    public function getFullNameSlug() {
        return Utils::slugify($this->fullNameInAscii);
    }

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="member")
     */
    protected $comments;
}
