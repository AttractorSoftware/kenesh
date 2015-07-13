<?php
namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Deputy
 *
 * @ORM\Table(name="Bill")
 * @ORM\Entity
 */
class Bill
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(name="title", type="string")
     */
    private $title;

    /**
     * @ORM\Column(name="register_number", type="string")
     */
    private $registerNumber;


    /**
     * @ORM\Column(name="register_date", type="date")
     */
    private $registerDate;


    /**
     * @ORM\Column(name="initiator", type="string")
     */
    private $initiator;

    /**
     * @ORM\Column(name="reporter", type="string")
     */
    private $reporter;

    /**
     * @ORM\ManyToOne(targetEntity="BillPhase", inversedBy="bills")
     */
    private $billPhase;

    /**
     * @ORM\OneToMany(targetEntity="Subscription", mappedBy="bill")
     */
    private $subsriptions;

    /**
     * @ORM\OneToMany(targetEntity="Vote", mappedBy="bill")
     */
    private $votes;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subsriptions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->votes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set title
     *
     * @param string $title
     * @return Bill
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set registerNumber
     *
     * @param string $registerNumber
     * @return Bill
     */
    public function setRegisterNumber($registerNumber)
    {
        $this->registerNumber = $registerNumber;

        return $this;
    }

    /**
     * Get registerNumber
     *
     * @return string
     */
    public function getRegisterNumber()
    {
        return $this->registerNumber;
    }

    /**
     * Set registerDate
     *
     * @param \DateTime $registerDate
     * @return Bill
     */
    public function setRegisterDate($registerDate)
    {
        $this->registerDate = $registerDate;

        return $this;
    }

    /**
     * Get registerDate
     *
     * @return \DateTime
     */
    public function getRegisterDate()
    {
        return $this->registerDate;
    }

    /**
     * Set initiator
     *
     * @param string $initiator
     * @return Bill
     */
    public function setInitiator($initiator)
    {
        $this->initiator = $initiator;

        return $this;
    }

    /**
     * Get initiator
     *
     * @return string
     */
    public function getInitiator()
    {
        return $this->initiator;
    }

    /**
     * Set reporter
     *
     * @param string $reporter
     * @return Bill
     */
    public function setReporter($reporter)
    {
        $this->reporter = $reporter;

        return $this;
    }

    /**
     * Get reporter
     *
     * @return string
     */
    public function getReporter()
    {
        return $this->reporter;
    }

    /**
     * Set billPhase
     *
     * @param \AdminBundle\Entity\BillPhase $billPhase
     * @return Bill
     */
    public function setBillPhase(\AdminBundle\Entity\BillPhase $billPhase = null)
    {
        $this->billPhase = $billPhase;

        return $this;
    }

    /**
     * Get billPhase
     *
     * @return \AdminBundle\Entity\BillPhase
     */
    public function getBillPhase()
    {
        return $this->billPhase;
    }

    /**
     * Add subsriptions
     *
     * @param \AdminBundle\Entity\Subscription $subsriptions
     * @return Bill
     */
    public function addSubsription(\AdminBundle\Entity\Subscription $subsriptions)
    {
        $this->subsriptions[] = $subsriptions;

        return $this;
    }

    /**
     * Remove subsriptions
     *
     * @param \AdminBundle\Entity\Subscription $subsriptions
     */
    public function removeSubsription(\AdminBundle\Entity\Subscription $subsriptions)
    {
        $this->subsriptions->removeElement($subsriptions);
    }

    /**
     * Get subsriptions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubsriptions()
    {
        return $this->subsriptions;
    }

    /**
     * Add votes
     *
     * @param \AdminBundle\Entity\Vote $votes
     * @return Bill
     */
    public function addVote(\AdminBundle\Entity\Vote $votes)
    {
        $this->votes[] = $votes;

        return $this;
    }

    /**
     * Remove votes
     *
     * @param \AdminBundle\Entity\Vote $votes
     */
    public function removeVote(\AdminBundle\Entity\Vote $votes)
    {
        $this->votes->removeElement($votes);
    }

    /**
     * Get votes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVotes()
    {
        return $this->votes;
    }
}
