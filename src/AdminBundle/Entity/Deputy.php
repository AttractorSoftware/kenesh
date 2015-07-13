<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Deputy
 *
 * @ORM\Table(name="deputy")
 * @ORM\Entity
 */
class Deputy
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


    /**
     * @var integer
     *
     * @ORM\Column(name="full_name", type="string", nullable=true)
     */
    private $fullName;


    /**
     * @var string
     *
     * @ORM\Column(name="biography", type="text", nullable=false)
     */
    private $biography;


    /**
     * @var string
     *
     * @ORM\Column(name="kenesh_link", type="string", nullable=true)
     */
    private $keneshLink;


    /**
     * @ORM\ManyToOne(targetEntity="Faction", inversedBy="deputies")
     */
    private $faction;

    /**
     * @ORM\OneToMany(targetEntity="Skip", mappedBy="deputy")
     */
    private $skips;


    /**
     * @ORM\OneToMany(targetEntity="Skip", mappedBy="votingDeputy")
     */
    private $voteSubstitute;

    /**
     * @ORM\OneToMany(targetEntity="Vote", mappedBy="deputy")
     */
    private $votes;

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
     * Set fullName
     *
     * @param string $fullName
     * @return Deputy
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set biography
     *
     * @param string $biography
     * @return Deputy
     */
    public function setBiography($biography)
    {
        $this->biography = $biography;

        return $this;
    }

    /**
     * Get biography
     *
     * @return string
     */
    public function getBiography()
    {
        return $this->biography;
    }

    /**
     * Set keneshLink
     *
     * @param string $keneshLink
     * @return Deputy
     */
    public function setKeneshLink($keneshLink)
    {
        $this->keneshLink = $keneshLink;

        return $this;
    }

    /**
     * Get keneshLink
     *
     * @return string
     */
    public function getKeneshLink()
    {
        return $this->keneshLink;
    }

    /**
     * Set faction
     *
     * @param \AdminBundle\Entity\Faction $faction
     * @return Deputy
     */
    public function setFaction(\AdminBundle\Entity\Faction $faction = null)
    {
        $this->faction = $faction;

        return $this;
    }

    /**
     * Get faction
     *
     * @return \AdminBundle\Entity\Faction
     */
    public function getFaction()
    {
        return $this->faction;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->skips = new \Doctrine\Common\Collections\ArrayCollection();
        $this->voteSubstitute = new \Doctrine\Common\Collections\ArrayCollection();
        $this->votes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add skips
     *
     * @param \AdminBundle\Entity\Skip $skips
     * @return Deputy
     */
    public function addSkip(\AdminBundle\Entity\Skip $skips)
    {
        $this->skips[] = $skips;

        return $this;
    }

    /**
     * Remove skips
     *
     * @param \AdminBundle\Entity\Skip $skips
     */
    public function removeSkip(\AdminBundle\Entity\Skip $skips)
    {
        $this->skips->removeElement($skips);
    }

    /**
     * Get skips
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSkips()
    {
        return $this->skips;
    }

    /**
     * Add voteSubstitute
     *
     * @param \AdminBundle\Entity\Skip $voteSubstitute
     * @return Deputy
     */
    public function addVoteSubstitute(\AdminBundle\Entity\Skip $voteSubstitute)
    {
        $this->voteSubstitute[] = $voteSubstitute;

        return $this;
    }

    /**
     * Remove voteSubstitute
     *
     * @param \AdminBundle\Entity\Skip $voteSubstitute
     */
    public function removeVoteSubstitute(\AdminBundle\Entity\Skip $voteSubstitute)
    {
        $this->voteSubstitute->removeElement($voteSubstitute);
    }

    /**
     * Get voteSubstitute
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVoteSubstitute()
    {
        return $this->voteSubstitute;
    }

    /**
     * Add votes
     *
     * @param \AdminBundle\Entity\Vote $votes
     * @return Deputy
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
