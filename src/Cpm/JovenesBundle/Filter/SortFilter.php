<?php

namespace Cpm\JovenesBundle\Filter;

class SortFilter
{
	private $sortField;
	private $sortOrder;

	public function __construct($sortField = '', $sortOrder = 'asc'){
		$this->sortField=$sortField;
		$this->sortOrder=$sortOrder;
	}
	
	public function getSortField(){
		return $this->sortField;	
	}
	
	public function setSortField($sortField){
		$this->sortField=$sortField;	
	}
	
	public function getSortOrder(){
		return $this->sortOrder;	
	}
	
	public function setSortOrder($sortOrder){
		$this->sortOrder=$sortOrder;	
	}
}
