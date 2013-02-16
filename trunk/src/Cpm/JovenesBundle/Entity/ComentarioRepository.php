<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CoomentarioRepository
 *
 */
class ComentarioRepository extends EntityRepository
{

	
	public function findAllQuery($user_id = null) {
		$qb = $this->getEntityManager()->createQueryBuilder()
		->add('select','c')
		->add('from','CpmJovenesBundle:Comentario c')
		->orderBy('c.fecha', 'DESC');
	
		if ($user_id != null)
			$qb->andWhere('c.destinatario = :destinatario')
				->setParameter('destinatario',$user_id);
		
		return  $qb->getQuery();
	}
	
	
}