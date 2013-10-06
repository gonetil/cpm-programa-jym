function TandaShowCtrl($rootScope,$scope, $routeParams, Tanda, Logger) {

	$rootScope.tanda = Tanda.get({tandaId: $routeParams.tandaId},function(tanda){
		tanda.initialize();
	});

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
    	//}elseif (o instanceOf Bloque){
    	//}
    	return clases;
    }

    //////////////////////////////////////////
    
    $scope.presentacionDropped=function(event, ui){
    	var presentacionId = ui.draggable.data("presentacion");
    	var origen = ui.draggable.data("bloque");
    	var destino = $(event.target).data("bloque");
    	var presentacion = new Presentacion({id:presentacionId});
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
	$rootScope.tanda = null;
}
function TandaResetearPresentacionesCtrl($rootScope,$scope, $location, $routeParams, Tanda, Logger) {
	var tandaId = $routeParams.tandaId;
	if (!$rootScope.tanda){
		Logger.debug("La tanda actual no estaba, la levanto nuevamente");
		$rootScope.tanda = Tanda.get({tandaId: $routeParams.tandaId},function(tanda){
			tanda.initialize();
		});
	}
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
	
	$scope.modo="best";
	
	if (!$rootScope.tanda){
		Logger.debug("La tanda actual no estaba, la levanto nuevamente");
		$rootScope.tanda = Tanda.get({tandaId: $routeParams.tandaId},function(tanda){
			tanda.initialize();
		});
	}
	$scope.distribuirPresentacionesLibres=function(){
		
		
		if ($rootScope.tanda.presentaciones_libres.length ==0){
			Logger.info("No quedan presentaciones libres para distribuir en el cronograma");
			return;
		}
		
		var movidas = $rootScope.tanda.distribuirPresentaciones($scope.modo);
		if (movidas == 0){
			Logger.info("No se pudo distribuir ninguna presentación en el cronograma");
			
		}else{
			Logger.debug("Se distribuyeron con éxito "+movidas +" presentaciones, ahora comenzamos el guardado ...");
			
			var tandaDTO=new Tanda({id:tandaId});
			tandaDTO.presentaciones = $rootScope.tanda.getPresentacionesConBloqueDTO();
			tandaDTO.$savePresentaciones(
				function(entity){Logger.success("Se distribuyeron con éxito "+movidas +" presentaciones")},
				function(error){Logger.error("Ocurrió un error al guardar la redistribución de la tanda"); Logger.error(error.data)}
			);
		}
		
		$location.url("/tanda/"+$routeParams.tandaId);
	}
	
}

//DIA
function DiaNewCtrl($scope, $routeParams, $location, Dia, Logger){
	$scope.dia = new Dia({tanda:$routeParams.tandaId});
	$scope.dia.$save(function(){Logger.success("Se agregó el dia "+$scope.dia.numero)},Logger.error);
	$location.url("/tanda/"+$routeParams.tandaId);
}
function DiaRemoveCtrl($scope, $routeParams, $location, Dia, Logger){
	$scope.confirmMessage = "Esta seguro que desea eliminar el día "+$routeParams.diaId;
	$scope.dia = new Dia({id:$routeParams.diaId});
	$scope.confirmOk= function(){
		$scope.dia.$remove(Logger.success, Logger.error);
		history.back();
	}
}
function DiaDuplicarCtrl($scope, $routeParams, $location, Dia, Logger){
	$scope.dia = new Dia({id:$routeParams.diaId});
	$scope.dia.$duplicar({dia_destino:$routeParams.diaDestino}, 
			function(){Logger.success("Se duplicó el dia "+$scope.dia.id)},
			function(error){Logger.error(error.data)}
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
				function(ad){Logger.success("Se agregó el auditorio '"+ad.auditorio.nombre+ "' al dia"); Logger.debug(ad);},
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
				function(ad){Logger.success("Se guardó satistfactoriamente el auditorio del dia"); Logger.debug(ad);},
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
				function(b){Logger.success("Nuevo bloque ("+b.id+") creado con éxito")},
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
				function(b){Logger.debug("Bloque ("+b.id+") actualizado")},
				function(error){Logger.error("Se produjo un error al guardar el bloque ");Logger.error(error.data);}
		);
		history.back();
    }
}
function BloqueMoverCtrl($scope, $routeParams, $location, Bloque, Logger){
	var bloqueDTO=new Bloque({id: $routeParams.bloqueId, posicion: $routeParams.posicion}); 
	bloqueDTO.$save({}, 
				function(){Logger.success("Se movio el bloque " +bloqueDTO.id);}, 
				function(error){Logger.error(error.data)}
	);
	
	history.back();
}

function BloqueRemoveCtrl($scope, $routeParams, $location, Bloque, Logger){
	$scope.confirmMessage = "Esta seguro que desea eliminar el Bloque?";
	$scope.confirmMessage = "Se liberarán presentaciones asociadas";
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

function PresentacionNewCtrl($scope, $routeParams, $location, Presentacion,Produccion, EjeTematico, AreaReferencia, Logger){
	$scope.textButton = "Crear";

	//cargo los valores por default para la presentacion
	$scope.presentacion = new Presentacion({tanda:$routeParams.tandaId, duracion:15, personasConfirmadas:0});
	$scope.ejesTematicos=EjeTematico.query();
	$scope.areasReferencia=AreaReferencia.query();
	$scope.producciones=Produccion.query();
	$scope.savePresentacion=function(){
		$scope.presentacion.$save(
				function(entity){Logger.success("Nueva presentacion ("+entity.id+") creada con éxito")},
				function(error){Logger.error("Error al crear una presentacion"); Logger.error(error.data)}
		);
		history.back();
	}
}

function PresentacionEditCtrl($scope, $routeParams, $location, Presentacion,Produccion,	EjeTematico, AreaReferencia, Tanda, Logger){
	$scope.textButton = "Editar";
	$scope.presentacion = Presentacion.get(
			{presentacionId:$routeParams.presentacionId},
			function(entity){Logger.debug("Presentacion ("+entity.id+") recuperada con éxito")},
			function(error){
				Logger.error("No se pudo recuperar la presentacion "+$routeParams.presentacionId);
				Logger.error(error.data); 
				history.back();
			}
	);
	$scope.ejesTematicos=EjeTematico.query();
	$scope.areasReferencia=AreaReferencia.query();
	$scope.producciones=Produccion.query();
	$scope.tandas=Tanda.query();
	$scope.savePresentacion = function(){
		$scope.presentacion.$save(
				function(entity){Logger.success("Presentacion ("+entity.id+") actualizada con éxito")},
				function(error){Logger.error("Error al guardar la presentacion "+pid); Logger.error(error.data);}
		);
		history.back();
    }
}

function PresentacionRemoveCtrl($scope, $routeParams, $location, Presentacion, Logger){
	$scope.confirmMessage = "Esta seguro que desea eliminar la presentacion? ";
	$scope.descriptionMessage = "Esta operación no se podrá deshacer";
	var pid = $routeParams.presentacionId;
	$scope.presentacion = new Presentacion({id:pid});
	$scope.confirmOk= function(){
		$scope.presentacion.$remove(
				function(message){Logger.debug("Presentacion "+pid+" eliminada"); Logger.success(message)}, 
				function(error){Logger.error("Error al eliminar la presentacion "+pid); Logger.error(error.data);}
		);
		history.back();
	}
}