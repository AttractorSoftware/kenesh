<?php
namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="BillResultHistory")
 * @ORM\Entity
 */
class BillResultHistory
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
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $descrtiption;

    /**
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

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
     * Set descrtiption
     *
     * @param string $descrtiption
     * @return BillResultHistory
     */
    public function setDescrtiption($descrtiption)
    {
        $this->descrtiption = $descrtiption;

        return $this;
    }

    /**
     * Get descrtiption
     *
     * @return string
     */
    public function getDescrtiption()
    {
        return $this->descrtiption;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return BillResultHistory
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
}
