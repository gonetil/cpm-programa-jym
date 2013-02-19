<?php

namespace Cpm \ JovenesBundle \ Filter;

interface ModelFilter{
	
	public function createForm(\Cpm\JovenesBundle\Service\JYM $jym);//retorna un ModelFilterForm
	
	public function getTargetEntity(); //retorna un ModelFilterForm
	
}