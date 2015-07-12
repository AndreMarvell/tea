<?php

namespace TeaCampus\CommonBundle\Entity\Repository;

/**
 * VideoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class VideoRepository extends \Doctrine\ORM\EntityRepository
{
   public function findPopular() {
 
        $query = $this->createQueryBuilder('v');
        $query->andWhere('v.enabled = true');
        $query->orderBy('v.date','DESC');
        $query->setMaxResults(5);
        
        return $query->getQuery()->getResult();
    } 
}