<?php

namespace ITViet\SiteBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Doctrine\ORM\Mapping as ORM;

abstract class User implements AdvancedUserInterface, \Serializable
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    protected $email;

    /**
     * @ORM\Column(type="string")
     */
    protected $hashedPassword;

    /**
     * @ORM\Column(type="string")
     */
    protected $salt;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $enabled;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $confirmationToken;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $emailChangeRequestedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $locked;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $since;

    public function __construct() {
        $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $this->enabled = false;
        $this->locked = false;
    }

    public function getId() {
        return $this->id;
    }

    public function getEmail() {
        return $this->email;
    }
    public function setEmail($email) {
        $this->email = $email;
    }

    public function getUserName() {
        return $this->getEmail();
    }
    public function __toString() {
        return (string) $this->getUsername();
    }

    public function getSalt() {
        return $this->salt;
    }

    public function getConfirmationToken() {
        return $this->confirmationToken;
    }

    public function setEmailChangeRequestedAt(\DateTime $date) {
        $this->emailChangeRequestedAt = $date;
    }
    public function getEmailChangeRequestedAt() {
        return $this->emailChangeRequestedAt;
    }

    public function setSince(\DateTime $date) {
        $this->since = $date;
    }
    public function getSince() {
        return $this->since;
    }

    public function getHashedPassword() {
        return $this->hashedPassword;
    }
    public function getPassword() {
        return $this->getHashedPassword();
    }

    public function isDeleted() {
        return false;
    }

    public function isEnabled() {
        return $this->enabled;
    }

    public function isLocked() {
        return $this->locked;
    }

    public function isAccountNonLocked() {
        return !$this->locked;
    }

    public function isAccountNonExpired() {
        return true;
    }

    public function isCredentialsNonExpired() {
        return true;
    }

    public function generateConfirmationToken() {
        $this->confirmationToken = $this->generateToken();
    }

    protected function generateToken() {
        $bytes = false;
        if (function_exists('openssl_random_pseudo_bytes') && 0 !== stripos(PHP_OS, 'win')) {
            $bytes = openssl_random_pseudo_bytes(32, $strong);

            if (true !== $strong) {
                $bytes = false;
            }
        }

        if (false === $bytes) {
            $bytes = hash('sha256', microtime(), true);
        }

        return base_convert(bin2hex($bytes), 16, 36).time();
    }

    public function eraseCredentials() {}

    public function serialize() {
        $data = array(
          'id' => $this->id,
          'email' => $this->email,
          'hashedPassword' => $this->hashedPassword,
        );
        return serialize($data);
    }

    public function unserialize($str) {
        $data = unserialize($str);
        $this->id = $data['id'];
        $this->email = $data['email'];
        $this->hashedPassword = $data['hashedPassword'];
    }

    /**
     * Implementation of AdvancedUserInterface.
     */
    public function equals(UserInterface $user) {
        if (!$user instanceof User) {
            return false;
        }
        if ($this->getHashedPassword() !== $user->getHashedPassword()) {
            return false;
        }
        if ($this->getSalt() !== $user->getSalt()) {
            return false;
        }
        if ($this->getUserName() !== $user->getUserName()) {
            return false;
        }
        if ($this->isAccountNonLocked() !== $user->isAccountNonLocked()) {
            return false;
        }
        if ($this->isEnabled() !== $user->isEnabled()) {
            return false;
        }

        return true;
    }

    public function setPassword($password) {
        $encode = new MessageDigestPasswordEncoder('sha512', true, 10);
        $hashedPassword = $encode->encodePassword($password, $this->getSalt());
        $this->hashedPassword = $hashedPassword;
    }
    public function setEnabled($enabled){
        $this->enabled = $enabled;
    }

    public function isCorrectPassword($str) {
        $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
        $hashedStr = $encoder->encodePassword($str, $this->getSalt());
        return $this->hashedPassword == $hashedStr ? true : false;
    }

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $emailTemporary;
    public function getEmailTemporary() {
        return $this->emailTemporary;
    }
    public function setEmailTemporary($str) {
        $this->emailTemporary = $str;
    }
}
