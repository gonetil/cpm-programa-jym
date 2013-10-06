/*
 AngularJS v1.1.5
 (c) 2010-2012 Google, Inc. http://angularjs.org
 License: MIT
 */
(function(B,f,w){'use strict';f.module("ngResource",["ng"]).factory("$resource",["$http","$parse",function(x,y){function u(b,d){this.template=b;this.defaults=d||{};this.urlParams={}}function v(b,d,e){function j(c,b){var p={},b=m({},d,b);l(b,function(a,b){k(a)&&(a=a());var g;a&&a.charAt&&a.charAt(0)=="@"?(g=a.substr(1),g=y(g)(c)):g=a;p[b]=g});return p}function c(c){t(c||{},this)}var n=new u(b),e=m({},z,e);l(e,function(b,d){b.method=f.uppercase(b.method);var p=b.method=="POST"||b.method=="PUT"||b.method==
"PATCH";c[d]=function(a,d,g,A){function f(){h.$resolved=!0}var i={},e,o=q,r=null;switch(arguments.length){case 4:r=A,o=g;case 3:case 2:if(k(d)){if(k(a)){o=a;r=d;break}o=d;r=g}else{i=a;e=d;o=g;break}case 1:k(a)?o=a:p?e=a:i=a;break;case 0:break;default:throw"Expected between 0-4 arguments [params, data, success, error], got "+arguments.length+" arguments.";}var h=this instanceof c?this:b.isArray?[]:new c(e),s={};l(b,function(a,b){b!="params"&&b!="isArray"&&(s[b]=t(a))});s.data=e;n.setUrlParams(s,m({},
j(e,b.params||{}),i),b.url);i=x(s);h.$resolved=!1;i.then(f,f);h.$then=i.then(function(a){var d=a.data,g=h.$then,e=h.$resolved;if(d)b.isArray?(h.length=0,l(d,function(a){h.push(new c(a))})):(t(d,h),h.$then=g,h.$resolved=e);(o||q)(h,a.headers);a.resource=h;return a},r).then;return h};c.prototype["$"+d]=function(a,b,g){var e=j(this),f=q,i;switch(arguments.length){case 3:e=a;f=b;i=g;break;case 2:case 1:k(a)?(f=a,i=b):(e=a,f=b||q);case 0:break;default:throw"Expected between 1-3 arguments [params, success, error], got "+
arguments.length+" arguments.";}c[d].call(this,e,p?this:w,f,i)}});c.bind=function(c){return v(b,m({},d,c),e)};return c}var z={get:{method:"GET"},save:{method:"POST"},query:{method:"GET",isArray:!0},remove:{method:"DELETE"},"delete":{method:"DELETE"}},q=f.noop,l=f.forEach,m=f.extend,t=f.copy,k=f.isFunction;u.prototype={setUrlParams:function(b,d,e){var j=this,c=e||j.template,n,k,m=j.urlParams={};l(c.split(/\W/),function(b){b&&RegExp("(^|[^\\\\]):"+b+"(\\W|$)").test(c)&&(m[b]=!0)});c=c.replace(/\\:/g,
":");d=d||{};l(j.urlParams,function(b,a){n=d.hasOwnProperty(a)?d[a]:j.defaults[a];f.isDefined(n)&&n!==null?(k=encodeURIComponent(n).replace(/%40/gi,"@").replace(/%3A/gi,":").replace(/%24/g,"$").replace(/%2C/gi,",").replace(/%20/g,"%20").replace(/%26/gi,"&").replace(/%3D/gi,"=").replace(/%2B/gi,"+"),c=c.replace(RegExp(":"+a+"(\\W|$)","g"),k+"$1")):c=c.replace(RegExp("(/?):"+a+"(\\W|$)","g"),function(b,a,c){return c.charAt(0)=="/"?c:a+c})});c=c.replace(/\/+$/,"");c=c.replace(/\/\.(?=\w+($|\?))/,".");
b.url=c.replace(/\/\\\./,"/.");l(d,function(c,a){if(!j.urlParams[a])b.params=b.params||{},b.params[a]=c})}};return v}])})(window,window.angular);
