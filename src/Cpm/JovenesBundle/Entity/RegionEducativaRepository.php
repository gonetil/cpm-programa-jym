<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * RegionEducativaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RegionEducativaRepository extends EntityRepository
{
	public function findAllQuery() {
		return $this->createQueryBuilder('t')->getQuery();
	}
}