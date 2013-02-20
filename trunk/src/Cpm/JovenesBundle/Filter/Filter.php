<?php

namespace Cpm\JovenesBundle\Filter;

class Filter
{
	private $modelFilter;
	private $sortField;
	private $sortOrder;
    private $batchAction;
    private $batchActionType; //todos o !todos FIXME
	private $selectedEntities;
	private $ciclo_activo;
	
	public function __construct(ModelFilter $modelFilter, $ciclo_activo,$sortField = '', $sortOrder = 'asc'){
		$this->modelFilter = $modelFilter;
		$this->sortField=$sortField;
		$this->sortOrder=$sortOrder;
		$this->ciclo_activo = $ciclo_activo;
		$this->selectedEntities = new \Doctrine\Common\Collections\ArrayCollection();
	}
	
	public function getModelFilter(){
		return $this->modelFilter;	
	}
	
	public function setModelFilter($modelFilter){
		$this->modelFilter=$modelFilter;	
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


    public function hasBatchAction() {
    	return !empty($this->batchAction);
    }
    
    public function getBatchAction() {
    	return $this->batchAction;
    }
    public function setBatchAction($batch) {
    	$this->batchAction = $batch;
    }
    
    public function isBatchActionTypeTodos() { 
    	return $this->batchActionType == "todos";
    }
    
    public function getBatchActionType() { 
    	return $this->batchActionType;
    }
    
    public function setBatchActionType($type) {
    	return $this->batchActionType = $type;
    }
    
   	public function getSelectedEntities() {
		return $this->selectedEntities;
	}

	public function setSelectedEntities($x) {
		$this->selectedEntities = $x;
	}
	
	private $page_size = 20;
	private $page_number = 1;
	public function getPageSize() {
		return $this->page_size;
	}
	public function setPageSize($size) {
		$this->page_size = $size;
	}
	
	public function getPageNumber() {
		return $this->page_number;
	}
	public function setPageNumber($number) {
		$this->page_number = $number;
	}
	
	
	public function getCicloActivo()
	{
		return $this->ciclo_activo;
	}
	
	public function setCicloActivo($ciclo)
	{
		$this->ciclo_activo = $ciclo;
	}
}
	