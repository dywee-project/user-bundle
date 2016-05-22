<?php

namespace Dywee\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
    public function countActiveUser($website)
    {
        $qb = $this->createQueryBuilder('u')
            ->select('count(u)')
            ->join('u.website', 'w')
            ->where('u.enabled = 1 and w = :website')
            ->setParameter('website', $website);

        return $qb->getQuery()->getSingleScalarResult();
    }
}