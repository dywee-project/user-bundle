<?php

namespace Dywee\UserBundle\Entity;

interface UserInterface
{
    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName);

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName();

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName);

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName();

    /**
     * Set lastIp
     *
     * @param string $lastIp
     * @return User
     */
    public function setLastIp($lastIp);

    /**
     * Get lastIp
     *
     * @return string
     */
    public function getLastIp();

    /**
     * Set gender
     *
     * @param boolean $gender
     * @return User
     */
    public function setGender($gender);

    /**
     * Get gender
     *
     * @return boolean
     */
    public function getGender();

    /**
     * Set creationDate
     *
     * @param \DateTime $date
     * @return User
     */
    public function setCreatedAt(\DateTime $date);

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * @param string $order
     * @return string
     */
    public function getName($order = 'first');
}