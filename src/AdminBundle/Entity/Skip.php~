<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="Skip")
 * @ORM\Entity
 */
class Skip
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Deputy", inversedBy="skips")
     */
    private $deputy;

    /**
     * @ORM\Column(name="reason", type="string")
     */
    private $reason;

    /**
     * @ORM\ManyToOne(targetEntity="Deputy", inversedBy="voteSubstitute")
     */
    private $votingDeputy;

    /**
     * @ORM\Column(name="start_date", type="date")
     */
    private $startDate;


    /**
     * @ORM\Column(name="end_date", type="date")
     */
    private $end_date;

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
     * Set reason
     *
     * @param string $reason
     * @return Skip
     */
    public function setReason($reason)
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Get reason
     *
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Set deputy
     *
     * @param \AdminBundle\Entity\Deputy $deputy
     * @return Skip
     */
    public function setDeputy(\AdminBundle\Entity\Deputy $deputy = null)
    {
        $this->deputy = $deputy;

        return $this;
    }

    /**
     * Get deputy
     *
     * @return \AdminBundle\Entity\Deputy
     */
    public function getDeputy()
    {
        return $this->deputy;
    }

    /**
     * Set votingDeputy
     *
     * @param \AdminBundle\Entity\Deputy $votingDeputy
     * @return Skip
     */
    public function setVotingDeputy(\AdminBundle\Entity\Deputy $votingDeputy = null)
    {
        $this->votingDeputy = $votingDeputy;

        return $this;
    }

    /**
     * Get votingDeputy
     *
     * @return \AdminBundle\Entity\Deputy
     */
    public function getVotingDeputy()
    {
        return $this->votingDeputy;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Skip
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set end_date
     *
     * @param \DateTime $endDate
     * @return Skip
     */
    public function setEndDate($endDate)
    {
        $this->end_date = $endDate;

        return $this;
    }

    /**
     * Get end_date
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->end_date;
    }
}
