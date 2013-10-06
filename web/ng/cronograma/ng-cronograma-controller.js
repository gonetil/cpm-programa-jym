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
    

    //////////////////////////////////////////
    
    $scope.presentacionDropped=function(event, ui){
    	var presentacionId = ui.draggable.data("presentacion");
    	var origen = ui.draggable.data("bloque");
    	var destino = $(event.target).data("bloque");
    	if (origen == destino){
    		Logger.debug("Ignoro el dnd de la presentacion porque el destino es == al origen ");
    		//FIXME aca no deberíamos mirar el tema de la posicion de la presentacion dentro del bloque?
    		return;
    	}
    	//?"al bloque "+ origen.data("bloque"):" presentaciones libres"));
    	var presentacion = new Presentacion({id:presentacionId, bloque:(destino?destino:'')});
    	presentacion.$save(function(data){
    		Logger.success("Se movió la presentación "+presentacion.id);
    		Logger.debug("Saco la presentación "+presentacionId + " de "+ (origen?"al bloque "+origen:"a presentaciones libres") + " y la paso "+ (destino?"al bloque "+destino:"a presentaciones libres"));
    		//Logger.debug(data);
   		}, Logger.error);
    }
}

function TandaListCtrl($rootScope, $scope, Tanda) {
	$scope.tandas = Tanda.query();
	$rootScope.tanda = null;
}

function TandaDistribuirPresentacionesCtrl($rootScope,$scope, $location, $routeParams, Tanda, Logger) {

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
			return;
		}else{
			Logger.debug("Se distribuyeron con éxito "+movidas +" presentaciones, ahora comenzamos el guardado ...");
			
			var tandaDTO=new Tanda({id:$routeParams.tandaId});
			tandaDTO.presentaciones = $rootScope.tanda.getPresentacionesConBloqueDTO();
			tandaDTO.$savePresentaciones(
				function(entity){Logger.debug("Se distribuyeron con éxito "+movidas +" presentaciones")},
				function(error){Logger.error(error.data)}
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
	$scope.dia.$duplicar({dia_destino:$routeParams.diaDestino}, function(){Logger.success("Se duplicó el dia "+$scope.dia.id)},Logger.error);
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
		$scope.auditorioDia.$save(function(ad){Logger.success("Se guardó satistfactoriamente el auditorio del dia"); Logger.debug(ad);},Logger.error);
		history.back();
	}
}

function AuditorioDiaRemoveCtrl($scope, $routeParams, $location, AuditorioDia, Logger){
	$scope.confirmMessage = "Esta seguro que desea eliminar el auditorio día? Se perdera la información de sus bloques y presentaciones asociadas (las presentaciónes NO se eliminarán)";
	$scope.auditorioDia = new AuditorioDia({id:$routeParams.auditorioDiaId});
	$scope.confirmOk= function(){
		$scope.auditorioDia.$remove(Logger.success, Logger.error);
		history.back();
	}
}

//BLOQUE
function BloqueNewCtrl($scope, $routeParams, $location, Bloque,EjeTematico, AreaReferencia, Logger){
	$scope.textButton = "Crear";
	$scope.bloque = new Bloque({auditorioDia:$routeParams.auditorioDiaId,tienePresentaciones:true, duracion:15});
	$scope.ejesTematicos=EjeTematico.query();
	$scope.areasReferencia=AreaReferencia.query();
	$scope.saveBloque=function(){
		$scope.bloque.$save(function(b){Logger.success("Nuevo bloque ("+b.id+") creado con éxito")},Logger.error);
		history.back();
	}
}

function BloqueEditCtrl($scope, $routeParams, $location, Bloque,EjeTematico, AreaReferencia, Logger){
	$scope.textButton = "Editar";
	$scope.bloque = Bloque.get({bloqueId:$routeParams.bloqueId});
	$scope.ejesTematicos=EjeTematico.query();
	$scope.areasReferencia=AreaReferencia.query();
	$scope.saveBloque = function(){
		$scope.bloque.$save(function(b){Logger.success("Bloque ("+b.id+") actualizado")},Logger.error);
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
	$scope.confirmMessage = "Esta seguro que desea eliminar el Bloque? ";
	var bid = $routeParams.bloqueId;
	$scope.bloque = new Bloque({id:bid});
	$scope.confirmOk= function(){
		$scope.bloque.$remove(function(){Logger.success("Bloque ("+b.id+") eliminado")}, Logger.error);
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
				function(error){Logger.error(error.data)}
		);
		history.back();
	}
}

function PresentacionEditCtrl($scope, $routeParams, $location, Presentacion,EjeTematico, AreaReferencia, Logger){
	$scope.textButton = "Editar";
	$scope.presentacion = Presentacion.get({presentacionId:$routeParams.presentacionId});
	$scope.ejesTematicos=EjeTematico.query();
	$scope.areasReferencia=AreaReferencia.query();
	$scope.producciones=Produccion.query();
	$scope.savePresentacion = function(){
		$scope.presentacion.$save(
				function(entity){Logger.success("Nueva presentacion ("+entity.id+") creada con éxito")},
				function(error){Logger.error(error.data)}
		);
		history.back();
    }
}

function PresentacionRemoveCtrl($scope, $routeParams, $location, Presentacion, Logger){
	$scope.confirmMessage = "Esta seguro que desea eliminar el Bloque? ";
	var bid = $routeParams.bloqueId;
	$scope.bloque = new Bloque({id:bid});
	$scope.confirmOk= function(){
		$scope.bloque.$remove(function(){Logger.success("Bloque ("+b.id+") eliminado")}, Logger.error);
		history.back();
	}
}