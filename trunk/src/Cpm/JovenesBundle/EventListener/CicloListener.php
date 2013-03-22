<?php
/*
 * Created on 18/03/2013
 * @author gonetil
 * project jym
 * Copyleft 2013
 * 
 */


// src/Acme/SearchBundle/EventListener/SearchIndexer.php
namespace Cpm\JovenesBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Cpm\JovenesBundle\Entity\Ciclo;

class CicloListener
{
	private $logger;
	
	public function __construct($logger) {
		$this->logger = $logger;
	}
    
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        
        if ($entity instanceof Ciclo) {
			$txt = "Persistiendo ciclo. ";
			$txt .= "Etapa actual {$entity->getEtapaActual()}";
		/*		
	  		if ($event->hasChangedField('etapaActual')) {
				$txt .= "\n Se cambia la etapa actual '{$event->getOldValue($fieldName)}' por la  etapa '{$event->getNewValue($fieldName)}' ";
			} else { 
				$txt .= "\n No se detectaron cambios en la etapaActual";
			}
		*/
		  $this->logger->warn($txt);
		    
        }
    }
}
?>
