/**
 * bjs ajax
 * @author Junbo Bao <baojunbo@gmail.com>
 * @version 1.0.0
 */
function BjsAjax(loadingBar,width) {
	var canFree = false,t=this;
	getPageSize();
	t.loadingBar=loadingBar;
	t.method='post';
	t.responseType='RAW';
	t.ondone=null;
	var xmlHttp = null;
	var LoadingBar = null;
	var LoadingMax = width ? width : 400;
	var LoadingWidth = null;
	var LoadingTimer = 10;
	var HttpState = 0;
	var showLoadingBar=function(){
		if (t.loadingBar) {
			var loadingBarObj=d.getElementById('loadingBar');
			if(loadingBarObj) d.body.removeChild(loadingBarObj);
			var top=(pH-20)/2;
			var left=(pW-LoadingMax)/2;
			var c='<div class="loadingBarBg" style="height:'+pH+'px;"></div><div class="loadingBar" style="width:'+LoadingMax+'px;top:'+top+'px;margin-left:'+left+'px;"><div class="bar" id="bar"></div></div>';
			var div=d.createElement('div');
			div.id='loadingBar';
			div.innerHTML=c;
			d.body.appendChild(div);
			LoadingBar = $('bar');
			LoadingMax = LoadingBar.offsetWidth;
			LoadingWidth = LoadingBar.style.width;
			LoadingBar.setStyle('width', '0px');
			HttpState = 0;
		}
	}
	var xmlHttp=function(){
		try {
			if (navigator.appName.indexOf("Netscape")==-1) {
				try {
					xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
				}catch(ie){
					xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
				}
			} else {
				xmlHttp=new XMLHttpRequest();
			}
			canFree=true;
			return xmlHttp;
		}catch(e){
			return false;
		}
	};
	var buildHttpQuery=function(params){
		if (typeof params == 'string') {
			return params;
		} else {
			var fieldArr = new Array();
			var tmpArr = new Array();
			var tmpStr = '';
			params['random'] = Math.random();
			for (var k in params) {
				tmpArr.push(k);
			}
			tmpArr.sort();
			for (var i = 0; i < tmpArr.length; i++) {
				tmpStr = tmpArr[i];
				fieldArr.push(tmpStr+'='+encodeURIComponent(params[tmpStr]));
			}
			return fieldArr.join('&');
		}
	};

	var changeLoadingBar=function(){
		if (t.loadingBar) {
			try{
				if(LoadingBar.offsetWidth >= LoadingMax){
					LoadingBar.setStyle('width', LoadingMax+'px');
					return;
				}
				var loadingBarWidth = HttpState * Math.floor(LoadingMax / 4);
				if(LoadingBar.offsetWidth < LoadingMax){
					LoadingBar.setStyle('width', loadingBarWidth+'px');
				}
				if (HttpState == 4) {
					if (xmlHttp.status == 200) {
						if (LoadingBar.offsetWidth == LoadingMax && t.loadingBar) {setTimeout(function(){d.body.removeChild($('loadingBar'));if (t.ondone) t.ondone(getResponse());}, 100);}
					} else {
						if ($('loadingBar')) d.body.removeChild($('loadingBar'));
					}
				}
			}catch(e){}
		}
	};

	var getResponse=function(){
		var data=xmlHttp.responseText;
		if(t.responseType=='JSON'){
			try{eval('str='+data);}catch (e){str=data;};
		}else{
			str=data;
		}
		return str;
	}

	var StateChange=function(){
		try{
			if(xmlHttp.readyState) HttpState = xmlHttp.readyState;
			changeLoadingBar();
			if (HttpState==4 && xmlHttp.status==200 && !t.loadingBar) {
				if(t.ondone) t.ondone(getResponse());
			}
		}catch(e){}
	};

	t.send=function(url,params) {
		try {
			showLoadingBar();
			var param= buildHttpQuery(params);
			var ajax = xmlHttp();
			if (t.method.toLowerCase()=='get') url += '?' + param;
			ajax.open(t.method,url,true);
			ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded;charset=utf-8');
			ajax.onreadystatechange = StateChange;
			ajax.send(param);
			return true;
		}catch(e){
			return false;
		}
	}
}
