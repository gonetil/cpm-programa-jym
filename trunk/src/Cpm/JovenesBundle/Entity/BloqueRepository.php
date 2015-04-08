<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Cpm\JovenesBundle\Filter\BloqueFilter;


/**
 * BloqueRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BloqueRepository extends EntityRepository
{
	public function findAllQuery() {
		$qb = $this->getEntityManager()->createQueryBuilder()
		->add('select','d')
		->add('from','CpmJovenesBundle:Bloque d');
	
		return  $qb->getQuery();
	}
	
		public function filterQuery(BloqueFilter $filter,$ciclo_activo,$sort_field = null, $sort_order) {
		
	
	 	$qb = $this->createQueryBuilder('b')
	 		->innerJoin('b.auditorioDia','ad')
	 	    ->innerJoin('ad.dia','d')
			->addOrderBy('d.numero', 'asc');
		
		$cicloFilter = $filter->getCicloFilter();
		if  ($cicloFilter && ($ciclo = $cicloFilter['ciclo'])) {
			$qb
			->innerJoin('d.tanda','t')
			->innerJoin('t.instanciaEvento','ie')
			->innerJoin('ie.evento','e')
			->andWhere('e.ciclo = :ciclo')->setParameter('ciclo',$ciclo);
		}
	
		return $qb;
	
	}
}