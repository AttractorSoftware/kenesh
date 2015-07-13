<?php
namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="Subscription")
 * @ORM\Entity
 */
class Subscription
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(name="email", type="string", nullable=false)
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity="Bill", inversedBy="subscriptions")
     */
    private $bill;

    /**
     * @ORM\Column(name="token", type="string", nullable=false)
     */
    private $token;

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
     * Set email
     *
     * @param string $email
     * @return Subscription
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return Subscription
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set bill
     *
     * @param \AdminBundle\Entity\Bill $bill
     * @return Subscription
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
}
