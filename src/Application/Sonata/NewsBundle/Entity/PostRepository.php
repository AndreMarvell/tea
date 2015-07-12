<?php

/**
 * This file is part of the <name> project.
 *
 * (c) <yourname> <youremail>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\NewsBundle\Entity;

use Sonata\NewsBundle\Entity\BasePostRepository;

/**
 * This file has been generated by the EasyExtends bundle ( https://sonata-project.org/bundles/easy-extends )
 *
 * References :
 *   custom repository : http://www.doctrine-project.org/projects/orm/2.0/docs/reference/working-with-objects/en#querying:custom-repositories
 *   query builder     : http://www.doctrine-project.org/projects/orm/2.0/docs/reference/query-builder/en
 *   dql               : http://www.doctrine-project.org/projects/orm/2.0/docs/reference/dql-doctrine-query-language/en
 *
 * @author <yourname> <youremail>
 */
class PostRepository extends BasePostRepository
{
    
        
    public function findLastNews($limit=5) {
 
        $query = $this->createQueryBuilder('p');
        $query->where('p.enabled = true ');
        $query->orderBy('p.createdAt','DESC');
        $query->setMaxResults($limit);
        
        return $query->getQuery()->getResult();
    }
    
    public function findPopular() {
 
        $query = $this->createQueryBuilder('p');
        $query->where('p.enabled = true ');
        $query->orderBy('p.commentsCount','DESC');
        $query->setMaxResults(5);
        
        return $query->getQuery()->getResult();
    }
    
    public function search($search) {
 
        $query = $this->createQueryBuilder('p');
        $query->where('p.enabled = true ');
        $query->andWhere($query->expr()->like('p.title',':search'));
        $query->setParameter('search', '%'.$search.'%');
        
        return $query->getQuery()->getResult();
    }
}