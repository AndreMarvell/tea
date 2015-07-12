<?php



namespace Application\Sonata\ClassificationBundle\Entity;

use Sonata\ClassificationBundle\Entity\BaseCategory;


class CategoryRepository extends BaseCategory
{
    
        
    public function findCategories($locale='fr') {
 
        $query = $this->createQueryBuilder('p');
        $query->where('p.enabled = true ');
        $query->orderBy('p.createdAt','DESC');
        $query->setMaxResults($limit);
        
        return $query->getQuery()->getResult();
    }
}