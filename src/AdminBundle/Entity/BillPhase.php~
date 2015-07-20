<?php
namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="bill_phase")
 * @ORM\Entity
 */
class BillPhase
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(name="title", type="string", nullable=false)
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity="Bill", mappedBy="billPhase")
     */
    private $bills;

    /**
     * @ORM\OneToMany(targetEntity="Vote", mappedBy="billPhase")
     */
    private $votes;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->bills = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return BillPhase
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
     * Add bills
     *
     * @param \AdminBundle\Entity\Bill $bills
     * @return BillPhase
     */
    public function addBill(\AdminBundle\Entity\Bill $bills)
    {
        $this->bills[] = $bills;

        return $this;
    }

    /**
     * Remove bills
     *
     * @param \AdminBundle\Entity\Bill $bills
     */
    public function removeBill(\AdminBundle\Entity\Bill $bills)
    {
        $this->bills->removeElement($bills);
    }

    /**
     * Get bills
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBills()
    {
        return $this->bills;
    }

    /**
     * Add votes
     *
     * @param \AdminBundle\Entity\Vote $votes
     * @return BillPhase
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
