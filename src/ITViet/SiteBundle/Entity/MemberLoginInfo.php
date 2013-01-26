<?php

namespace ITViet\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */

class MemberLoginInfo
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastLogin;
    public function getLastLogin() {
        return $this->lastLogin;
    }
    public function setLastLogin($val) {
        $this->lastLogin = $val;
    }

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $currentLogin;
    public function getCurrentLogin() {
        return $this->currentLogin;
    }
    public function setCurrentLogin($val) {
        $this->currentLogin = $val;
    }

    /**
     * @ORM\Column(type="integer")
     */
    protected $count;
    public function getCount() {
        return $this->count;
    }
    public function setCount($val) {
        $this->count = $val;
    }


    /**
     * @ORM\PrePersist
     */
    public function setLastLoginValue() {
        $this->lastLogin = new \DateTime();
    }

    public function increaseCount() {
        $this->count++;
    }
}
