<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as FosUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;
use Doctrine\ORM\Mapping\AttributeOverrides;
use Doctrine\ORM\Mapping\AttributeOverride;

/**
 * User
 *
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends FosUser {

  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  public $id;

  public function __construct(){
      parent::__construct();
  }

  /** @ORM\Column(name="firstname", type="string", length=255, nullable=true) */
  public $firstName;

  /** @ORM\Column(name="lastname", type="string", length=255, nullable=true) */
  public $lastName;

  /** @ORM\Column(name="picture", type="string", length=255, nullable=true) */
  public $picture='about.jpg';

  /** @ORM\Column(name="facebook_id", type="string", length=255, nullable=true) */
  public $facebook_id;

  /** @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true) */
  public $facebook_access_token;

  /** @ORM\Column(name="description", type="text", nullable=true) */
  public $description;

  /** @ORM\Column(name="allergy", type="string", nullable=true) */
  public $allergy;

  /** @ORM\Column(name="specialScheme", type="string", nullable=true) */
  public $specialScheme;

  /** @ORM\Column(name="phoneNumber", type="string", nullable=true) */
  public $phoneNumber;

  /** @ORM\Column(name="adress", type="string", nullable=true) */
  public $adress;

  /** @ORM\Column(name="longAdress", type="string", nullable=true) */
  public $longAdress;

  /** @ORM\Column(name="latAdress", type="string", nullable=true) */
  public $latAdress;

  /** @ORM\Column(name="birthDate", type="date", nullable=true) */
  public $birthDate;

    /**
     * @return mixed
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * @param mixed $birthDate
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;
    }

    /**
     * @return mixed
     */
    public function getSpecialScheme()
    {
        return $this->specialScheme;
    }

    /**
     * @param mixed $specialScheme
     */
    public function setSpecialScheme($specialScheme)
    {
        $this->specialScheme = $specialScheme;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param mixed $picture
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    /**
     * @return mixed
     */
    public function getFacebookId()
    {
        return $this->facebook_id;
    }

    /**
     * @param mixed $facebook_id
     */
    public function setFacebookId($facebook_id)
    {
        $this->facebook_id = $facebook_id;
    }

    /**
     * @return mixed
     */
    public function getFacebookAccessToken()
    {
        return $this->facebook_access_token;
    }

    /**
     * @param mixed $facebook_access_token
     */
    public function setFacebookAccessToken($facebook_access_token)
    {
        $this->facebook_access_token = $facebook_access_token;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getAllergy()
    {
        return $this->allergy;
    }

    /**
     * @param mixed $allergy
     */
    public function setAllergy($allergy)
    {
        $this->allergy = $allergy;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getAdress()
    {
        return $this->adress;
    }

    /**
     * @param mixed $adress
     */
    public function setAdress($adress)
    {
        $this->adress = $adress;
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param mixed $phoneNumber
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return mixed
     */
    public function getLongAdress()
    {
        return $this->longAdress;
    }

    /**
     * @param mixed $longAdress
     */
    public function setLongAdress($longAdress)
    {
        $this->longAdress = $longAdress;
    }

    /**
     * @return mixed
     */
    public function getLatAdress()
    {
        return $this->latAdress;
    }

    /**
     * @param mixed $latAdress
     */
    public function setLatAdress($latAdress)
    {
        $this->latAdress = $latAdress;
    }

}
