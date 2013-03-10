<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * EtapaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EtapaRepository extends EntityRepository
{
	public function findAll($conditions = array()){
		return $this->findBy($conditions, array('numero' => 'ASC')	);
	}
	
	public function findOne($conditions = array()){
			$query = $this->createQueryBuilder('e');
		if($anteriores)
			$query->where('e.numero < :numero');
		else
			$query->where('e.numero > :numero');
			
		$query->orderBy('e.numero','DESC')
			->setParameter('numero', $etapa->getNumero())
			->setSize($count)
			->getQuery()
		;
		return $query->getResult();
	}


	public function findPrimerEtapa(){
		$etapaUno = $this->findOneBy(array(), array('numero' => 'ASC'));
		if (empty($etapaUno))
			throw new \OutOfRangeException("No existe una etapa UNO, esto no deberia suceder");
		return $etapaUno;
	}
	
	public function findEtapaAnteriorA($etapa){
		$proxEtapas = $this->findEtapasProximas($etapa, true,1);
		if (empty($proxEtapas))
			return null;
		else
			return array_pop($proxEtapas); 
	}
	
	public function findEtapaSiguienteA($etapa){
		$proxEtapas = $this->findEtapasProximas($etapa, false,1);
		
		if (empty($proxEtapas))
			return null;
		else			
			return array_pop($proxEtapas);
	}
		
	public function findEtapasProximas($etapa, $anteriores = true, $count = 1){
		$query = $this->createQueryBuilder('e');
		if($anteriores)
			$query->where('e.numero < :numero')->orderBy('e.numero', 'DESC');
		else
			$query->where('e.numero > :numero')->orderBy('e.numero', 'ASC');
			
		$query
			->setParameter('numero', $etapa->getNumero())
			->setMaxResults($count)
		;
		return $query->getQuery()->getResult();
	}
	
}