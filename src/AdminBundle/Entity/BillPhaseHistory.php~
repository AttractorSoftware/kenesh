<?php
namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="BillPhaseHistory")
 * @ORM\Entity
 */
class BillPhaseHistory
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Bill", inversedBy="billPhaseHistories")
     */
    private $bill;

    /**
     * @ORM\ManyToOne(targetEntity="BillPhase", inversedBy="billPhaseHistories")
     */
    private $phase;

    /**
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @ORM\OneToOne(targetEntity="BillResultHistory")
     */
    private $billResultHistory;



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
     * Set date
     *
     * @param \DateTime $date
     * @return BillPhaseHistory
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set bill
     *
     * @param \AdminBundle\Entity\Bill $bill
     * @return BillPhaseHistory
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
     * Set phase
     *
     * @param \AdminBundle\Entity\BillPhase $phase
     * @return BillPhaseHistory
     */
    public function setPhase(\AdminBundle\Entity\BillPhase $phase = null)
    {
        $this->phase = $phase;

        return $this;
    }

    /**
     * Get phase
     *
     * @return \AdminBundle\Entity\BillPhase 
     */
    public function getPhase()
    {
        return $this->phase;
    }

    /**
     * Set billResultHistory
     *
     * @param \AdminBundle\Entity\BillResultHistory $billResultHistory
     * @return BillPhaseHistory
     */
    public function setBillResultHistory(\AdminBundle\Entity\BillResultHistory $billResultHistory = null)
    {
        $this->billResultHistory = $billResultHistory;

        return $this;
    }

    /**
     * Get billResultHistory
     *
     * @return \AdminBundle\Entity\BillResultHistory 
     */
    public function getBillResultHistory()
    {
        return $this->billResultHistory;
    }
}
