<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * EjeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EjeRepository extends EntityRepository
{
	
		public function findAllQuery() {
			$qb = $this->getEntityManager()->createQueryBuilder()
					->add('select','e')
					->add('from','CpmJovenesBundle:Eje e');
			//
			return $qb->getQuery();
		
	}
}