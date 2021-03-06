<?php

namespace Cpm\JovenesBundle\Entity;

use Cpm \ JovenesBundle \ Filter \ EscuelaFilter;
use Doctrine\ORM\EntityRepository;

/**
 * EscuelaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EscuelaRepository extends EntityRepository
{
	
	
	static $sort_criteria = array("id" => "e.id","nombre"=>"e.nombre","numero"=>"e.numero");
		
	public function findAllQuery() {
		$qb = $this->getEntityManager()->createQueryBuilder()
		->add('select','e')
		->add('from','CpmJovenesBundle:Escuela e');
		return  $qb->getQuery();
	}
	
	
	public function filterQuery(EscuelaFilter $data, $ciclo_activo,$sort_field = null, $sort_order) {
		$qb = $this->createQueryBuilder('e');
										
		/* if ($sort_field) {
			$field = (isset(EscuelaRepository::$sort_criteria[$sort_field]))?EscuelaRepository::$sort_criteria[$sort_field]:EscuelaRepository::$sort_criteria['id'];
			$qb->orderBy($field,$sort_order);
		} */

			$qb_proyectos = $this->getEntityManager()->createQueryBuilder();
			$qb_proyectos->select('p')->from('CpmJovenesBundle:Proyecto','p')
					->innerJoin('p.ciclo','ciclo')->andWhere('ciclo = :ciclo')
					
				; 
		
		$cicloFilter = $data->getCicloFilter();
		if  ($cicloFilter && ($ciclo = $cicloFilter['ciclo'])) {
				$qb->andWhere($qb->expr()->in('e',$qb_proyectos->getDQL()))->setParameter('ciclo',$ciclo);
		} else {
			$qb->andWhere($qb->expr()->in('e',$qb_proyectos->getDQL()))->setParameter('ciclo',$ciclo_activo);
		}
		
		
		
		return $qb;
	} 
	
}