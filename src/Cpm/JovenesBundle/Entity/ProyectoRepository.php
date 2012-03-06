<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ProyectoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProyectoRepository extends EntityRepository
{
	
	function findBySearchCriteria($data) {
		
		$qb = $this->getEntityManager()->createQueryBuilder();
		
		$qb->add('select','p')
			->add('from','CpmJovenesBundle:Proyecto p');
		
		
		if ($data->getEsPrimeraVezDocente()) $qb->andWhere('p.esPrimeraVezDocente = :pvd')->setParameter('pvd',$data->getEsPrimeraVezDocente());
		
		if ($data->getEsPrimeraVezAlumnos()) $qb->andWhere('p.esPrimeraVezAlumnos = :pva')->setParameter('pva',$data->getEsPrimeraVezAlumnos());

		if ($data->getEsPrimeraVezEscuela()) $qb->andWhere('p.esPrimeraVezEscuela = :pve')->setParameter('pve',$data->getEsPrimeraVezEscuela());
		
		if ($data->getProduccionFinal()) $qb->andWhere('p.produccionFinal = :pf')->setParameter('pf',$data->getProduccionFinal());
		
		if ($data->getTemaPrincipal()) $qb->andWhere('p.temaPrincipal = :tp')->setParameter('tp',$data->getTemaPrincipal());
		
		
		if ($data->getRegion()) { 
			 $qb->innerJoin('p.escuela e')
				->join("e.localidad l")
				->join("l.distrito d")
			    ->join("d.region r")
				->andWhere('r = :region')
			 	->setParameter('region',$data->getRegion())
			;			
		}
		
		$qb->add('orderBy','p.id AsC');

		$proyectos = $qb->getQuery()->getResult();
		return $proyectos;
	}
}