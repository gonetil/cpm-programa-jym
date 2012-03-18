<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CorreoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CorreoRepository extends EntityRepository
{
	public function findAllQuery($user_id = null) {
		$qb = $this->getEntityManager()->createQueryBuilder()
		->add('select','c')
		->add('from','CpmJovenesBundle:Correo c')
		->orderBy('c.fecha', 'DESC');
	
		if ($user_id != null)
			$qb->andWhere('c.destinatario = :destinatario')
				->setParameter('destinatario',$user_id);
		
		return  $qb->getQuery();
	}
	
}