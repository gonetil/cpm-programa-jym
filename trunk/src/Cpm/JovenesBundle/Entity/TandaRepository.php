<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * TandaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TandaRepository extends EntityRepository
{

	public function getTandaDeInstanciaEvento($instanciaEvento) {
		
		$qb = $this->createQueryBuilder('t');
		$qb	->andWhere("t.instanciaEvento = :instanciaEvento")
		->setParameter("instanciaEvento",$instanciaEvento);
		return $qb->getQuery()->getOneOrNullResult(); 	
	}
	
	public function getTandasDeEvento($evento) {
		
		$qb = $this->createQueryBuilder('t');
		
		$qb	->innerJoin('t.instanciaEvento','ie')
			->andWhere("ie.evento = :evento")->setParameter("evento",$evento);
		return $qb->getQuery()->getResult(); 	
	}
	
	public function findAllQuery($ciclo = null) {
		
		$qb = $this->createQueryBuilder('t')
		->innerJoin('t.instanciaEvento','ie')
		->innerJoin('ie.evento','e')
		->addOrderBy('ie.id', 'desc')->addOrderBy('t.numero','ASC');
			
		if ($ciclo)
			$qb->andWhere('e.ciclo = :ciclo')->setParameter('ciclo',$ciclo);
		
		return  $qb->getQuery();
	}
}