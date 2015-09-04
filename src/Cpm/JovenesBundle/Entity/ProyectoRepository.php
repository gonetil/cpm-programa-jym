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

    /*
     * recibe un array o un ArrayCollecion y retorna un array con los IDs de los items
     */
    private function ids($array_collection) {
        $datos = (!is_array($array_collection)) ? $array_collection->toArray() : $array_collection;
        return array_map(function($elem) { return $elem->getId(); },array_values($datos));
    }

	public function filterQuery(ProyectoFilter $data, $ciclo_activo, $sort_field = null, $sort_order) {
		
		$qb = $this->createQueryBuilder('p')->innerJoin("p.coordinador", "coordinador")->innerJoin("p.escuela","e");
		
		if ($sort_field) {
			$field = (isset(ProyectoRepository::$sort_criteria[$sort_field]))?ProyectoRepository::$sort_criteria[$sort_field]:ProyectoRepository::$sort_criteria['id'];
			$qb->orderBy($field,$sort_order);
		}
		
		$cicloFilter = $data->getCicloFilter();
		if  ($ciclo = $cicloFilter->getCiclo()) { 
			$qb->andWhere('p.ciclo = :ciclo')->setParameter('ciclo', $ciclo);
		} else {
			$qb->andWhere('p.ciclo = :ciclo')->setParameter('ciclo', $ciclo_activo);
		}

		$usuarioFilter = $data->getUsuarioFilter();
		if ($aniosParticipo = $usuarioFilter->getAniosParticipo()) {
			$ors = $qb->expr()->orX();
			foreach ( $aniosParticipo as $index => $anio ) {
				$ors->add($qb->expr()->like('coordinador.aniosParticipo',"'%$anio%'"));       
			}
			$qb->andWhere($ors);
			
			if ($usuarioFilter->getPorPrimeraVez()) {  //me aseguro que no figuren otros años, hasta encontrar el menor 
					for($i=2002;$i<date('Y');$i++) {
						if (!in_array($i,$aniosParticipo)) { 
							$qb->andWhere(" coordinador.aniosParticipo not like '%$i%'" );
						} else { 
							break; 
						}
					}
			}  
		}

		if ($primeraVezQueParticipa = $usuarioFilter->getPrimeraVezQueParticipa()) {  //este es el select
				if ($primeraVezQueParticipa == 1)
	       			$qb->andWhere(" ( coordinador.aniosParticipo like '{}' or coordinador.aniosParticipo like '[]' or coordinador.aniosParticipo is NULL )");
	       		else	
		       		$qb->andWhere(" not ( coordinador.aniosParticipo like '{}' or coordinador.aniosParticipo like '[]' or coordinador.aniosParticipo is NULL )");
		}
		
		

		if ($data->getCuentanConNetbook()) {
			$pv = ($data->getCuentanConNetbook() != 1)?0 : 1;
			$qb->andWhere('p.cuentanConNetbook = :ccn')->setParameter('ccn', $pv);
		}
		
		if ($data->getCuentanConPlataformaVirtual()) {
			$pv = ($data->getCuentanConPlataformaVirtual() != 1)?0 : 1;
			$qb->andWhere('p.cuentanConPlataformaVirtual = :ccpv')->setParameter('ccpv', $pv);
		}
		


		if ($data->getEsPrimeraVezAlumnos()) {
			$pv = ($data->getEsPrimeraVezAlumnos() != 1)?0 : 1;
			$qb->andWhere('p.esPrimeraVezAlumnos = :pva')->setParameter('pva', $pv);
		}
		
		if ($data->getEsPrimeraVezEscuela()) {
			$pv = ($data->getEsPrimeraVezEscuela() != 1)?0 : 1;
			$qb->andWhere('p.esPrimeraVezEscuela = :pve')->setParameter('pve', $pv);
		}
		
		if ($producciones = $data->getProduccionesFinales()and (count($producciones)))  {
           $qb->andWhere('p.produccionFinal IN (:pf)')->setParameter('pf', $this->ids($producciones));
        }

        if ($temas = $data->getTemasPrincipales()and (count($temas))) {
            $qb->andWhere('p.temaPrincipal IN (:temas)')->setParameter('temas', $this->ids($temas) );
        }

        if ($ejes = $data->getEjes() and (count($ejes))) {
           $qb->andWhere('p.eje IN (:ejes)')->setParameter('ejes', $this->ids($ejes));
        }

		if ($color = $data->getColor()) {
			$qb->andWhere('p.color like :color')->setParameter('color', $color);
		}

		if ($transporte = $data->getTransporte()) {
			$qb->andWhere('p.transporte like :transporte')->setParameter('transporte', $transporte);
		}


		if ($escuela = $data->getEscuelaFilter()) {

			$tiene_escuela = false;
			if (($localidades = $escuela->getLocalidades()) && (count($localidades)) ) {

				$tiene_escuela = true;
				$qb->innerJoin("e.localidad", 'l')->andWhere('l in (:localidades)')->setParameter('localidades', $this->ids($localidades));
			}
			elseif (($distritos = $escuela->getDistritos()) && (count($distritos))) {
				$tiene_escuela = true;
				$qb->innerJoin("e.localidad", 'l')
                    ->innerJoin("l.distrito", 'd')
                    ->andWhere('d in (:distritos)')->setParameter('distritos', $this->ids($escuela->getDistritos()));
			}
			elseif (($regiones = $escuela->getRegiones()) || $escuela->getRegionDesde() || $escuela->getRegionHasta()) //la region solo se considera si no se eligio ni la localidad ni el distrito
			{
				$tiene_escuela = true;
				$qb->innerJoin("e.localidad", 'l')->innerJoin("l.distrito", 'd')->innerJoin("d.region", 'r');

				if (count($regiones))
					$qb->andWhere('r in (:regiones)')->setParameter('regiones', $this->ids($escuela->getRegiones()));
				else {
					if ($escuela->getRegionDesde())
						$qb->andWhere('r.id >= :regionDesde')->setParameter('regionDesde', $escuela->getRegionDesde());
					if ($escuela->getRegionHasta())
						$qb->andWhere('r.id <= :regionHasta')->setParameter('regionHasta', $escuela->getRegionHasta());
				};
			}

			if ($escuela->getOtroTipoInstitucion()) {
				$qb->andWhere('e.tipoInstitucion is NULL');
			}
			elseif ($escuela->getTipoInstitucion()) {
				$qb->innerJoin("e.tipoInstitucion", 't')->andWhere('t = :tipoInstitucion')->setParameter('tipoInstitucion', $escuela->getTipoInstitucion());
			}

			if ($contextoEncierro = $escuela->getContextoEncierro()) {
				$ctx = ($contextoEncierro != 1)?0 : 1;
				$qb->andWhere('e.contextoEncierro = :contexto')->setParameter('contexto',$ctx);
			}

			if (trim($escuela->getNombre()) != "") {
				$escuelaSel = trim($escuela->getNombre());

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

			$qb->andWhere("p.deQueSeTrata like :dqst")->setParameter("dqst", "%".(trim($data->getDeQueSeTrata()) ."%"));
		}

        if (trim($data->getTitulo()) != "") {

            $qb->andWhere("p.titulo like :titulo")->setParameter("titulo", "%".(trim($data->getTitulo()) ."%"));
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
				if ($estado->getConArchivo() || $estado->getYaEvaluado() || $estado->getVigente() || $estado->getNota() || $estado->getAprobado() || $estado->getCorreoEnviado() ) {
					
					$qb	->leftJoin('p.estadoActual','est');
						
					if ($archivo=$estado->getConArchivo()) {
						if ($archivo != 1) 
							$qb->andWhere("(est.estado < :estado or (p.estadoActual is null))")->setParameter('estado', ESTADO_PRESENTADO);
						else 
							$qb->andWhere("est.estado >= :estado")->setParameter('estado', ESTADO_PRESENTADO);
					}
					if (( $yev= $estado->getYaEvaluado()) && (! $estado->getAprobado())) {

						$estados_evaluados = array(ESTADO_APROBADO,ESTADO_APROBADO_CLINICA,ESTADO_DESAPROBADO,ESTADO_REHACER, ESTADO_FINALIZADO);
						$estados_sin_evaluar = array(ESTADO_INICIADO, ESTADO_PRESENTADO); //ESTADO_ANULADO
						
						if ($yev == 1) { 
							$qb->andWhere("est.estado between :estado_aprobado AND :estado_finalizado")
											->setParameter('estado_aprobado', ESTADO_APROBADO)
											->setParameter('estado_finalizado', ESTADO_FINALIZADO);						
						}
						else { 
							$qb->andWhere("( (est.estado = :estado_presentado) or (est.estado = :estado_iniciado) or (p.estadoActual is null) )")
								->setParameter('estado_presentado', ESTADO_PRESENTADO)->setParameter('estado_iniciado', ESTADO_INICIADO);
						}
					}
					
					if ($correoEnviado = $estado->getCorreoEnviado()) {
						if ($correoEnviado == 1) { // si
							$qb->innerJoin("est.correoEnviado",'correoEnviado');
						} else { 
							$qb->andWhere("est.correoEnviado is null");
						}
					}

               	if ($notas = $estado->getNotas() and (count($notas))) {
                        $qb->andWhere('est.estado IN (:notas)')->setParameter('notas', $notas );
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