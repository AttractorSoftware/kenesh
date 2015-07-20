<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="Vote")
 * @ORM\Entity
 */
class Vote
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Deputy", inversedBy="votes")
     */
    private $deputy;


    /**
     * @ORM\ManyToOne(targetEntity="Bill", inversedBy="votes")
     */
    private $bill;

    /**
     * @ORM\ManyToOne(targetEntity="BillPhase", inversedBy="votes")
     */
    private $billPhase;

    /**
     * @ORM\Column(name="protocol_number", type="string", nullable=false)
     */
    private $protocolNumber;

    /**
     * @ORM\Column(name="datetime", type="datetime", nullable=false)
     */
    private $datetime;

    /**
     * @ORM\Column(name="result", type="string", nullable=false)
     */
    private $result;

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
     * Set protocolNumber
     *
     * @param string $protocolNumber
     * @return Vote
     */
    public function setProtocolNumber($protocolNumber)
    {
        $this->protocolNumber = $protocolNumber;

        return $this;
    }

    /**
     * Get protocolNumber
     *
     * @return string
     */
    public function getProtocolNumber()
    {
        return $this->protocolNumber;
    }

    /**
     * Set datetime
     *
     * @param \DateTime $datetime
     * @return Vote
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * Get datetime
     *
     * @return \DateTime
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * Set result
     *
     * @param string $result
     * @return Vote
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Get result
     *
     * @return string
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set deputy
     *
     * @param \AdminBundle\Entity\Deputy $deputy
     * @return Vote
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
     * Set bill
     *
     * @param \AdminBundle\Entity\Bill $bill
     * @return Vote
     */
    public function setBill(\AdminBundle\Entity\Bill $bill = null)
    {
        $this->bill = $bill;

        return $this;
    }

    /**
     * Get bill
     *
     * @return \AdminBundle\Entity\Bill
     */
    public function getBill()
    {
        return $this->bill;
    }

    /**
     * Set billPhase
     *
     * @param \AdminBundle\Entity\BillPhase $billPhase
     * @return Vote
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
}
