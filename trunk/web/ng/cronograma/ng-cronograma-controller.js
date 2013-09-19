function TandaShowCtrl($scope, $routeParams, Tanda) {
	$scope.debug_console="Mostrando tanda";
	$scope.tanda=get_tanda_demo();
//	$scope.tanda = Tanda.get({tandaId: $routeParams.tandaId});
	
	$scope.css_cronograma_height=screen.height-150;
    $scope.css_dia_height=function(){
        var alto_dia=$scope.css_cronograma_height / $scope.tanda.dias.length;
        return "height:"+alto_dia+"px";
    }; 
    $scope.css_dias_height=function(){
        return "height:"+($scope.css_cronograma_height)+"px";
    };

	$scope.css_bloque_height = function(bloque) {
		return "min-height:" + bloque.duracion + "px";
	};

	// Determina si un bloque es de presentaciones o simple
	$scope.bloque_tiene_presentaciones = function(bloque) {
		return bloque.presentaciones !== false;
	};
  
    $scope.drop_options={
    };
    $scope.bloque_drop_options={
        accept:'.badge-presentacion',
        tolerance:'touch',
        hoverClass:'badge-presentacion-hover'
    };
    $scope.jquoui_drag_options= {
        revert:'invalid',
        cursor: 'move', 
        cursorAt:{left:50, top:10},
        helper:'clone'
    };
}

function TandaListCtrl($scope, Tanda) {
	$scope.tandas=[{nombre:'tanda 1', id:1},{nombre:'tanda 2', id:2},{nombre:'tanda 3', id:3}];
//	$scope.tanda = Tanda.get({tandaId: $routeParams.tandaId});
}

function BloqueNewCtrl($scope, $routeParams, $location, Bloque){
	$scope.textButton = "Crear";
	$scope.bloque = new Bloque({auditorio_dia_id:$routeParams.auditorioDiaId, tipo:true});
	$scope.bloque.hola();
	$scope.newBloque = function(){
		$scope.bloque.$save();
	    //TODO agregar al auditorio_dia?
		console.log("Se creó el bloque con id="+$scope.bloque.id);
	     //   $location.url("/");
	}
}

function BloqueEditCtrl($scope, $routeParams,$location, Bloque){
	$scope.textButton = "Editar";
	$scope.bloque = Bloque.get({bloqueId:$routeParams.bloqueId});
	$scope.editBloque = function(){
		$scope.bloque.$save();
	    console.log("Se editó el bloque con id="+$scope.bloque.id);
	    //$location.url("/");
	    }
	
}

/*
/*
 $scope.resetear_cronograma=function(){
    console.log("Se reinicia el cronograma");
    $scope.tanda.presentaciones_libres=$scope.tanda.presentaciones;
    $scope.tanda.dias.forEach(function(dia) {
      dia.auditorios_dia.forEach(function(auditorio_dia) {
        auditorio_dia.bloques.forEach(function(bloque) {
          if (bloque.presentaciones !== false)
            bloque.presentaciones = [];
        });
      });
    });
  };
*/