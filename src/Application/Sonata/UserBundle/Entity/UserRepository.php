<?php

namespace Application\Sonata\UserBundle\Entity;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public function search($search, $limit = null)
    {
        $query = $this->createQueryBuilder('u');
        $query->where($query->expr()->like('u.username', ':search'))
              ->orWhere($query->expr()->like('u.firstname', ':search'))
              ->orWhere($query->expr()->like('u.lastname', ':search'))
              ->orWhere($query->expr()->like('u.email', ':search'))
              ->orWhere($query->expr()->like(
                            $query->expr()->concat('u.firstname', $query->expr()->concat(':space', 'u.lastname')),
                            ':search'
                      ))
              ->orWhere($query->expr()->like(
                            $query->expr()->concat('u.lastname', $query->expr()->concat(':space', 'u.firstname')),
                            ':search'
                      ))
              ->setParameter('search', '%'.$search.'%')
              ->setParameter('space', " ");
        if(!is_null($limit)){
            $query->setMaxResults($limit);
        }
 
        return $query->getQuery()->getResult();
    }
}
