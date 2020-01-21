<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Meal
 *
 * @ORM\Table(name="meal")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MealRepository")
 */
class Meal
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="text")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="origin", type="string", length=255)
     */
    private $origin;

    /**
     * @var string
     *
     * @ORM\Column(name="adress", type="text")
     */
    private $adress;

    /**
     * @var float
     *
     * @ORM\Column(name="longAdress", type="float")
     */
    private $longAdress;

    /**
     * @var float
     *
     * @ORM\Column(name="latAdress", type="float")
     */
    private $latAdress;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="User")
     */
    private $cooker;

    /**
     * @var float
     *
     * @ORM\Column(name="pricePerPerson", type="float")
     */
    private $pricePerPerson;

    /**
     * @var string
     *
     * @ORM\Column(name="contentMeal", type="text")
     */
    private $contentMeal;

    /**
     * @var array
     *
     * @ORM\Column(name="pictures", type="array")
     */
    private $pictures;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timeEvent", type="datetime")
     */
    private $timeEvent;

    /**
     * @var int
     *
     * @ORM\Column(name="numberPlaces", type="integer")
     */
    private $numberPlaces;

    /**
     * @var boolean
     *
     * @ORM\Column(name="onTheSpot", type="boolean")
     */
    private $onTheSpot = false;


    /**
     * @var boolean
     *
     * @ORM\Column(name="delivery", type="boolean")
     */
    private $delivery = false;


    /**
     * @var boolean
     *
     * @ORM\Column(name="takeAway", type="boolean")
     */
    private $takeAway = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="automaticalyAcceptedRequest", type="boolean")
     */
    private $automaticalyAcceptedRequest;

    /**
     * @var bool
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;

    /**
     * @var int
     *
     * @ORM\Column(name="timesViewed", type="integer")
     */
    private $timesViewed = 0;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Meal
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set origin.
     *
     * @param string $origin
     *
     * @return Meal
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;

        return $this;
    }

    /**
     * Get origin.
     *
     * @return string
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * Set adress.
     *
     * @param string $adress
     *
     * @return Meal
     */
    public function setAdress($adress)
    {
        $this->adress = $adress;

        return $this;
    }

    /**
     * Get adress.
     *
     * @return string
     */
    public function getAdress()
    {
        return $this->adress;
    }

    /**
     * Set longAdress.
     *
     * @param float $longAdress
     *
     * @return Meal
     */
    public function setLongAdress($longAdress)
    {
        $this->longAdress = $longAdress;

        return $this;
    }

    /**
     * Get longAdress.
     *
     * @return float
     */
    public function getLongAdress()
    {
        return $this->longAdress;
    }

    /**
     * Set latAdress.
     *
     * @param float $latAdress
     *
     * @return Meal
     */
    public function setLatAdress($latAdress)
    {
        $this->latAdress = $latAdress;

        return $this;
    }

    /**
     * Get latAdress.
     *
     * @return float
     */
    public function getLatAdress()
    {
        return $this->latAdress;
    }

    /**
     * Set cooker.
     *
     * @param string $cooker
     *
     * @return Meal
     */
    public function setCooker($cooker)
    {
        $this->cooker = $cooker;

        return $this;
    }

    /**
     * Get cooker.
     *
     * @return string
     */
    public function getCooker()
    {
        return $this->cooker;
    }

    /**
     * Set pricePerPerson.
     *
     * @param float $pricePerPerson
     *
     * @return Meal
     */
    public function setPricePerPerson($pricePerPerson)
    {
        $this->pricePerPerson = $pricePerPerson;

        return $this;
    }

    /**
     * Get pricePerPerson.
     *
     * @return float
     */
    public function getPricePerPerson()
    {
        return $this->pricePerPerson;
    }

    /**
     * Set contentMeal.
     *
     * @param string $contentMeal
     *
     * @return Meal
     */
    public function setContentMeal($contentMeal)
    {
        $this->contentMeal = $contentMeal;

        return $this;
    }

    /**
     * Get contentMeal.
     *
     * @return string
     */
    public function getContentMeal()
    {
        return $this->contentMeal;
    }

    /**
     * Set pictures.
     *
     * @param array $pictures
     *
     * @return Meal
     */
    public function setPictures($pictures)
    {
        $this->pictures = $pictures;

        return $this;
    }

    /**
     * Get pictures.
     *
     * @return array
     */
    public function getPictures()
    {
        return $this->pictures;
    }

    /**
     * Set timeEvent.
     *
     * @param \DateTime $timeEvent
     *
     * @return Meal
     */
    public function setTimeEvent($timeEvent)
    {
        $this->timeEvent = $timeEvent;

        return $this;
    }

    /**
     * Get timeEvent.
     *
     * @return \DateTime
     */
    public function getTimeEvent()
    {
        return $this->timeEvent;
    }

    /**
     * Set numberPlaces.
     *
     * @param int $numberPlaces
     *
     * @return Meal
     */
    public function setNumberPlaces($numberPlaces)
    {
        $this->numberPlaces = $numberPlaces;

        return $this;
    }

    /**
     * Get numberPlaces.
     *
     * @return int
     */
    public function getNumberPlaces()
    {
        return $this->numberPlaces;
    }

    /**
     * @return boolean
     */
    public function getOnTheSpot()
    {
        return $this->onTheSpot;
    }

    /**
     * @param boolean $onTheSpot
     */
    public function setOnTheSpot($onTheSpot)
    {
        $this->onTheSpot = $onTheSpot;
    }

    /**
     * @return boolean
     */
    public function getDelivery()
    {
        return $this->delivery;
    }

    /**
     * @param boolean $delivery
     */
    public function setDelivery($delivery)
    {
        $this->delivery = $delivery;
    }

    /**
     * @return boolean
     */
    public function getTakeAway()
    {
        return $this->takeAway;
    }

    /**
     * @param boolean $takeAway
     */
    public function setTakeAway($takeAway)
    {
        $this->takeAway = $takeAway;
    }



    /**
     * Set automaticalyAcceptedRequest.
     *
     * @param bool $automaticalyAcceptedRequest
     *
     * @return Meal
     */
    public function setAutomaticalyAcceptedRequest($automaticalyAcceptedRequest)
    {
        $this->automaticalyAcceptedRequest = $automaticalyAcceptedRequest;

        return $this;
    }

    /**
     * Get automaticalyAcceptedRequest.
     *
     * @return bool
     */
    public function getAutomaticalyAcceptedRequest()
    {
        return $this->automaticalyAcceptedRequest;
    }

    /**
     * Set enabled.
     *
     * @param bool $enabled
     *
     * @return Meal
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled.
     *
     * @return bool
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set timesViewed.
     *
     * @param int $timesViewed
     *
     * @return Meal
     */
    public function setTimesViewed($timesViewed)
    {
        $this->timesViewed = $timesViewed;

        return $this;
    }

    /**
     * Get timesViewed.
     *
     * @return int
     */
    public function getTimesViewed()
    {
        return $this->timesViewed;
    }
}
