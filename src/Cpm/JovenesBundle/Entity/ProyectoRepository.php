<?php
namespace Cpm \ JovenesBundle \ Entity;

use Cpm \ JovenesBundle \ Filter \ ProyectoFilter;
use Doctrine \ ORM \ EntityRepository;

/**
 * ProyectoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProyectoRepository extends EntityRepository {
	
	static $sort_criteria = array("id" => "p.id" , "coordinador" => "coordinador.apellido", "titulo" => "p.titulo");
	
	function findAllQuery($ciclo = null) {
		$qb = $this->getEntityManager()->createQueryBuilder();

		$qb->add('select', 'p')->add('from', 'CpmJovenesBundle:Proyecto p');
		if ($ciclo) {
			$qb->andWhere('p.ciclo = :ciclo')->setParameter('ciclo', $ciclo);
		}
		return $qb->getQuery();

	}

	public function filterQuery(ProyectoFilter $data, $sort_field = null, $sort_order) {
		
		$qb = $this->createQueryBuilder('p')->innerJoin("p.coordinador", "coordinador");

		if ($sort_field) {
			$field = (isset(ProyectoRepository::$sort_criteria[$sort_field]))?ProyectoRepository::$sort_criteria[$sort_field]:ProyectoRepository::$sort_criteria['id'];
			$qb->orderBy($field,$sort_order);
		}
		
		$cicloFilter = $data->getCicloFilter();
//		echo $cicloFilter->getCiclo(); echo $cicloFilter['ciclo']; die;
		if  ($ciclo = $cicloFilter->getCiclo()) { 
	//		echo $ciclo; die;
			$qb->andWhere('p.ciclo = :ciclo')->setParameter('ciclo', $ciclo);
		}
		
		if ($data->getEsPrimeraVezDocente()) {
			$pv = ($data->getEsPrimeraVezDocente() != 1)?0 : 1;
			$qb->andWhere('p.esPrimeraVezDocente = :pvd')->setParameter('pvd', $pv);
		}
		if ($data->getEsPrimeraVezAlumnos()) {
			$pv = ($data->getEsPrimeraVezAlumnos() != 1)?0 : 1;
			$qb->andWhere('p.esPrimeraVezAlumnos = :pva')->setParameter('pva', $pv);
		}
		
		if ($data->getEsPrimeraVezEscuela()) {
			$pv = ($data->getEsPrimeraVezEscuela() != 1)?0 : 1;
			$qb->andWhere('p.esPrimeraVezEscuela = :pve')->setParameter('pve', $pv);
		}
		
		if ($data->getProduccionFinal())
			$qb->andWhere('p.produccionFinal = :pf')->setParameter('pf', $data->getProduccionFinal());

		if ($data->getTemaPrincipal())
			$qb->andWhere('p.temaPrincipal = :tp')->setParameter('tp', $data->getTemaPrincipal());

		if ($color = $data->getColor()) {
			$qb->andWhere('p.color like :color')->setParameter('color', $color);
		}

		if ($transporte = $data->getTransporte()) {
			$qb->andWhere('p.transporte like :transporte')->setParameter('transporte', $transporte);
		}


		if ($escuela = $data->getEscuelaFilter()) {
	
			$tiene_escuela = false;
			if ($escuela->getLocalidad()) {
				$tiene_escuela = true;
				$qb->innerJoin('p.escuela', 'e')->innerJoin("e.localidad", 'l')->andWhere('l = :localidad')->setParameter('localidad', $escuela->getLocalidad());
			}
			elseif ($escuela->getDistrito()) {
				$tiene_escuela = true;
				$qb->innerJoin('p.escuela', 'e')->innerJoin("e.localidad", 'l')->innerJoin("l.distrito", 'd')->andWhere('d = :distrito')->setParameter('distrito', $escuela->getDistrito());
			}
			elseif ($escuela->getRegion() || $escuela->getRegionDesde() || $escuela->getRegionHasta()) //la region solo se considera si no se eligio ni la localidad ni el distrito 
			{
				$tiene_escuela = true;
				$qb->innerJoin('p.escuela', 'e')->innerJoin("e.localidad", 'l')->innerJoin("l.distrito", 'd')->innerJoin("d.region", 'r');

				if ($escuela->getRegion())
					$qb->andWhere('r = :region')->setParameter('region', $escuela->getRegion());
				else {
					if ($escuela->getRegionDesde())
						$qb->andWhere('r.id >= :regionDesde')->setParameter('regionDesde', $escuela->getRegionDesde());
					if ($escuela->getRegionHasta())
						$qb->andWhere('r.id <= :regionHasta')->setParameter('regionHasta', $escuela->getRegionHasta());
				};
			}

			if ($escuela->getOtroTipoInstitucion()) {
				if (!$tiene_escuela)
					$qb->innerJoin('p.escuela', 'e');
				$qb->andWhere('e.tipoInstitucion is NULL');
			}
			elseif ($escuela->getTipoInstitucion()) {
				if (!$tiene_escuela)
					$qb->innerJoin('p.escuela', 'e');

				$qb->innerJoin("e.tipoInstitucion", 't')->andWhere('t = :tipoInstitucion')->setParameter('tipoInstitucion', $escuela->getTipoInstitucion());
			}

			if (trim($escuela->getNombre()) != "") {
				$escuelaSel = trim($escuela->getNombre());
				if (!$tiene_escuela)
					$qb->innerJoin('p.escuela', 'e');

				if (is_numeric($escuelaSel)) {
					$qb->andWhere("e.numero = :numero")->setParameter("numero", $escuelaSel);
				} else {
					$qb->andWhere("e.nombre like :nombreEscuela")->setParameter("nombreEscuela", "%". $escuelaSel .
					"%");
				}

			}

		}
		if (trim($data->getCoordinador()) != "") {

			$qb->andWhere("coordinador.apellido like :apellido")->setParameter("apellido", (trim($data->getCoordinador()) .
			"%"));
		}

		if (trim($data->getDeQueSeTrata()) != "") {

			$qb->andWhere("p.deQueSeTrata like :dqst")->setParameter("dqst", (trim($data->getDeQueSeTrata()) ."%"));
		}

		if ($data->getRequerimientosDeEdicion()) {
			if ($data->getRequerimientosDeEdicion() != 1)
				$qb->andWhere("p.requerimientosDeEdicion like ''");
			else	
				$qb->andWhere("p.requerimientosDeEdicion not like ''");
		}

		$evento = $data->getEventoFilter(); 
		if ($ev = $evento->getEvento()) {  
			if ($evento->getSinInvitacionFlag()) //sin invitacion 
			{ 
				$qb	->andWhere( 'p NOT IN ('.
											' SELECT proyecto FROM CpmJovenesBundle:Invitacion invit' .
											' INNER JOIN invit.proyecto proyecto ' .
											' INNER JOIN invit.instanciaEvento inst ' .
											' INNER JOIN inst.evento event'.
											' WHERE event = :ev)')->setParameter('ev',$ev);
			} else {
					$qb	->innerJoin('p.invitaciones','inv')
					->innerJoin('inv.instanciaEvento','instancia')
					->innerJoin('instancia.evento','ev')->andWhere('ev = :ev')->setParameter('ev',$ev);
				//$qb->andWhere('p.tipoInstitucion is NULL');
								 
			}		
		}
		
		
		$instanciaEvento = $data->getInstanciaEventoFilter(); 
		if ($iev = $instanciaEvento->getInstanciaEvento()) {  
					$qb	->innerJoin('p.invitaciones','invitaciones2')
					->innerJoin('invitaciones2.instanciaEvento','instancia2')
					->andWhere('instancia2 = :iev')->setParameter('iev',$iev);
		}
		

		$estado = $data->getEstadoFilter();
		if ($estado) {
				if ($estado->getConArchivo() || $estado->getYaEvaluado() || $estado->getVigente() || $estado->getNota() || $estado->getAprobado()) {
					
					$qb	->leftJoin('p.estadoActual','est');
						
					if ($archivo=$estado->getConArchivo()) {
						if ($archivo != 1) 
							$qb->andWhere("(est.estado < :estado or (p.estadoActual is null))")->setParameter('estado', ESTADO_PRESENTADO);
						else 
							$qb->andWhere("est.estado >= :estado")->setParameter('estado', ESTADO_PRESENTADO);
					}
					if ($yev= $estado->getYaEvaluado() && (! $estado->getAprobado())) {
						$estados_evaluados = array(ESTADO_APROBADO,ESTADO_APROBADO_CLINICA,ESTADO_DESAPROBADO,ESTADO_REHACER, ESTADO_FINALIZADO);
						$estados_sin_evaluar = array(ESTADO_INICIADO); //ESTADO_ANULADO
						
						if ($yev == 1)
							$qb->andWhere("est.estado between :estado_aprobado AND :estado_finalizado")
											->setParameter('estado_aprobado', ESTADO_APROBADO)
											->setParameter('estado_finalizado', ESTADO_FINALIZADO);						
						else
							$qb->andWhere("( (est.estado = :estado_presentado) or (est.estado = :estado_iniciado) or (p.estadoActual is null) )")
								->setParameter('estado_presentado', ESTADO_PRESENTADO)->setParameter('estado_iniciado', ESTADO_INICIADO);
					}
					
					if ($nota = $estado->getNota()) {
						if ($nota == ESTADO_APROBADO_Y_APROBADO_C)
							$qb->andWhere('est.estado in (:aprobado ,  :aprobado_c )')->setParameter('aprobado',ESTADO_APROBADO)->setParameter('aprobado_c',ESTADO_APROBADO_CLINICA);
						else	
							$qb->andWhere('est.estado = :nota')->setParameter('nota',$nota);
					}

//					if ($aprobado = $estado->getAprobado()) { 
//						$qb->andWhere('est.estado in (:aprobado ,  :aprobado_c )')->setParameter('aprobado',ESTADO_APROBADO)->setParameter('aprobado_c',ESTADO_APROBADO_CLINICA);
//					} 
					  
					   	
						if ($vig = $estado->getVigente()) {  					
							switch ( $vig ) {
								case 1: //no anulados, o sea vigentes
										$qb->andWhere("(est.estado != :est or p.estadoActual is null)")->setParameter('est', ESTADO_ANULADO);	
									break;
								case 2: //anulados
									$qb->andWhere("est.estado = :est")->setParameter('est', ESTADO_ANULADO);
									break; 	
								default:
									break;
							} //switch
						  }	// if vig
					  
				}
		}
		return $qb;
	}

	function findAllInArray($ids = array ()) {
		$qb = $this->getEntityManager()->createQueryBuilder();

		$qb->add('select', 'p')->add('from', 'CpmJovenesBundle:Proyecto p')->andWhere("p.id in (:proyectos)")->setParameter("proyectos", array_values($ids));

		return $qb->getQuery();

	}
}