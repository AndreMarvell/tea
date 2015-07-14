<?php

namespace TeaCampus\CommonBundle\Entity\Repository;

/**
 * IngredientRepository
 * By : SAMIE MEHEZA
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProjetRepository extends \Doctrine\ORM\EntityRepository
{
    
    public function getSelectedProjet() {
        $query = $this->createQueryBuilder('a');
        $query->where('a.selectionOfTea =  true ');       
        $query->andWhere('a.enabled = true');
        $query->andWhere('a.private = false');
        return $query->getQuery()->getResult();
    }
    
    public function getProjectOfTheMonth() {
        $query = $this->createQueryBuilder('a');
        $query->where('a.projectOfTheMonth =  true ');
        $query->andWhere('a.enabled = true');
        $query->andWhere('a.private = false');
        return $query->getQuery()->getResult();
    }
    
    public function getProjectOfTheWeek() {
        $query = $this->createQueryBuilder('a');
        $query->where('a.projectOfTheWeek =  true ');
        $query->andWhere('a.enabled = true');
        $query->andWhere('a.private = false');
        return $query->getQuery()->getResult();
    }
    
    public function findPopular() {
 
        $query = $this->createQueryBuilder('p');
        $query->where('p.private = false ');
        $query->andWhere('p.enabled = true');
        $query->orderBy('p.date','DESC');
        $query->setMaxResults(5);
        
        return $query->getQuery()->getResult();
    }
    
    public function findLast($limit=5) {
 
        $query = $this->createQueryBuilder('p');
        $query->where('p.enabled = true ');
        $query->where('p.private = false ');
        $query->orderBy('p.date','DESC');
        $query->setMaxResults($limit);
        
        return $query->getQuery()->getResult();
    }
    
    public function findByTag($tag)
    {
        $query = $this->createQueryBuilder('p');
        $query->join('p.tags', 't')
              ->where($query->expr()->in('t.id', $tag));
 
        return $query->getQuery()->getResult();
    }
    
    public function search($search, $limit = null)
    {
        $query = $this->createQueryBuilder('p');
        $query->where($query->expr()->like('p.title', ':search'))
              ->orWhere($query->expr()->like('p.description', ':search'))
              ->setParameter('search', '%'.$search.'%');
        if(!is_null($limit)){
            $query->setMaxResults($limit);
        }
 
        return $query->getQuery()->getResult();
    }
}
