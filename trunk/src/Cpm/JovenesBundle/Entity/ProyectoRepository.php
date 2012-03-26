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
	
	function findAllQuery($ciclo = null) { 
		$qb = $this->getEntityManager()->createQueryBuilder();
		
		$qb->add('select','p')
				->add('from','CpmJovenesBundle:Proyecto p');
		if ($ciclo)
		{
			$qb->andWhere('p.ciclo = :ciclo')->setParameter('ciclo',$ciclo);
		}
			return $qb->getQuery();
		
	}
	function findBySearchCriteriaQuery($data,$ciclo = null) {
		
		$qb = $this->getEntityManager()->createQueryBuilder();
		
		$qb->add('select','p')
			->add('from','CpmJovenesBundle:Proyecto p');
		
		if ($ciclo) 
		{
			$qb->andWhere('p.ciclo = :ciclo')->setParameter('ciclo',$ciclo);
		}
		
		if ($data->getEsPrimeraVezDocente()) $qb->andWhere('p.esPrimeraVezDocente = :pvd')->setParameter('pvd',$data->getEsPrimeraVezDocente());
		
		if ($data->getEsPrimeraVezAlumnos()) $qb->andWhere('p.esPrimeraVezAlumnos = :pva')->setParameter('pva',$data->getEsPrimeraVezAlumnos());

		if ($data->getEsPrimeraVezEscuela()) $qb->andWhere('p.esPrimeraVezEscuela = :pve')->setParameter('pve',$data->getEsPrimeraVezEscuela());
		
		if ($data->getProduccionFinal()) $qb->andWhere('p.produccionFinal = :pf')->setParameter('pf',$data->getProduccionFinal());
		
		if ($data->getTemaPrincipal()) $qb->andWhere('p.temaPrincipal = :tp')->setParameter('tp',$data->getTemaPrincipal());
				
		$tiene_escuela = false;
		if ($data->getLocalidad())
		{  
			$tiene_escuela = true;
			$qb->innerJoin('p.escuela','e')
			->innerJoin("e.localidad",'l')
			->andWhere('l = :localidad')
			->setParameter('localidad',$data->getLocalidad())
			;
		} elseif ($data->getDistrito()) 
			{ 
				$tiene_escuela = true;
				 $qb->innerJoin('p.escuela','e')
					->innerJoin("e.localidad",'l')
					->innerJoin("l.distrito",'d')
					->andWhere('d = :distrito')
				 	->setParameter('distrito',$data->getDistrito())
				;			
			}
			elseif ($data->getRegion() || $data->getRegionDesde() || $data->getRegionHasta()) //la region solo se considera si no se eligio ni la localidad ni el distrito 
			{ 
				$tiene_escuela = true;
				 $qb->innerJoin('p.escuela','e')
					->innerJoin("e.localidad",'l')
					->innerJoin("l.distrito",'d')
				    ->innerJoin("d.region",'r');
				    
				    if ($data->getRegion())  
						$qb->andWhere('r = :region')->setParameter('region',$data->getRegion());
				    else { 
				    	if ($data->getRegionDesde()) $qb->andWhere('r.id >= :regionDesde')->setParameter('regionDesde',$data->getRegionDesde());
				    	if ($data->getRegionHasta()) $qb->andWhere('r.id <= :regionHasta')->setParameter('regionHasta',$data->getRegionHasta());
				    }
				;			
			}
			
			if ($data->getOtroTipoInstitucion()) 
			{
				if (!$tiene_escuela ) $qb->innerJoin('p.escuela','e');
				$qb->andWhere('e.tipoInstitucion is NULL'); 
			} 
			elseif ($data->getTipoInstitucion()) 
			{ 
					if (!$tiene_escuela ) $qb->innerJoin('p.escuela','e');
					
					$qb->innerJoin("e.tipoInstitucion",'t')
					   ->andWhere('t = :tipoInstitucion')->setParameter('tipoInstitucion',$data->getTipoInstitucion())
				;			
			}

		
			if (trim($data->getCoordinador()) != "") { 
				$qb->innerJoin("p.coordinador","coordinador")
					->andWhere("coordinador.apellido like :apellido")
					->setParameter("apellido",(trim($data->getCoordinador())."%"));
			}
			
			if (trim($data->getEscuela()) != "") {
				$escuela = trim($data->getEscuela());
				if (!$tiene_escuela ) $qb->innerJoin('p.escuela','e');
				
				if (is_numeric($escuela)) { 
					$qb->andWhere("e.numero = :numero")
						->setParameter("numero",$escuela);
				} 
				else 
				{
					$qb->andWhere("e.nombre like :nombreEscuela")
					->setParameter("nombreEscuela",$escuela."%");
				}
				
			}
			
			$qb->add('orderBy','p.id AsC');
	  
		$proyectos = $qb->getQuery();
		return $proyectos;
	}
	
	function findAllInArray($ids=array()) {
		$qb = $this->getEntityManager()->createQueryBuilder();
		
		$qb->add('select','p')
		->add('from','CpmJovenesBundle:Proyecto p')
		->andWhere("p.id in (:proyectos)")->setParameter("proyectos",array_values($ids))
		;
		
		return $qb->getQuery();
	
	}
}