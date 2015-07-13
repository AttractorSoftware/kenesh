<?php
namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Faction
 *
 * @ORM\Table(name="faction")
 * @ORM\Entity
 */
class Faction
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
     * @ORM\OneToMany(targetEntity="Deputy", mappedBy="faction")
     */
    private $deputies;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->deputies = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Faction
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
     * Add deputies
     *
     * @param \AdminBundle\Entity\Deputy $deputies
     * @return Faction
     */
    public function addDeputy(\AdminBundle\Entity\Deputy $deputies)
    {
        $this->deputies[] = $deputies;

        return $this;
    }

    /**
     * Remove deputies
     *
     * @param \AdminBundle\Entity\Deputy $deputies
     */
    public function removeDeputy(\AdminBundle\Entity\Deputy $deputies)
    {
        $this->deputies->removeElement($deputies);
    }

    /**
     * Get deputies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDeputies()
    {
        return $this->deputies;
    }
}
