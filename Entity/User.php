<?php

namespace Dywee\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Dywee\UserBundle\Repository\UserRepository")
 */
class User extends BaseUser implements UserInterface
{
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** @ORM\Column(name="gender", type="smallint", nullable=true) */
    protected $gender;

    /** @ORM\Column(name="firstName", type="text", length=255) */
    protected $firstName = '';

    /** @ORM\Column(name="lastName", type="text", length=255) */
    protected $lastName = '';

    /** @ORM\Column(name="lastIp", type="text", length=255, nullable=true) */
    protected $lastIp = '';

    /** @ORM\Column(name="createdAt", type="datetime", nullable=true) */
    protected $createdAt;

    /**
     * @inheritdoc
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @inheritdoc
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @inheritdoc
     */
    public function setLastIp($lastIp)
    {
        $this->lastIp = $lastIp;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getLastIp()
    {
        return $this->lastIp;
    }

    /**
     * @inheritdoc
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return User
     * @deprecated
     */
    public function setCreationDate($creationDate)
    {
        return $this->setCreatedAt($creationDate);
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     * @deprecated
     */
    public function getCreationDate()
    {
        return $this->getCreatedAt();
    }

    /**
     * @inheritdoc
     */
    public function setCreatedAt(\DateTime $date)
    {
        $this->createdAt = $date;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @inheritdoc
     */
    public function getName($order = 'first')
    {
        return $this->getFirstName().' '.$this->getLastName();
    }
}
