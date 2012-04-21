<?php

namespace Cpm \ JovenesBundle \ Filter;

interface ModelFilter{
	public function createForm(); //retorna un ModelFilterForm
	
	public function getTargetEntity(); //retorna un ModelFilterForm
	
}