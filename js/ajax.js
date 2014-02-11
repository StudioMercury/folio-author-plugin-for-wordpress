// underscore
//     Underscore.js 1.5.2
//     http://underscorejs.org
//     (c) 2009-2013 Jeremy Ashkenas, DocumentCloud and Investigative Reporters & Editors
//     Underscore may be freely distributed under the MIT license.
(function(){var n=this,t=n._,r={},e=Array.prototype,u=Object.prototype,i=Function.prototype,a=e.push,o=e.slice,c=e.concat,l=u.toString,f=u.hasOwnProperty,s=e.forEach,p=e.map,h=e.reduce,v=e.reduceRight,g=e.filter,d=e.every,m=e.some,y=e.indexOf,b=e.lastIndexOf,x=Array.isArray,w=Object.keys,_=i.bind,j=function(n){return n instanceof j?n:this instanceof j?(this._wrapped=n,void 0):new j(n)};"undefined"!=typeof exports?("undefined"!=typeof module&&module.exports&&(exports=module.exports=j),exports._=j):n._=j,j.VERSION="1.5.2";var A=j.each=j.forEach=function(n,t,e){if(null!=n)if(s&&n.forEach===s)n.forEach(t,e);else if(n.length===+n.length){for(var u=0,i=n.length;i>u;u++)if(t.call(e,n[u],u,n)===r)return}else for(var a=j.keys(n),u=0,i=a.length;i>u;u++)if(t.call(e,n[a[u]],a[u],n)===r)return};j.map=j.collect=function(n,t,r){var e=[];return null==n?e:p&&n.map===p?n.map(t,r):(A(n,function(n,u,i){e.push(t.call(r,n,u,i))}),e)};var E="Reduce of empty array with no initial value";j.reduce=j.foldl=j.inject=function(n,t,r,e){var u=arguments.length>2;if(null==n&&(n=[]),h&&n.reduce===h)return e&&(t=j.bind(t,e)),u?n.reduce(t,r):n.reduce(t);if(A(n,function(n,i,a){u?r=t.call(e,r,n,i,a):(r=n,u=!0)}),!u)throw new TypeError(E);return r},j.reduceRight=j.foldr=function(n,t,r,e){var u=arguments.length>2;if(null==n&&(n=[]),v&&n.reduceRight===v)return e&&(t=j.bind(t,e)),u?n.reduceRight(t,r):n.reduceRight(t);var i=n.length;if(i!==+i){var a=j.keys(n);i=a.length}if(A(n,function(o,c,l){c=a?a[--i]:--i,u?r=t.call(e,r,n[c],c,l):(r=n[c],u=!0)}),!u)throw new TypeError(E);return r},j.find=j.detect=function(n,t,r){var e;return O(n,function(n,u,i){return t.call(r,n,u,i)?(e=n,!0):void 0}),e},j.filter=j.select=function(n,t,r){var e=[];return null==n?e:g&&n.filter===g?n.filter(t,r):(A(n,function(n,u,i){t.call(r,n,u,i)&&e.push(n)}),e)},j.reject=function(n,t,r){return j.filter(n,function(n,e,u){return!t.call(r,n,e,u)},r)},j.every=j.all=function(n,t,e){t||(t=j.identity);var u=!0;return null==n?u:d&&n.every===d?n.every(t,e):(A(n,function(n,i,a){return(u=u&&t.call(e,n,i,a))?void 0:r}),!!u)};var O=j.some=j.any=function(n,t,e){t||(t=j.identity);var u=!1;return null==n?u:m&&n.some===m?n.some(t,e):(A(n,function(n,i,a){return u||(u=t.call(e,n,i,a))?r:void 0}),!!u)};j.contains=j.include=function(n,t){return null==n?!1:y&&n.indexOf===y?n.indexOf(t)!=-1:O(n,function(n){return n===t})},j.invoke=function(n,t){var r=o.call(arguments,2),e=j.isFunction(t);return j.map(n,function(n){return(e?t:n[t]).apply(n,r)})},j.pluck=function(n,t){return j.map(n,function(n){return n[t]})},j.where=function(n,t,r){return j.isEmpty(t)?r?void 0:[]:j[r?"find":"filter"](n,function(n){for(var r in t)if(t[r]!==n[r])return!1;return!0})},j.findWhere=function(n,t){return j.where(n,t,!0)},j.max=function(n,t,r){if(!t&&j.isArray(n)&&n[0]===+n[0]&&n.length<65535)return Math.max.apply(Math,n);if(!t&&j.isEmpty(n))return-1/0;var e={computed:-1/0,value:-1/0};return A(n,function(n,u,i){var a=t?t.call(r,n,u,i):n;a>e.computed&&(e={value:n,computed:a})}),e.value},j.min=function(n,t,r){if(!t&&j.isArray(n)&&n[0]===+n[0]&&n.length<65535)return Math.min.apply(Math,n);if(!t&&j.isEmpty(n))return 1/0;var e={computed:1/0,value:1/0};return A(n,function(n,u,i){var a=t?t.call(r,n,u,i):n;a<e.computed&&(e={value:n,computed:a})}),e.value},j.shuffle=function(n){var t,r=0,e=[];return A(n,function(n){t=j.random(r++),e[r-1]=e[t],e[t]=n}),e},j.sample=function(n,t,r){return arguments.length<2||r?n[j.random(n.length-1)]:j.shuffle(n).slice(0,Math.max(0,t))};var k=function(n){return j.isFunction(n)?n:function(t){return t[n]}};j.sortBy=function(n,t,r){var e=k(t);return j.pluck(j.map(n,function(n,t,u){return{value:n,index:t,criteria:e.call(r,n,t,u)}}).sort(function(n,t){var r=n.criteria,e=t.criteria;if(r!==e){if(r>e||r===void 0)return 1;if(e>r||e===void 0)return-1}return n.index-t.index}),"value")};var F=function(n){return function(t,r,e){var u={},i=null==r?j.identity:k(r);return A(t,function(r,a){var o=i.call(e,r,a,t);n(u,o,r)}),u}};j.groupBy=F(function(n,t,r){(j.has(n,t)?n[t]:n[t]=[]).push(r)}),j.indexBy=F(function(n,t,r){n[t]=r}),j.countBy=F(function(n,t){j.has(n,t)?n[t]++:n[t]=1}),j.sortedIndex=function(n,t,r,e){r=null==r?j.identity:k(r);for(var u=r.call(e,t),i=0,a=n.length;a>i;){var o=i+a>>>1;r.call(e,n[o])<u?i=o+1:a=o}return i},j.toArray=function(n){return n?j.isArray(n)?o.call(n):n.length===+n.length?j.map(n,j.identity):j.values(n):[]},j.size=function(n){return null==n?0:n.length===+n.length?n.length:j.keys(n).length},j.first=j.head=j.take=function(n,t,r){return null==n?void 0:null==t||r?n[0]:o.call(n,0,t)},j.initial=function(n,t,r){return o.call(n,0,n.length-(null==t||r?1:t))},j.last=function(n,t,r){return null==n?void 0:null==t||r?n[n.length-1]:o.call(n,Math.max(n.length-t,0))},j.rest=j.tail=j.drop=function(n,t,r){return o.call(n,null==t||r?1:t)},j.compact=function(n){return j.filter(n,j.identity)};var M=function(n,t,r){return t&&j.every(n,j.isArray)?c.apply(r,n):(A(n,function(n){j.isArray(n)||j.isArguments(n)?t?a.apply(r,n):M(n,t,r):r.push(n)}),r)};j.flatten=function(n,t){return M(n,t,[])},j.without=function(n){return j.difference(n,o.call(arguments,1))},j.uniq=j.unique=function(n,t,r,e){j.isFunction(t)&&(e=r,r=t,t=!1);var u=r?j.map(n,r,e):n,i=[],a=[];return A(u,function(r,e){(t?e&&a[a.length-1]===r:j.contains(a,r))||(a.push(r),i.push(n[e]))}),i},j.union=function(){return j.uniq(j.flatten(arguments,!0))},j.intersection=function(n){var t=o.call(arguments,1);return j.filter(j.uniq(n),function(n){return j.every(t,function(t){return j.indexOf(t,n)>=0})})},j.difference=function(n){var t=c.apply(e,o.call(arguments,1));return j.filter(n,function(n){return!j.contains(t,n)})},j.zip=function(){for(var n=j.max(j.pluck(arguments,"length").concat(0)),t=new Array(n),r=0;n>r;r++)t[r]=j.pluck(arguments,""+r);return t},j.object=function(n,t){if(null==n)return{};for(var r={},e=0,u=n.length;u>e;e++)t?r[n[e]]=t[e]:r[n[e][0]]=n[e][1];return r},j.indexOf=function(n,t,r){if(null==n)return-1;var e=0,u=n.length;if(r){if("number"!=typeof r)return e=j.sortedIndex(n,t),n[e]===t?e:-1;e=0>r?Math.max(0,u+r):r}if(y&&n.indexOf===y)return n.indexOf(t,r);for(;u>e;e++)if(n[e]===t)return e;return-1},j.lastIndexOf=function(n,t,r){if(null==n)return-1;var e=null!=r;if(b&&n.lastIndexOf===b)return e?n.lastIndexOf(t,r):n.lastIndexOf(t);for(var u=e?r:n.length;u--;)if(n[u]===t)return u;return-1},j.range=function(n,t,r){arguments.length<=1&&(t=n||0,n=0),r=arguments[2]||1;for(var e=Math.max(Math.ceil((t-n)/r),0),u=0,i=new Array(e);e>u;)i[u++]=n,n+=r;return i};var R=function(){};j.bind=function(n,t){var r,e;if(_&&n.bind===_)return _.apply(n,o.call(arguments,1));if(!j.isFunction(n))throw new TypeError;return r=o.call(arguments,2),e=function(){if(!(this instanceof e))return n.apply(t,r.concat(o.call(arguments)));R.prototype=n.prototype;var u=new R;R.prototype=null;var i=n.apply(u,r.concat(o.call(arguments)));return Object(i)===i?i:u}},j.partial=function(n){var t=o.call(arguments,1);return function(){return n.apply(this,t.concat(o.call(arguments)))}},j.bindAll=function(n){var t=o.call(arguments,1);if(0===t.length)throw new Error("bindAll must be passed function names");return A(t,function(t){n[t]=j.bind(n[t],n)}),n},j.memoize=function(n,t){var r={};return t||(t=j.identity),function(){var e=t.apply(this,arguments);return j.has(r,e)?r[e]:r[e]=n.apply(this,arguments)}},j.delay=function(n,t){var r=o.call(arguments,2);return setTimeout(function(){return n.apply(null,r)},t)},j.defer=function(n){return j.delay.apply(j,[n,1].concat(o.call(arguments,1)))},j.throttle=function(n,t,r){var e,u,i,a=null,o=0;r||(r={});var c=function(){o=r.leading===!1?0:new Date,a=null,i=n.apply(e,u)};return function(){var l=new Date;o||r.leading!==!1||(o=l);var f=t-(l-o);return e=this,u=arguments,0>=f?(clearTimeout(a),a=null,o=l,i=n.apply(e,u)):a||r.trailing===!1||(a=setTimeout(c,f)),i}},j.debounce=function(n,t,r){var e,u,i,a,o;return function(){i=this,u=arguments,a=new Date;var c=function(){var l=new Date-a;t>l?e=setTimeout(c,t-l):(e=null,r||(o=n.apply(i,u)))},l=r&&!e;return e||(e=setTimeout(c,t)),l&&(o=n.apply(i,u)),o}},j.once=function(n){var t,r=!1;return function(){return r?t:(r=!0,t=n.apply(this,arguments),n=null,t)}},j.wrap=function(n,t){return function(){var r=[n];return a.apply(r,arguments),t.apply(this,r)}},j.compose=function(){var n=arguments;return function(){for(var t=arguments,r=n.length-1;r>=0;r--)t=[n[r].apply(this,t)];return t[0]}},j.after=function(n,t){return function(){return--n<1?t.apply(this,arguments):void 0}},j.keys=w||function(n){if(n!==Object(n))throw new TypeError("Invalid object");var t=[];for(var r in n)j.has(n,r)&&t.push(r);return t},j.values=function(n){for(var t=j.keys(n),r=t.length,e=new Array(r),u=0;r>u;u++)e[u]=n[t[u]];return e},j.pairs=function(n){for(var t=j.keys(n),r=t.length,e=new Array(r),u=0;r>u;u++)e[u]=[t[u],n[t[u]]];return e},j.invert=function(n){for(var t={},r=j.keys(n),e=0,u=r.length;u>e;e++)t[n[r[e]]]=r[e];return t},j.functions=j.methods=function(n){var t=[];for(var r in n)j.isFunction(n[r])&&t.push(r);return t.sort()},j.extend=function(n){return A(o.call(arguments,1),function(t){if(t)for(var r in t)n[r]=t[r]}),n},j.pick=function(n){var t={},r=c.apply(e,o.call(arguments,1));return A(r,function(r){r in n&&(t[r]=n[r])}),t},j.omit=function(n){var t={},r=c.apply(e,o.call(arguments,1));for(var u in n)j.contains(r,u)||(t[u]=n[u]);return t},j.defaults=function(n){return A(o.call(arguments,1),function(t){if(t)for(var r in t)n[r]===void 0&&(n[r]=t[r])}),n},j.clone=function(n){return j.isObject(n)?j.isArray(n)?n.slice():j.extend({},n):n},j.tap=function(n,t){return t(n),n};var S=function(n,t,r,e){if(n===t)return 0!==n||1/n==1/t;if(null==n||null==t)return n===t;n instanceof j&&(n=n._wrapped),t instanceof j&&(t=t._wrapped);var u=l.call(n);if(u!=l.call(t))return!1;switch(u){case"[object String]":return n==String(t);case"[object Number]":return n!=+n?t!=+t:0==n?1/n==1/t:n==+t;case"[object Date]":case"[object Boolean]":return+n==+t;case"[object RegExp]":return n.source==t.source&&n.global==t.global&&n.multiline==t.multiline&&n.ignoreCase==t.ignoreCase}if("object"!=typeof n||"object"!=typeof t)return!1;for(var i=r.length;i--;)if(r[i]==n)return e[i]==t;var a=n.constructor,o=t.constructor;if(a!==o&&!(j.isFunction(a)&&a instanceof a&&j.isFunction(o)&&o instanceof o))return!1;r.push(n),e.push(t);var c=0,f=!0;if("[object Array]"==u){if(c=n.length,f=c==t.length)for(;c--&&(f=S(n[c],t[c],r,e)););}else{for(var s in n)if(j.has(n,s)&&(c++,!(f=j.has(t,s)&&S(n[s],t[s],r,e))))break;if(f){for(s in t)if(j.has(t,s)&&!c--)break;f=!c}}return r.pop(),e.pop(),f};j.isEqual=function(n,t){return S(n,t,[],[])},j.isEmpty=function(n){if(null==n)return!0;if(j.isArray(n)||j.isString(n))return 0===n.length;for(var t in n)if(j.has(n,t))return!1;return!0},j.isElement=function(n){return!(!n||1!==n.nodeType)},j.isArray=x||function(n){return"[object Array]"==l.call(n)},j.isObject=function(n){return n===Object(n)},A(["Arguments","Function","String","Number","Date","RegExp"],function(n){j["is"+n]=function(t){return l.call(t)=="[object "+n+"]"}}),j.isArguments(arguments)||(j.isArguments=function(n){return!(!n||!j.has(n,"callee"))}),"function"!=typeof/./&&(j.isFunction=function(n){return"function"==typeof n}),j.isFinite=function(n){return isFinite(n)&&!isNaN(parseFloat(n))},j.isNaN=function(n){return j.isNumber(n)&&n!=+n},j.isBoolean=function(n){return n===!0||n===!1||"[object Boolean]"==l.call(n)},j.isNull=function(n){return null===n},j.isUndefined=function(n){return n===void 0},j.has=function(n,t){return f.call(n,t)},j.noConflict=function(){return n._=t,this},j.identity=function(n){return n},j.times=function(n,t,r){for(var e=Array(Math.max(0,n)),u=0;n>u;u++)e[u]=t.call(r,u);return e},j.random=function(n,t){return null==t&&(t=n,n=0),n+Math.floor(Math.random()*(t-n+1))};var I={escape:{"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#x27;"}};I.unescape=j.invert(I.escape);var T={escape:new RegExp("["+j.keys(I.escape).join("")+"]","g"),unescape:new RegExp("("+j.keys(I.unescape).join("|")+")","g")};j.each(["escape","unescape"],function(n){j[n]=function(t){return null==t?"":(""+t).replace(T[n],function(t){return I[n][t]})}}),j.result=function(n,t){if(null==n)return void 0;var r=n[t];return j.isFunction(r)?r.call(n):r},j.mixin=function(n){A(j.functions(n),function(t){var r=j[t]=n[t];j.prototype[t]=function(){var n=[this._wrapped];return a.apply(n,arguments),z.call(this,r.apply(j,n))}})};var N=0;j.uniqueId=function(n){var t=++N+"";return n?n+t:t},j.templateSettings={evaluate:/<%([\s\S]+?)%>/g,interpolate:/<%=([\s\S]+?)%>/g,escape:/<%-([\s\S]+?)%>/g};var q=/(.)^/,B={"'":"'","\\":"\\","\r":"r","\n":"n","	":"t","\u2028":"u2028","\u2029":"u2029"},D=/\\|'|\r|\n|\t|\u2028|\u2029/g;j.template=function(n,t,r){var e;r=j.defaults({},r,j.templateSettings);var u=new RegExp([(r.escape||q).source,(r.interpolate||q).source,(r.evaluate||q).source].join("|")+"|$","g"),i=0,a="__p+='";n.replace(u,function(t,r,e,u,o){return a+=n.slice(i,o).replace(D,function(n){return"\\"+B[n]}),r&&(a+="'+\n((__t=("+r+"))==null?'':_.escape(__t))+\n'"),e&&(a+="'+\n((__t=("+e+"))==null?'':__t)+\n'"),u&&(a+="';\n"+u+"\n__p+='"),i=o+t.length,t}),a+="';\n",r.variable||(a="with(obj||{}){\n"+a+"}\n"),a="var __t,__p='',__j=Array.prototype.join,"+"print=function(){__p+=__j.call(arguments,'');};\n"+a+"return __p;\n";try{e=new Function(r.variable||"obj","_",a)}catch(o){throw o.source=a,o}if(t)return e(t,j);var c=function(n){return e.call(this,n,j)};return c.source="function("+(r.variable||"obj")+"){\n"+a+"}",c},j.chain=function(n){return j(n).chain()};var z=function(n){return this._chain?j(n).chain():n};j.mixin(j),A(["pop","push","reverse","shift","sort","splice","unshift"],function(n){var t=e[n];j.prototype[n]=function(){var r=this._wrapped;return t.apply(r,arguments),"shift"!=n&&"splice"!=n||0!==r.length||delete r[0],z.call(this,r)}}),A(["concat","join","slice"],function(n){var t=e[n];j.prototype[n]=function(){return z.call(this,t.apply(this._wrapped,arguments))}}),j.extend(j.prototype,{chain:function(){return this._chain=!0,this},value:function(){return this._wrapped}})}).call(this);

var underscore = _.noConflict();
if (typeof window._ === 'undefined') { window._ = underscore; }

/*
 * DPSFolioAuthor : JS components for the DPS Folio Author Plugin
 * Javascript communication with WP DPS Folio Author Plugin
 *
 * @Requires: jQuery
 *            Mustache
 *            dpsfa.ajax.js
 *            jquery.ui.js
 *
 */

function DPSFolioAjax(options) {
	this.initDialog();
};

underscore.extend(DPSFolioAjax.prototype, {

/* START DIALOG BOX */
	openDialog: function(){
	    this.dialog.addClass('active');
	},

	closeDialog: function(){
    	this.updateDialogHead("");
    	this.updateDialog("");
    	this.dialog.removeClass("active");
	},
	
	updateDialogHead: function( message ){
        this.dialog.find("#modal-head").html(message);
	},

	updateDialog: function( message, icon, actions ){
	    if(typeof actions == "undefined"){ actions = ""; }
	    if(typeof icon == "undefined"){ icon = ""; }
    	this.openDialog();
        this.dialog.find("#modal-content").html(message);
        this.dialog.find("#modal-actions").html(actions);
        this.dialog.find("#modal-icon").html('<i class="fa fa-' + icon + '"></i>');
	},

	updateDialogFromAjax: function(response){
    	this.updateDialog( response );
        if(Gumby){ Gumby.initialize(['skiplink', 'checkbox', 'radiobtn'], true); }
	},

	enableClose: function(){
    	jQuery("#modal .close, #modal").on("click", jQuery.proxy(function(e){
    	    if( jQuery(e.target).hasClass("close") ){
    	        this.closeDialog();
    	    }
	    },this) );
	},

	disableClose: function(){
	    jQuery("#modal .close, #modal").off("click");
    	this.dialog.find(".close.switch").hide();
	},
	
	initDialog: function(){
	    if( jQuery("#modal").length < 1){
    	    jQuery("body").append( '<div class="gumby"><div class="modal close" id="modal">' +
                                     '<div class="content">' +
                                        '<a class="close switch"><i class="close switch icon-cancel"></i></a>' +
                                        '<div class="row">' +
                                          '<div class="twelve columns centered text-center">' +
                                            '<h1 id="modal-head"></h1>' +
                                            '<div id="modal-icon"></div>'+
                                            '<div id="modal-content"></div>' +
                                            '<div id="modal-actions"></div>' +
                                          '</div>' +
                                        '</div>' +
                                      '</div>' +
                                '</div></div>' );
	    }

	    this.dialog = jQuery( "#modal" );
	    this.enableClose();
	},
	
	confirmation_screen: function( onConfirm, onCancel, message, header, confirmButton ){
		confirmButton = (confirmButton) ? confirmButton : "Delete";
    	this.openDialog();
        this.updateDialogHead(header);
        console.log(header);

        confirm = jQuery('<div class="medium danger btn"><a>'+confirmButton+'</a></div>').bind("click", onConfirm );
        cancel = jQuery('<div class="medium default btn"><a>Cancel</a></div>').bind("click", onCancel );
        buttons = jQuery('<div class="buttons"></div>');

        buttons.append( confirm );
        buttons.append( cancel );
        
        this.updateDialog(message, "exclamation-triangle", buttons);
	},
/* END DIALOG BOX */
    
    handle_errors: function( response, message, header ){
        if( !header ){ header = "Something went wrong."; }
        this.enableClose();
        this.updateDialogHead( header );
        this.updateDialog( message + ' Error results: <div class="errors">' + response.message + '</div>', "warning" );
    },
    
	open_box_duplicate_articles_from_rendition: function( data ){
	    this.openDialog();
	    this.updateDialogHead( "Duplicate Articles" );
	    data.action = "get_ajax_form";
	    data.form = "duplicate_articles_from_rendition";
	    onSuccess = jQuery.proxy(function(response){
	        this.updateDialogFromAjax( response );
	    },this);
	    this.request(data, onSuccess);
	},

	duplicate_articles_from_rendition: function( data ){
	    data = this.dialog.find("form").serialize();
	    onSuccess = jQuery.proxy(function(response){
            location.reload();  // articles are duplicated
	    },this);
	    onError = jQuery.proxy(function(response){
            message = 'Could not duplicate articles.';
	        this.handle_errors( response, message );
	    },this);
	    this.request(data, onSuccess, onError);
	},

	show_import_sidecar: function( data ){
	    jQuery( data.uploader ).show();
	},
	
	open_box_rendition_sync: function( data ){
	    this.openDialog();
	    this.updateDialogHead( "Sync Renditions" );
	    data.action = "get_ajax_form";
	    data.form = "rendition_sync";
	    onSuccess = jQuery.proxy(function(response){
	        this.updateDialogFromAjax( response );
	    },this);
	    this.request(data, onSuccess);
	},
	
	sync_renditions: function( data ){
    	data = this.dialog.find("form").serialize();
	    onSuccess = jQuery.proxy(function(response){
            alert(response);
            //location.reload();  // articles are done. rendition is pushed
	    },this);
	    onError = jQuery.proxy(function(response){
            message = 'Could not sync renditions.';
	        this.handle_errors( response, message );
	    },this);
	    this.request(data, onSuccess, onError);
	},
	
	open_box_new_folio: function( data ){
	    this.openDialog();
	    this.updateDialogHead( "Create a new folio" );
	    data.action = "get_ajax_form";
	    data.form = "create_new_folio";
	    onSuccess = jQuery.proxy(function(response){
	        this.updateDialogFromAjax( response );
	    },this);
	    this.request(data, onSuccess);
	},

	create_new_folio: function( data ){
	    data = this.dialog.find("form").serialize();
	    onSuccess = jQuery.proxy(function(response){
            location.reload();  // articles are done. rendition is pushed
	    },this);
	    onError = jQuery.proxy(function(response){
            message = 'Could not create the folio.';
	        this.handle_errors( response, message );
	    },this);
	    this.request(data, onSuccess, onError);
	},
	
	clear_dps_session: function( data ){
    	this.openDialog();
	    this.updateDialogHead( "Clearing DPS Session" );
        this.updateDialog( "Clearing your session with the DPS server.", "refresh fa-spin" );
	    onSuccess = jQuery.proxy(function(response){
	        this.updateDialogHead( "Session Cleared" );
	        this.updateDialog( "Next time you upload an article or folio it will renew your session. You can close this modal." );
	    },this);
	    this.request(data, onSuccess);
	},

	open_box_new_rendition: function( data ){
	    this.openDialog();
	    this.updateDialogHead( "Create a new rendition" );
	    data.action = "get_ajax_form";
	    data.form = "create_new_rendition";
	    onSuccess = jQuery.proxy(function(response){
	        this.updateDialogFromAjax( response );
	    },this);
	    this.request(data, onSuccess);
	},

	update_rendition: function( data ){
    	this.disableClose();
	    this.updateDialogHead( "Updating Rendition" );
	    this.updateDialog( "Updating the metadata for the rendition on Adobe.", "refresh fa-spin" );
	    onSuccess = jQuery.proxy(function(response){
            location.reload();  // articles are done. rendition is pushed
	    },this);
	    onError = jQuery.proxy(function(response){
            message = 'Could not update rendition metadat on adobe.';
	        this.handle_errors( response, message );
	    },this);
	    this.request(data, onSuccess, onError);
	},

	push_rendition: function( data ){
        this.updateDialogHead("Pushing rendition to the cloud");
        this.updateDialog("Creating the rendition shell in the cloud. Please wait while we create the a reference to the rendition in the cloud.", "refresh fa-spin");
        this.disableClose();
	    onSuccess = jQuery.proxy(function(response){
            location.reload();
	    },this);
	    onError = jQuery.proxy(function(response){
            message = 'Could not push this rendition to the cloud.';
	        this.handle_errors( response, message );
	    },this);
	    this.request(data, onSuccess, onError);
	},

	push_rendition_articles: function( data ){
	    this.articles = null;
	    this.folioID = data.folio;
	    this.disableClose();
        this.updateDialogHead( "Prepping the rendition" );
        this.updateDialog( "Collecting all the articles for the rendition." );
        this.get_folio_articles( data.folio );
	},

	push_articles: function( articles, folioID ){
	    this.updateDialogHead( "Pushing article " + this.articleIndex + " of " + this.articleCount);
        this.updateDialog( "Please wait, we're bundling your articles and pushing them to the cloud. Grab some coffee this may take a couple minutes.", "refresh fa-spin" );
        if( this.articleIndex > this.articleCount ){
            location.reload();  // articles are done. rendition is pushed
        }else{
            this.push_article( this.articles[ (this.articleIndex-1 )] );
        }
	},

	push_article: function( article ){
	    data = {};
	    data.action = "publish_article";
	    data.articleID = article;
	    onSuccess = jQuery.proxy(function(response){
    	   this.articleIndex++;
    	   this.push_articles();
	    },this);
	    onError = jQuery.proxy(function(response){
            message = 'Could not push article.';
	        this.handle_errors( response, message );
	    },this);
	    this.request(data, onSuccess, onError);
	},
	
	push_article_meta: function( article ){
    	
	},
	
	push_single_article: function( data ){
    	this.updateDialogHead("Pushing article to the cloud");
        this.updateDialog("Uploading the individual article.");
        this.disableClose();
        data.action = "publish_article";
        data.articleID = data.article;
	    onSuccess = jQuery.proxy(function(response){
            this.enableClose();
            this.updateDialogHead("Article update complete");
    	    this.updateDialog( 'Article has been updated in the cloud.' );
	    },this);
	    onError = jQuery.proxy(function(response){
            message = 'Could not push the article to the cloud.';
	        this.handle_errors( response, message );
	    },this);
	    this.request(data, onSuccess, onError);
	},
	
	update_folio_covers: function( folio ){
    	this.updateDialogHead("Updating folio covers");
        this.updateDialog("Uploading updates to the folio covers.");
        this.disableClose();
        data.action = "update_folio_covers";
        data.folio = data.folio;
        onSuccess = jQuery.proxy(function(response){
            this.enableClose();
            this.updateDialogHead("Covers completed");
            this.updateDialog( 'Covers are now live for this rendition.' );
	    },this);
	    onError = jQuery.proxy(function(response){
            message = 'Couldn not upload the folio covers.';
	        this.handle_errors( response, message );
	    },this);
	    this.request(data, onSuccess, onError);
	},

	get_folio_articles: function( folioID ){
	    data = {};
	    data.action = "get_folio_articles";
	    data.folioID = folioID;
	    
	    this.disableClose();
        this.updateDialogHead( "Collecting articles" );
        this.updateDialog( "Collecting all the articles for the rendition." );
	    onSuccess = jQuery.proxy(function(response){
	        if( response.hasOwnProperty("articles") && response.articles.length > 0 ){
                this.articleIndex = 1;
                this.articleCount = response.articles.length;
                this.articles = response.articles;
	            this.push_articles( response.articles, folioID );
	        }else{
                location.reload();
	        }
	    },this);
	    onError = jQuery.proxy(function(response){
            message = 'Could not get list of articles.';
	        this.handle_errors( response, message );
	    },this);
	    this.request(data, onSuccess, onError);
	},

	delete_rendition: function( data ){
	    onConfirm = jQuery.proxy(function(){
    	    this.openDialog();
            this.updateDialogHead("Deleting Rendition");
    	    this.updateDialog("Please wait while we delete any references on Adobe of the rendition.", "refresh fa-spin");
    	    onSuccess = jQuery.proxy(function(response){
                document.location = "admin.php?page=dpsfa_page_folios";
     	    },this);
     	    onError = jQuery.proxy(function(response){
                message = 'Could not delete rendition.';
    	        this.handle_errors( response, message );
    	    },this);
    	    this.request(data, onSuccess, onError);
	    },this);
	    onCancel = jQuery.proxy(function(){
	        this.closeDialog();
	    },this);
	    header = "Warning";
	    message = "Are you sure you want to delete this rendition? This will be permanent and will delete any references to the rendition on Adobe hosting as well.";
        this.confirmation_screen( onConfirm, onCancel, message, header );
    },

	create_new_rendition: function(){
	    data = this.dialog.find("form").serialize();

        this.updateDialogHead("");
        this.updateDialog("Now creating the new rendition size. We're also creating a copy of this rendition in the cloud. Once the rendtition is complete you can start adding articles to it.");
        this.disableClose();
	    onSuccess = jQuery.proxy(function(response){
    	   location.reload();
	    },this);
	    onError = jQuery.proxy(function(response){
            message = 'We could not create the new rendition.';
	        this.handle_errors( response, message );
	    },this);
	    this.request(data, onSuccess, onError);
	},

	publish_article: function( articleID ){
    	console.log( "AJAX:\t publishing article");
	    this.openDialog( "Publishing article. Grab a cup of coffee, this might take a couple min. This box will close when finished" );
	    data = {};
	    data.action = "publish_article";
	    data.articleID = articleID;
	    onSuccess = jQuery.proxy(function(response){
    	    location.reload();
	    },this);
	    onError = jQuery.proxy(function(response){
            message = 'We could not push the rendition.';
	        this.handle_errors( response, message );
	    },this);
	    this.request(data, onSuccess, onError);
	},

	upload_htmlresources: function( data ){
    	this.openDialog();
        this.updateDialogHead("Uploading HTMLResources");
	    this.updateDialog("Please wait while we collect and upload the HTMLResources for the folio", "refresh fa-spin");
	    onSuccess = jQuery.proxy(function(response){
            location.reload();
 	    },this);
 	    onError = jQuery.proxy(function(response){
            message = 'Could not add HTMLResources.';
	        this.handle_errors( response, message );
	    },this);
	    this.request(data, onSuccess, onError);
	},

	open_box_add_article: function( data ){
	    this.openDialog();
	    this.updateDialogHead( "Add articles to rendition" );
	    data.action = "get_ajax_form";
	    data.form = "add_articles_to_folio";
	    callback = jQuery.proxy(function(response){
	        this.updateDialogFromAjax( response );
	    },this);
	    this.request(data, callback);
	},

	add_articles_to_folio: function(){
	    data = this.dialog.find("form").serialize();
        this.updateDialogHead("");
	    this.updateDialog( "Adding the article to the folio. Please wait while we attach", "refresh fa-spin" );
        this.disableClose();
	    onSuccess = jQuery.proxy(function(response){
    	   location.reload();
	    },this);
	    onError = jQuery.proxy(function(response){
            message = 'We could not attach the articles to the folio.';
	        this.handle_errors( response, message );
	    },this);
	    this.request(data, onSuccess, onError);
	},

	open_box_import_article: function( data ){
	    this.openDialog();
	    this.updateDialogHead( "Select posts to import" );
	    data.action = "get_ajax_form";
	    data.form = "import_articles";
	    onSuccess = jQuery.proxy(function(response){
	        this.updateDialogFromAjax( response );
	    },this);
	    this.request(data, onSuccess);
	},

	import_articles: function(){
	    data = this.dialog.find("form").serialize();
        this.updateDialogHead("");
	    this.updateDialog( "Importing selected posts as articles. Please wait while we convert them.", "refresh fa-spin" );
        this.disableClose();
	    onSuccess = jQuery.proxy(function(response){
            location.reload();
	    },this);
	    onError = jQuery.proxy(function(response){
            message = 'Could not import the posts.';
	        this.handle_errors( response, message );
	    },this);
	    this.request(data, onSuccess, onError);
	},

	remove_article_from_folio: function( data ){
	    this.openDialog();
        this.updateDialog( "Removing the article from the folio" );
	    onSuccess = jQuery.proxy(function(response){
            location.reload();
	    },this);
	    onError = jQuery.proxy(function(response){
            message = 'We could not remove the article from the folio.';
	        this.handle_errors( response, message );
	    },this);
	    this.request(data, onSuccess, onError);
	},

	import_article: function(){
    	console.log( "AJAX:\t getting all posts");
	    this.openDialog( "Generating list of posts to import as an article" );
	    this.get_all_posts();
	},

	import_post: function( postID ){
    	console.log( "AJAX:\t now importing post: " + postID);
	    this.openDialog( "Importing post number: " + postID + ". This might take a minuite while we copy this post into an article." );
	    data = {};
	    data.action = "import_post_as_article";
	    data.postID = postID;
	    onSuccess = jQuery.proxy(function( response ){
    	    location.reload();
 	    },this);
 	    onError = jQuery.proxy(function(response){
            message = 'Could not import post.';
	        this.handle_errors( response, message );
	    },this);
	    this.request(data, onSuccess, onError);
	},

	get_all_posts: function(){
	    data = {};
	    data.action = "get_all_posts";
    	callback = jQuery.proxy(function(response){
    	    console.log("RESPONSE: " ,response);
    	    $postSelect = jQuery('<select id="import-post"></select>');
    	    jQuery.each( response.posts, function(index, post){
    	        console.log("POST", post);
        	    $postSelect.append('<option value="'+post.ID+'">'+post.title+'</option>');
    	    });
    	    $wrapper = jQuery("<div><span>Select a post to import</span></div>");
    	    $wrapper.append($postSelect);

    	    $importButton = jQuery('<div class="btn btn-default">Import Post as Article</div>');
    	    $importButton.on("click", jQuery.proxy(function(){
        	    this.import_post( jQuery("#import-post").val() );
    	    }, this) );

    	    $wrapper.append($postSelect);
    	    $wrapper.append( $importButton );

    	    jQuery("#dialog").html($wrapper);
 	    },this);
	    this.request(data, callback);
	},

	open_box_edit_folio: function( data ){
	    this.openDialog();
	    this.updateDialogHead( "Edit folio" );
	    data.action = "get_ajax_form";
	    data.form = "edit_folio";
	    callback = jQuery.proxy(function(response){
	        this.updateDialogFromAjax( response );

	    },this);
	    this.request(data, callback);
	},

	edit_folio: function( data ){
	    data = this.dialog.find("form").serialize();
	    this.disableClose();
        this.updateDialogHead( "Updating Folio" );
        this.updateDialog( "Syncing your updates now. Please wait.", "refresh fa-spin" );

	    onSuccess = jQuery.proxy(function(response){
            location.reload();  // article updated
	    },this);
	    onError = jQuery.proxy(function(response){
            message = 'Could not update the folio.';
	        this.handle_errors( response, message );
	    },this);
	    this.request(data, onSuccess, onError);
	},

	delete_folio: function( data ){
	    onConfirm = jQuery.proxy(function(){
    	    this.openDialog();
    	    this.updateDialog("Deleting folio. Please wait while we delete any references on Adobe of the folio.", "refresh fa-spin");
    	    callback = jQuery.proxy(function(){
        	    document.location = "admin.php?page=dpsfa_page_folios";
     	    },this);
    	    this.request(data, callback);
	    },this);
	    onCancel = jQuery.proxy(function(){
	        this.closeDialog();
	    },this);
	    header = "Warning";
	    message = "Are you sure you want to delete this folio? This will be permanent and will delete all renditions of this folio locally and on Adobe hosting.";
        this.confirmation_screen( onConfirm, onCancel, message, header );
	},

	delete_article: function( data ){
	    onConfirm = jQuery.proxy(function(){
    	    this.openDialog();
            this.disableClose();
    	    this.updateDialog("Deleting Article. Please wait while we delete remove the article.", "refresh fa-spin");
    	    onSuccess = jQuery.proxy(function(response){
                if(response.hasOwnProperty("redirect")){
                    document.location = response.redirect;
                }else{
                    location.reload();
                }
     	    },this);
     	    onError = jQuery.proxy(function(response){
                message = 'Could not delete the article.';
    	        this.handle_errors( response, message );
    	    },this);
    	    this.request(data, onSuccess, onError);	    
        },this);
	    onCancel = jQuery.proxy(function(){
	        this.closeDialog();
	    },this);
	    header = "Warning";
	    message = "Are you sure you want to delete this article? This will be permanent and will delete all article renditions locally and on Adobe hosting.";
        this.confirmation_screen( onConfirm, onCancel, message, header );
	},
	
	publish_folio: function( folioID ){
    	console.log( "AJAX:\t publishing folio");
	    this.updateDialog( "Publishing folio. This may take a while while we bundle all articles and push the folio to Adobe's servers", "refresh fa-spin" );
	    data = {};
	    data.action = "publish_folio";
	    data.folioID = articleID;
	    callback = jQuery.proxy(function(){
    	    location.reload();
	    },this);
	    this.request(data, callback);
	},

	sync_articles_from_adobe: function( folioID ){
    	console.log( "AJAX:\t syncing articles from adobe");
	    this.updateDialog( "Syncing articles for the folio with Adobe hosting. <BR/><BR/>  Grab a cup of coffee, this could take a couple minutes.", "refresh fa-spin" );
	    data = {};
	    data.action = "sync_hosted_articles";
	    data.folioID = folioID;
	    callback = jQuery.proxy(function(response){
	        if(response.code == 1){
               location.reload();
	        }else{
    	       alert("Error: something went wrong");
	        }
	    },this);
	    this.request(data, callback);
	},

	sync_hosted_folios: function( data ){
	    this.disableClose();
	    this.updateDialogHead( "Looking for missing folios" );
	    this.updateDialog( "We're looking in the cloud to see if there are any folios missing from the list below. Please wait while we check.", "refresh fa-spin" );
	    callback = jQuery.proxy(function(response){
	        this.enableClose();
	        if(response.code == 1){
                this.updateDialogHead( "Found some folios" );
                this.updateDialog( "Just added new folios to the list below." );
                location.reload();
	        }else{
    	       this.updateDialog('Error: something went wrong. Error results: <div class="errors">'+response.message+'</div>');
	        }
	    },this);
	    this.request(data, callback);
	},

	link_folio: function( data ){
	    this.disableClose();
	    this.updateDialogHead( "Linking Folio" );
	    this.updateDialog( "Please wait while we take over the folio on DPS and link it to wordpress.", "refresh fa-spin" );
	    callback = jQuery.proxy(function(){
    	    location.reload();
	    },this);
	    this.request(data, callback);
	},
	
	bulk_action: function( data ){
	    dataObj = jQuery(data.form).serialize();
	    dataObj = (dataObj) ? dataObj + "&action=bulk_action" : "action=bulk_action";
	    
	    onSuccess = jQuery.proxy(function(response){
            location.reload();
            console.log(response);
	    },this);
	    onError = jQuery.proxy(function(response){
            message = 'Couldn not do the bulk action';
	        this.handle_errors( response, message );
	    },this);
	    this.request(dataObj, onSuccess, onError);
	},

	update_article_positions: function( articles ){
	    console.log(articles);
	    data = {};
	    data.action = "update_article_positions";
	    data.articles = articles;
	    callback = jQuery.proxy(function(response){
    	    console.log(response);
	    },this);
	    this.request(data, callback);
	},

    select_device: function(data, $el){
        var name = $el.find("option:selected").attr("data-name");
        var width = $el.find("option:selected").attr("data-width")
        var height = $el.find("option:selected").attr("data-height");

        jQuery('[name="rendition\[renditionLabel\]"]').val( name );
        jQuery('[name="rendition\[meta\]\[resolutionWidth\]"]').val( width );
        jQuery('[name="rendition\[meta\]\[resolutionHeight\]"]').val( height );
    },

    select_all: function( data ){
        $el = jQuery( data.boxes );
        $el.find('input:checkbox').trigger('gumby.check');
        jQuery('[data-action="select_all"]').attr('data-action','deselect_all');

    },

    deselect_all: function( data ){
        $el = jQuery( data.boxes );
        $el.find('input:checkbox').trigger('gumby.uncheck');
        jQuery('[data-action="deselect_all"]').attr('data-action','select_all');
    },

    do_action: function( action, $this ){
        try{
            this[ action ]( $this.data(), $this );
        }catch(err){
            this.openDialog();
            this.updateDialogHead("Something went wrong");
            this.updateDialog("Could not do the action: " + action,"warning");
        }
    },
    
    toggle_element: function( data ){
        jQuery( data.toggle ).fadeToggle("fast");
        jQuery("#filter-button").toggle();
    },
    
    remove_device: function( data ){
        jQuery(data.device).hide().remove();
    },
    
    add_device: function( data ){
        // verify the fields are filled out
        var proceed = true;
        var $deviceMeta = jQuery('[data-new="device"]');

        $deviceMeta.each(function(){
            if( jQuery(this).val().length == 0 ){ proceed = false; }
        });
        
        if(proceed){
            var key = jQuery("#devices .device").length;
            var $device = jQuery('<li class="device" id="device-'+key+'"><div class="remove" data-action="remove_device" data-device="#device-'+key+'">REMOVE</div></li>');
            $deviceMeta.each(function(){
                var name = jQuery(this).attr('data-name');
                var setting = jQuery(this).attr('data-key');
                var value = jQuery(this).val();
                $device.append(
                    '<div class="'+name+'">'+value+'</div>' +
                    '<input type="hidden" data-device-field="'+name+'" name="'+setting+'[devices]['+key+']['+name+']" value="'+value+'" />'
                );
                jQuery(this).val("");
            });
            jQuery("#devices").append($device);
            console.log($device)
        }
    },
    
    filter: function(data){
        data.query = jQuery(data.search).val();
	    onSuccess = jQuery.proxy(function(response){
	        jQuery(data.list).html(response.found);
            Gumby.initialize(['checkbox']);
            data = {};
	        data.toggle = "#filter-options";
	        this.toggle_element( data );
	    },this);
	    onError = jQuery.proxy(function(response){
            message = 'Something went wrong in the search.';
	        this.handle_errors( response, message );
	        data = {};
	        data.toggle = "#filter-options";
	        this.toggle_element( data );
	    },this);
	    this.request(data, onSuccess, onError);
    },

    request: function(data, onSuccess, onError){
        url = window.ajaxurl;
	    console.log( "AJAX:\t REQUESTING FROM " + url + "with data: ", data);
		jQuery.ajax({
            type: "POST",
            url: url,
            async: true,
            data: data,
            success: jQuery.proxy( function (response) {
                console.log(response);
                if( response.code == 1 || !response.hasOwnProperty("code") ){
                    onSuccess.call(this, response);
                }else{
                    if( !onError ){
                        this.requestError(response.message);
                    }else{
                        onError.call(this, response);
                    }
                }
            }, this),
            error: jQuery.proxy( function (e) {
                 this.requestError(e.responseText);
            }, this)
        });
	},
	
	requestError: function( message ){
    	this.openDialog();
        this.enableClose();
        this.updateDialogHead("Something went wrong");
        this.updateDialog("Looks like something didn't go as expected. Here's the technical response: <BR/><BR/>" + message );
	}

});


jQuery( document ).ready( function(){

    /* INITIALIZE DPS AJAX */
    var DPSAjax = new DPSFolioAjax();

    /* BIND ALL ACTIONS TO THEIR DATA-ACTION ATTRIBUTE */
    jQuery('body').on('click', '[data-action]', function(){
        DPSAjax.do_action( jQuery(this).attr('data-action'), jQuery(this));
    });
    
    jQuery('body').on('keypress', '[data-option="disable-return"]', function(e){
        if (e.which == 13) {
            e.preventDefault();
            DPSAjax.do_action( jQuery( jQuery(this).data('submit') ).attr('data-action'), jQuery( jQuery(this).data('submit') ) );
        }
    });     
        
    /* BIND ALL ACTIONS TO THEIR DATA-ACTION ATTRIBUTE */
    jQuery('body').on('change','select[data-action-change]', function(){
        DPSAjax.do_action( jQuery(this).attr('data-action-change'), jQuery(this));
    });

    /* DATE PICKERS */
    jQuery('body').on('click focus', 'input.datepicker', function(){
        //jQuery(this).datepicker().prop('type', 'text');
        if (!jQuery(this).hasClass("hasDatepicker")){
            jQuery(this).datepicker();
            jQuery(this).datepicker("show");
        }
    });

    /* SORTABLE */
    jQuery( ".sortable.articles" ).sortable({
      placeholder: "ui-state-highlight",
      forcePlaceholderSize: true,
      update: function(event, ui) {
           articles = jQuery(this).sortable('toArray');
           jQuery("#articleList").val(articles.join(","));
      }
    });
    jQuery( ".sortable.articles" ).disableSelection();
    
    /* SORTABLE FOR DEVICES*/
    jQuery( ".sortable.devices" ).sortable({
      placeholder: "ui-state-highlight",
      forcePlaceholderSize: true,
      update: function(event, ui) {
           console.log(jQuery(this).sortable('toArray'));
      }
    });
    jQuery( ".sortable.devices" ).disableSelection();
        
    /* INITIALIZE GUMBY MODULES */
    //Gumby.init({ uiModules: ['checkbox'] });

});