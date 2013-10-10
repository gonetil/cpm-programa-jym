function TandaShowCtrl($rootScope,$scope, $routeParams, Tanda, Logger) {
	$rootScope.getTanda();
	var tanda = $rootScope.getTanda($routeParams.tandaId);
	
	$scope.presentacion_droppable={
			multiple:true,
			placeholder:false,
			onDrop:'presentacionDropped'
	};
    $scope.presentacion_droppable_options={
        accept:'.badge-presentacion',
        tolerance:'intersect',
        hoverClass:'badge-presentacion-hover'
    };
    $scope.presentacion_draggable_options= {
        revert:'invalid',
        cursor: 'move', 
        cursorAt:{left:50, top:10},
        helper:'clone'
    };
    
    $scope.get_css_styles=function(o){
    	var styles= "";
    	//if (o instanceOf Presentacion){
    	styles+="height:{{o.duracion}}px;";
    	//}elseif (o instanceOf Bloque){
    	//styles+="min-height:{{o.duracion}}px;";
    	//}
    	return styles;
    }
    $scope.get_css_classes=function(o){
    	var clases= "";
    	//if (o instanceOf Presentacion){
    	if (o.valoracion == 'Muy bueno') 
    		clases+=' presentacion-muy_buena';
    	
    	if (o.tipo == 'externa')
    		clases+=' presentacion-externa';
    	//}elseif (o instanceOf Bloque){
    	//}
    	return clases;
    }
    $scope.icons=function(){
    	return "icon-star icon-star";
    }
    //////////////////////////////////////////
    
    $scope.presentacionDropped=function(event, ui){
    	var presentacionId = ui.draggable.data("presentacion");
    	var origen = ui.draggable.data("bloque");
    	var destino = $(event.target).data("bloque");
    	var presentacion = new Presentacion({id:presentacionId});

/*    	
 * 
 * 
 * 
 * 
	event{
		cancelable: true,
		currentTArget: docuemnt
		delegateTArget: docuemnt
		exclusive: undefined
		data:null
		handleObj
		originalEvent: mouseEvent
		srcElement, target, toElement. 
	}


ui{
  
    		draggable:{
    			data('presentacion') == int
    			data('bloque') == int
    		}
    		helper:{
    			context: elementoHTML
    		}
    		position: {top:'', left: ''}
    		offset: {top:'', left: ''}
    	}
    	*/
    	if (origen == destino){
			Logger.debug("Ignoro el move de la presentacion porque el destino es == al origen ");
			//FIXME aca no deberíamos mirar el tema de la posicion de la presentacion dentro del bloque?
			return;
		}
    	presentacion.setBloquePersistent(destino);
    }
}

function TandaListCtrl($rootScope, $scope, Tanda) {
	$scope.tandas = Tanda.query();
	$rootScope.getTanda();
}
function TandaResetearPresentacionesCtrl($rootScope,$scope, $location, $routeParams, Tanda, Logger) {
	var tandaId = $routeParams.tandaId;
	//var tanda = $rootScope.getTanda(tandaId);

	$scope.confirmMessage="Esta seguro que desea reinicializar esta tanda?";
	$scope.descriptionMessage="Al reinicializar una tanda se quitan todas las presentaciones de sus bloques y quedan libres. No se pierden los días, auditorios ni bloques."
	$scope.confirmButton='Reinicializar';
	$scope.confirmOk=function(){
		
		var tandaDTO=new Tanda({id:tandaId});
		tandaDTO.$resetPresentaciones(
			function(entity){Logger.success("Se reinicializó la tanda"); $location.url("/tanda/"+$routeParams.tandaId);},
			function(error){Logger.error("Ocurrió un error al reinicializar la tanda"); Logger.error(error.data)}
		);
	}
}

function TandaDistribuirPresentacionesCtrl($rootScope,$scope, $location, $routeParams, Tanda, Logger) {
	
	var tandaId = $routeParams.tandaId;
	var tanda = $rootScope.getTanda(tandaId);
	$scope.modo="best";
	
	$scope.distribuirPresentacionesLibres=function(){
		
		if (tanda.presentaciones_libres.length ==0){
			Logger.info("No quedan presentaciones libres para distribuir en el cronograma");
			$location.url("/tanda/"+tandaId);
			return;
		}
		
		var movidas = tanda.distribuirPresentaciones($scope.modo);
		if (movidas == 0){
			Logger.info("No se pudo distribuir ninguna presentación en el cronograma");
		}else{
			Logger.debug("Se distribuyeron con éxito "+movidas +" presentaciones, ahora comenzamos el guardado ...");
			
			var tandaDTO=new Tanda({id:tandaId});
			tandaDTO.presentaciones = tanda.getPresentacionesConBloqueDTO();
			tandaDTO.$savePresentaciones(
				function(entity){Logger.success("Se distribuyeron con éxito "+movidas +" presentaciones")},
				function(error){Logger.error("Ocurrió un error al guardar la redistribución de la tanda"); Logger.error(error.data)}
			);
		}
		
		$location.url("/tanda/"+tandaId);
	}
}

//DIA
function DiaNewCtrl($scope, $routeParams, $location, Dia, Logger){
	$scope.dia = new Dia({tanda:$routeParams.tandaId});
	$scope.dia.$save(
			function(entity){Logger.debug("Se agregó el dia "+entity.numero)},
			function(error){Logger.error("Se produjo un error al tratar de crear el dia");Logger.error(error.data);}
		);
	$location.url("/tanda/"+$routeParams.tandaId);
}
function DiaRemoveCtrl($scope, $routeParams, $location, Dia, Logger){
	var diaId = $routeParams.diaId;
	$scope.confirmMessage = "Esta seguro que desea eliminar el día "+diaId;
	$scope.dia = new Dia({id:diaId});
	$scope.confirmOk= function(){
		$scope.dia.$remove(
				function(message){Logger.debug("Se eliminó el dia "+diaId);Logger.success(message);},
				function(error){Logger.error("Se produjo un error al tratar de eliminar el dia "+diaId);Logger.error(error.data);}
			);
		history.back();
	}
}
function DiaDuplicarCtrl($scope, $routeParams, $location, Dia, Logger){
	var diaId = $routeParams.diaId;
	$scope.dia = new Dia({id:diaId});
	$scope.dia.$duplicar({dia_destino:$routeParams.diaDestino}, 
			function(entity){Logger.success("Se duplicó el dia "+diaId)},
			function(error){Logger.error("Error al tratar de eliminar el dia"+diaId);Logger.error(error.data);}
	);
	history.back();
}
//AUDITORIO_DIA
function AuditorioDiaNewCtrl($scope, $routeParams, $location, Auditorio, AuditorioDia, Logger){
	$scope.textButton = "Agregar";
	$scope.auditorios=Auditorio.query();
	$scope.auditorioDia = new AuditorioDia({dia:$routeParams.diaId});
	$scope.saveAuditorioDia=function(){
		//$scope.auditorioDia.auditorio=$scope.auditorioDia.auditorio.id;
		$scope.auditorioDia.$save(
				function(ad){Logger.debug("Se agregó el auditorio "+ad.auditorio.nombre+ " al dia"); },
				function(error){Logger.error("Ocurrió un error al tratar de agregar el auditorio al dia"); Logger.error(error.data);}
		);
		history.back();
	}
}

function AuditorioDiaEditCtrl($scope, $routeParams, $location, Auditorio, AuditorioDia, Logger){
	$scope.auditorioDia = AuditorioDia.get({auditorioDiaId:$routeParams.auditorioDiaId});
	$scope.textButton = "Modificar";

	$scope.auditorios=Auditorio.query();
	$scope.saveAuditorioDia=function(){
		$scope.auditorioDia.$save(
				function(ad){Logger.debug("Se guardó satistfactoriamente el auditorio "+ad.auditorio.nombre+ " del dia"); },
				function(error){Logger.error("Ocurrió un error al tratar de guardar el auditorio del dia"); Logger.error(error.data)}
		);
		history.back();
	}
}

function AuditorioDiaRemoveCtrl($scope, $routeParams, $location, AuditorioDia, Logger){
	$scope.confirmMessage = "Esta seguro que desea eliminar el auditorio día? Se perdera la información de sus bloques y presentaciones asociadas (las presentaciónes NO se eliminarán)";
	$scope.auditorioDia = new AuditorioDia({id:$routeParams.auditorioDiaId});
	$scope.confirmOk= function(){
		$scope.auditorioDia.$remove(
				function(message){Logger.debug("AuditorioDia "+$routeParams.auditorioDiaId+ " eliminado"); Logger.success(message);},
				function(error){Logger.error("Se produjo un error al tratar de quitar el auditorio del dia");Logger.error(error.data);}
		);
		history.back();
	}
}

//BLOQUE
function BloqueNewCtrl($scope, $routeParams, $location, Bloque,EjeTematico, AreaReferencia, Logger){
	$scope.textButton = "Crear";
	$scope.bloque = new Bloque({auditorioDia:$routeParams.auditorioDiaId,tienePresentaciones:true, duracion:15, nombre: 'Presentaciones', horaInicio: '09:00'});
	$scope.ejesTematicos=EjeTematico.query();
	$scope.areasReferencia=AreaReferencia.query();
	$scope.saveBloque=function(){
		$scope.bloque.$save(
				function(entity){Logger.debug("Nuevo bloque ("+entity.id+") creado con éxito")},
				function(error){Logger.error("Se produjo un error al guardar el bloque ");Logger.error(error.data);}
		);
		history.back();
	}
}

function BloqueEditCtrl($scope, $routeParams, $location, Bloque,EjeTematico, AreaReferencia, Logger){
	$scope.textButton = "Editar";
	$scope.bloque = Bloque.get({bloqueId:$routeParams.bloqueId});
	$scope.ejesTematicos=EjeTematico.query();
	$scope.areasReferencia=AreaReferencia.query();
	$scope.saveBloque = function(){
		$scope.bloque.$save(
				function(entity){Logger.debug("Bloque ("+entity.id+") actualizado")},
				function(error){Logger.error("Se produjo un error al guardar el bloque ");Logger.error(error.data);}
		);
		history.back();
    }
}

function BloqueRemoveCtrl($scope, $routeParams, $location, Bloque, Logger){
	$scope.confirmMessage = "Esta seguro que desea eliminar el Bloque?";
	$scope.confirmDescription = "Se liberarán presentaciones asociadas";
	var bid = $routeParams.bloqueId;
	$scope.bloque = new Bloque({id:bid});
	$scope.confirmOk= function(){
		$scope.bloque.$remove(
			function(message){Logger.debug("Bloque ("+b.id+") eliminado");Logger.success(message);}, 
			function(error){Logger.error("Error al eliminar el bloque "+b.id); Logger.error(error.data);}
		);
		history.back();
	}
}

//PRESENTACION (EXTERNA)

function PresentacionNewCtrl($scope, $routeParams, $location, Presentacion,Produccion, EjeTematico, Provincia, AreaReferencia, Logger){
	$scope.textButton = "Crear";

	//cargo los valores por default para la presentacion
	$scope.presentacion = new Presentacion({tanda:$routeParams.tandaId, tipo:'externa', duracion:15, personasConfirmadas:0, valoracion: "Sin especificar"});
	$scope.ejesTematicos=EjeTematico.query();
	$scope.areasReferencia=AreaReferencia.query();
	$scope.producciones=Produccion.query();
	$scope.provincias=Provincia.query(function(){console.log($scope.provincias)});
	$scope.valoraciones = ["Muy bueno","Bueno","Regular","Sin especificar"];
	$scope.savePresentacion=function(){
		$scope.presentacion.$save(
				function(entity){
					Logger.success("Nueva presentacion externa ("+entity.id+") agregada a la tanda");
					$location.url("/tanda/"+$scope.presentacion.tanda);
				},
				function(error){Logger.error("Error al crear una presentacion"); Logger.error(error.data)}
		);
	}
}

function PresentacionEditCtrl($scope, $routeParams, $location, Presentacion,Produccion,	EjeTematico, Provincia, AreaReferencia, Tanda, Logger){
	$scope.textButton = "Editar";
	var tanda_anterior = null;
	$scope.presentacion = Presentacion.get(
			{presentacionId:$routeParams.presentacionId},
			function(entity){
				tanda_anterior = entity.tanda;
			},
			function(error){
				Logger.error("No se pudo recuperar la presentacion "+$routeParams.presentacionId);
				Logger.error(error.data); 
				history.back();
			}
	);
	$scope.ejesTematicos=EjeTematico.query();
	$scope.areasReferencia=AreaReferencia.query();
	$scope.producciones=Produccion.query();
	$scope.provincias=Provincia.query();
	$scope.valoraciones = ["Muy bueno","Bueno","Regular","Sin especificar"];
	
	$scope.tandas=Tanda.query();
	$scope.savePresentacion = function(){
		$scope.presentacion.$save(
				function(entity){
					Logger.debug("Presentacion ("+entity.id+") actualizada ");
					$location.url("/tanda/"+tanda_anterior);
				},
				function(error){Logger.error("Error al guardar la presentacion "+pid); Logger.error(error.data);}
		);
    }
}

function PresentacionRemoveCtrl($rootScope, $scope, $routeParams, $location, Presentacion, Logger){
	$scope.confirmMessage = "Esta seguro que desea eliminar la presentacion?";
	$scope.confirmDescription = "Esta operación no se podrá deshacer. Si la presentación está asociada a un proyecto del sistema, el proyecto no será modificado. ";
	var pid = $routeParams.presentacionId;
	
	$scope.presentacion = Presentacion.get({presentacionId:$routeParams.presentacionId});
	
	//$scope.presentacion = new Presentacion({id:pid});
	$scope.confirmOk= function(){
		var tanda= $scope.presentacion.tanda;
		$scope.presentacion.$remove(
				function(message){
					Logger.success("Presentación "+pid+" eliminada"); 
					Logger.success(message)
					$location.url("/tanda/"+tanda);
				}, 
				function(error){Logger.error("Error al eliminar la presentacion "+pid); Logger.error(error.data);}
		);
	}
}