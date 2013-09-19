console.log("creamos nuestro modulo llamado cronograma_de_tanda");
//http://docs.angularjs.org/api/angular.Module
var app = angular.module("cronograma", ['ngDragDrop', 'ngResource']);

app.factory('Bloque', function($resource){
	Bloque = $resource('bloque/:bloqueId', {}, {
		//query: {method:'GET', params:{bloqueId:'phones'}, isArray:true}
	});
	//TODO reemplazar hola por getAuditorioDia o algo así 
	Bloque.prototype.hola=function(){alert('hole Bloque'+this.auditorio_dia_id);}; 
	return Bloque;
});
app.factory('Tanda', function($resource){
	Tanda = $resource('tanda/:tandaId', {}, {
		//query: {method:'GET', params:{bloqueId:'phones'}, isArray:true}
	});
	Tanda.prototype.hola=function(){alert('hole');};
	return Tanda;
});

app.run(function ($rootScope) {
	//declaro las variables y funciones globales
    $rootScope.asset = asset;
});

//hacemos el ruteo de nuestra aplicación
app.config(['$routeProvider', function($routeProvider, $rootScope) {
	$routeProvider.
		when('/', {templateUrl: asset('tanda-list.html'), controller: TandaListCtrl}).
		when('/tanda/show/:tandaId', {templateUrl: asset('tanda-show.html'), controller: TandaShowCtrl}).
		when('/auditorio/:auditorioDiaId/bloque/new', {templateUrl: asset('bloque-new.html'), controller: BloqueNewCtrl}).
		when('/auditorio/:auditorioDiaId/bloque/edit/:bloqueId', {templateUrl: asset('bloque-edit.html'), controller: BloqueEditCtrl}).
		otherwise({template: function (){
	    	console.log('Se realiza una redirección a / desde '+location.hash);
	    	return 'Se realiza una redirección a / porque se recibio un path desconocido '+location.hash;
	    }});
	}]
);

function asset(filename){
	//Esta funcion se pone suelta, fuera del $rootScope porque desde la inicialización del routeProvider no se puede 
	// ver $rootScope (dado que aún no fue inicializado)
	var ng_path=BASE_PATH+"/ng/cronograma/";
	return ng_path + filename;
};

get_tanda_demo=function (){

    tanda = {
    nombre:'Tanda 1',
    presentaciones: [
      {
        id: 1,
        titulo: "Proyecto 1",
        escuela: "ENET N4",
        duracion: 15,
        ubicacion: "La Plata",
        area_referencia: "Malvinas Ref",
        eje: "Malvinas Eje",
        tipo_produccion: "Audiovisual"
      },
      {
        id: 2,
        titulo: "Proyecto 2",
        escuela: "ENET N4x2",
        duracion: 25,
        ubicacion: "La Plata",
        area_referencia: "Malvinas Ref",
        eje: "Malvinas Eje",
        tipo_produccion: "Audiovisual"
      },
      {
        id: 3,
        titulo: "Proyecto 3",
        escuela: "ENET N44",
        duracion: 15,
        ubicacion: "La Matanza",
        area_referencia: "Malvinas Ref",
        eje: "Malvinas Eje",
        tipo_produccion: "Audiovisual"
      }
    ],
    presentaciones_libres: [],
    dias: [
      //dia 1
      {
        numero:1,
        auditorios_dia: [
          //auditorio_dia sala 1
          {
        	id: 1,
            titulo: 'Auditorio 1', 
            bloques:[
              {hora_inicio: '09:00', hora_fin: '10:00', duracion: 60, titulo: 'Taller introductorio (Collor Naranja)', descripcion: '', presentaciones:false},
              {hora_inicio: '10:00', hora_fin: '13:00', duracion: 180, titulo: 'Bloque de presentaciones n 1', descripcion: '', presentaciones:[
                {
                  id: 4,
                  titulo: "Proyecto 4",
                  escuela: "ENET N44444",
                  duracion: 15,
                  ubicacion: "La Matanza",
                  area_referencia: "Malvinas Ref",
                  eje: "Malvinas Eje",
                  tipo_produccion: "Audiovisual"
                }
              ]},
              {hora_inicio: '13:00', hora_fin: '15:00', duracion: 120, titulo: 'Almuerzo libre', descripcion: '', presentaciones:false},
              {hora_inicio: '15:00', hora_fin: '18:00', duracion: 180, titulo: 'Bloque de presentaciones n 2', descripcion: '', presentaciones:[]}
            ]
          },
          
          //auditorio_dia sala 2
          {
        	  id: 2,
              titulo: 'Auditorio 2', 
            bloques:[
              {hora_inicio: '09:00', hora_fin: '10:00', titulo: 'Taller introductorio (Collor Azul)', descripcion: '', presentaciones:false},
              {hora_inicio: '10:00', hora_fin: '13:00', titulo: 'Bloque de presentaciones n 3', descripcion: '', presentaciones:[]},
              {hora_inicio: '13:00', hora_fin: '15:00', titulo: 'Almuerzo libre (Azul)', descripcion: '', presentaciones:false},
              {hora_inicio: '15:00', hora_fin: '18:00', titulo: 'Bloque de presentaciones n 4', descripcion: '', presentaciones:[]}
            ]
          }
        ]
      },
      //dia 2
      {
        numero:2,
        auditorios_dia: [
          //auditorio_dia sala 1
          {
        	id: 3,
            titulo: 'Auditorio 3', 
            bloques:[
              {hora_inicio: '09:00', hora_fin: '10:00', titulo: 'Taller introductorio (Collor Amarillo)', descripcion: '', presentaciones:false},
              {hora_inicio: '10:00', hora_fin: '13:00', titulo: 'Bloque de presentaciones n 5', descripcion: '', presentaciones:[]},
              {hora_inicio: '13:00', hora_fin: '15:00', titulo: 'Almuerzo libre', descripcion: '', presentaciones:false},
              {hora_inicio: '15:00', hora_fin: '18:00', titulo: 'Bloque de presentaciones n 6', descripcion: '', presentaciones:[]}
            ]
          },
          
          //auditorio_dia sala 2
          {
        	id: 4,
            titulo: 'Auditorio 4', 
            bloques:[
              {hora_inicio: '09:00', hora_fin: '10:00', titulo: 'Taller introductorio (Collor Rojo)', descripcion: '', presentaciones:false},
              {hora_inicio: '10:00', hora_fin: '13:00', titulo: 'Bloque de presentaciones n 7', descripcion: '', presentaciones:[]},
              {hora_inicio: '13:00', hora_fin: '15:00', titulo: 'Almuerzo libre (Rojo)', descripcion: '', presentaciones:false},
              {hora_inicio: '15:00', hora_fin: '18:00', titulo: 'Bloque de presentaciones n 8', descripcion: '', presentaciones:[]}
            ]
          }
        ]
      }
    ]
  };
  
  tanda.presentaciones_libres=tanda.presentaciones;
  return tanda;
}
