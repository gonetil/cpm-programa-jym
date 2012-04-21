<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Cpm\JovenesBundle\Filter\InvitacionFilter;

/**
 * InvitacionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class InvitacionRepository extends EntityRepository
{
	public function findAllQuery() {
		$qb = $this->getEntityManager()->createQueryBuilder()
		->add('select','i')
		->add('from','CpmJovenesBundle:Invitacion i')
		->add('orderBy','i.fechaCreacion ASC')
		;
	
		return  $qb->getQuery();
	}
	
	public function getInvitacionesDTO($filters = array()){
		$qb = $this->getEntityManager()->createQueryBuilder()
		
		//SELECT partial u.{id, username} FROM CmsUser u')
			->add('select','i, c.id as id_coordinador, c.apellido as apellido_coordinador, c.nombre as nombre_coordinador, e.nombre, l.nombre as localidad_escuela, d.nombre as distrito_escuela')
			->from('CpmJovenesBundle:Invitacion','i')
			->innerJoin('i.proyecto','p' )
			->innerJoin('p.escuela','e' )
			->innerJoin('e.localidad','l' )
			->innerJoin('l.distrito','d' )
			->innerJoin('p.coordinador','c')
		;
		
		if (!empty($filters['instanciaEvento']))
			$qb->andWhere('i.instanciaEvento = :instancia')->setParameter('instancia',$filters['instanciaEvento']);
		$qb->orderBy('c.apellido,c.nombre','ASC');
		return $qb->getQuery()->getResult();
	}
	
	
	public function getCantidadesPorInstancia($instancia) {
		
		$qb = $this->getEntityManager()->createQueryBuilder()
		->add('select','i.aceptoInvitacion, count(i.aceptoInvitacion) as cant')
		->add('from','CpmJovenesBundle:Invitacion i')
		->andWhere('i.instanciaEvento = :instancia')->setParameter('instancia',$instancia)
		->add('groupBy','i.aceptoInvitacion')
		;
		$cantidades=array('invitados'=>0, 'confirmaciones'=>0, 'rechazos'=>0);
		$rows = $qb->getQuery()->getResult();
		
		foreach ( $rows as $row ) {
			$cantidades['invitados']+=$row['cant'];
			if ($row['aceptoInvitacion'] == '0'){
				$cantidades['rechazos']=$row['cant'];
			}elseif ($row['aceptoInvitacion'] == '1'){
				$cantidades['confirmaciones']=$row['cant'];
			}
				
		}
		
		return $cantidades;
	}
	
	public function getInvitacionesPendientes($instancia) { 
		$qb = $this->getEntityManager()->createQueryBuilder()
				->add('select','i')
				->add('from','CpmJovenesBundle:Invitacion i')
				->andWhere('i.instanciaEvento = :instancia')->setParameter('instancia',$instancia)
				->andWhere('i.aceptoInvitacion is NULL');
	
			return $qb->getQuery()->iterate();//->getResult(); 
				
	}

	
	public function filterQuery(InvitacionFilter $filter) {
		$qb = $this->createQueryBuilder('c')->orderBy('c.fechaCreacion', 'DESC');
		if ($filter->getFechaMin())
			$qb->andWhere('c.fechaCreacion > :fechaMin')->setParameter('fechaMin',$filter->getFechaMin());
		if ($filter->getFechaMax())
			$qb->andWhere('c.fechaCreacion < :fechaMax')->setParameter('fechaMax',$filter->getFechaMax());
		if ($filter->getSuplente())
			$qb->andWhere('c.suplente LIKE :sup')->setParameter('sup','%'.$filter->getSuplente().'%');
		if ($filter->getProyecto())
			$qb->andWhere('c.proyecto = :proyecto')->setParameter('proyecto',$filter->getProyecto());
		if ($filter->getInstanciaEvento())
			$qb->andWhere('c.instanciaEvento = :instancia')->setParameter('instancia',$filter->getInstanciaEvento());
		
		if ($filter->getSolicitaHospedaje()) {
			$pvh = ($filter->getSolicitaHospedaje() != 1)?0 : 1;
			$qb->andWhere('c.solicitaHospedaje = :pvh')->setParameter('pvh', $pvh);
		}
		
		if ($filter->getSolicitaViaje()) {
			$pvv = ($filter->getSolicitaViaje() != 1)?0 : 1;
			$qb->andWhere('c.solicitaViaje = :pvv')->setParameter('pvv', $pvv);
		}

		return $qb;
	}
	
}