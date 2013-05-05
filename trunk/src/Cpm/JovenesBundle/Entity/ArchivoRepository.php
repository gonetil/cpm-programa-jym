<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ArchivoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArchivoRepository extends EntityRepository
{
	public function findAllQuery() {
		$qb = $this->getEntityManager()->createQueryBuilder()
			->add('select','a')
			->add('from','CpmJovenesBundle:Archivo a')
			->add('orderBy','a.fecha_creado DESC');
		 
    	return  $qb->getQuery();
  
	}
}