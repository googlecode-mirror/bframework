/**
 * bjs
 * @author Junbo Bao <baojunbo@gmail.com>
 * @version 1.0.0
 */
var w=window,d=document;
var de=d.documentElement;
var pH=de.clientHeight,pW=de.clientWidth;
var isIE=(d.all)?true:false;
function $(s) {
	var o = null;
	var a,f,k;
	if (s.indexOf('|')==-1) {
		o=d.getElementById(s);
	}else{
		a=s.split('|');
		f=a[0].toLowerCase();
		k=a[1];
		if (f=='n') {
			o=d.getElementsByName(k);
		}else if (f=='t') {
			o=d.getElementsByTagName(k.toLowerCase());
		}
	}
	if (o) {
		if (o.length > 1) {
			for (var i = 0; i < o.length; i ++){
				o[i].getValue=function(){return this.value;};
				o[i].setValue=function(v){this.value=v;};
				o[i].getId=function(){return this.id;};
				o[i].setId=function(v){this.id=v;};
				o[i].getName=function(){return this.name;};
				o[i].setName=function(v){this.name=v;};
				o[i].getSrc=function(){return this.src;};
				o[i].setSrc=function(v){ this.src=v;};
				o[i].getInnerHTML=function(){return this.innerHTML;};
				o[i].setInnerHTML=function(v){this.innerHTML=v;};
				o[i].getTextValue=function(){return this.innerHTML.htmlspecialchars('ENT_QUOTES');};
				o[i].setTextValue=function(v){this.innerHTML=v.htmlspecialchars('ENT_QUOTES');};
				o[i].getClassName=function(){return this.className;};
				o[i].setClassName=function(v){this.className=v;};
				o[i].getChecked=function(){if(this.checked==true)return true;else return false;};
				o[i].setChecked=function(v){this.checked=v;};
				o[i].getSelected=function(){if(this.selected==true)return true;else return false;};
				o[i].setSelected=function(v){this.selected=v;};
				o[i].getHref=function(){return this.href;};
				o[i].setHref=function(v){this.href=v;};
				o[i].getTitle=function(){return this.title;};
				o[i].setTitle=function(v){ this.title=v;};
				o[i].setStyle=function(s,v){_setStyle(this,s,v)};
				o[i].addEvent=function(t,h){if(this.addEventListener){this.addEventListener(t,h,false);return true;}else if(this.attachEvent){return this.attachEvent('on'+t,h);}else{return false;}};
			}
		} else {
			o.getValue=function(){return this.value;};
			o.setValue=function(v){this.value=v;};
			o.getId=function(){return this.id;};
			o.setId=function(v){this.id=v;};
			o.getName=function(){return this.name;};
			o.setName=function(v){this.name=v;};
			o.getSrc=function(){return this.src;};
			o.setSrc=function(v){this.src=v;};
			o.getInnerHTML=function(){return this.innerHTML.toLowerCase();};
			o.setInnerHTML=function(v){this.innerHTML=v;};
			o.getTextValue=function(){return this.innerHTML.htmlspecialchars('ENT_QUOTES');};
			o.setTextValue=function(v){this.innerHTML=v.htmlspecialchars('ENT_QUOTES');};
			o.getClassName=function(){return this.className;};
			o.setClassName=function(v){this.className=v;};
			o.getChecked=function(){if(this.checked==true)return true;else return false;};
			o.setChecked=function(v){ this.checked=v;};
			o.getSelected=function(){if(this.selected==true)return true;else return false;};
			o.setSelected=function(v){this.selected=v;};
			o.getHref=function(){return this.href;};
			o.setHref=function(v){this.href=v;};
			o.getTitle=function(){return this.title;};
			o.setTitle=function(v){ this.title=v;};
			o.setStyle=function(s,v){_setStyle(this,s,v)};
			o.addEvent=function(t,h){if(this.addEventListener){this.addEventListener(t,h,false);return true;}else if(this.attachEvent){return this.attachEvent('on'+t,h);}else{return false;}};
		}
	}
	return o;
}

function eSrc(e){
	var evt=e||w.event;
	var eSrc=evt.target||evt.srcElement;
	return eSrc;
}

function getPageSize() {
	if (pH < de.scrollHeight) pH = de.scrollHeight;
	if (pW < de.scrollWidth)  pW = de.scrollWidth;
}

function createObjTip(src) {
	var t = this;
	var rect  = src.getBoundingClientRect();
	var left  = rect.left+de.scrollLeft;
	var top   = rect.top+de.scrollTop;
	var right = rect.right+de.scrollLeft;
	var bottom= rect.bottom+de.scrollTop;
	getPageSize();
	t.content=null;

	t.showObjTip=function() {
		if ($('objTip')) d.body.removeChild($('objTip'));
		var div = d.createElement('div');
		div.id = 'objTip';
		div.style.left= left+'px';
		div.style.top = bottom+'px';
		div.className = 'objTip';
		div.innerHTML = '<table class="tipContent" author="tipDialog"><tr><td colspan="2" rowspan="2" style="padding:0;"><div id="contentDiv" class="content">'+t.content+'</div></td><td class="tipTopRight"></td></tr><tr><td id="rightBorderHeight" class="tipRightBorder"></td></tr><tr><td id="tipBottomLeft" class="tipBottomLeft"></td><td id="bottomBorderWidth" class="tipBottomBorder"></td><td id="bottomBorderRight" class="tipBottomRight"></td></tr></table>';
		d.body.appendChild(div);
		if(isIE) {
			var rightBorderHeight = $('contentDiv').offsetHeight;
			var bottomBorderWidth = $('contentDiv').offsetWidth;
			$('rightBorderHeight').setStyle('height',rightBorderHeight-12+'px');
			$('bottomBorderWidth').setStyle('width',bottomBorderWidth-10+'px');
			$('tipBottomLeft').setStyle('height','2px');
			$('bottomBorderWidth').setStyle('height','2px');
			$('bottomBorderRight').setStyle('height','2px');
		}
		if (bottom>=pH) {
			var newTop = top-$('objTip').offsetHeight;
			$('objTip').setStyle('top', newTop+'px');
		}
	};

	t.hiddenObjTip=function(){
		var objTip = $('objTip');
		if (objTip && !objTip.getAttribute('Author')) d.body.removeChild($('objTip'));
	}
}

function _setStyle(o,s,v){
	var p=/^(?:[\w\-#%+]+|rgb\(\d+ *,*\d+,*\d+\)|url\('?http[^ ]+?'?\)| +)*$/i;
	if(typeof s=='string'){
		if(p.test(v)){o.style[s]=v;}
	}else{
		for(var i in s){_setStyle(o,i,s[i]);}
	}
}

function stopEvent(e) {
	if (isIE) {
		w.event.returnValue = false;
	} else {
		e.preventDefault();
	}
}

String.prototype.htmlspecialchars=function(quote_style){
	var s=this.toString();
	s=s.replace(/&/g,'&amp;');
	s=s.replace(/</g,'&lt;');
	s=s.replace(/>/g,'&gt;');
	s=s.replace(/"/g,'&quot;');
	if(quote_style=='ENT_QUOTES'){
		s=s.replace(/\'/g,'&#039;');
	}
	s=s.toLowerCase();
	return s;
};
String.prototype.decodeHtmlspecialchars=function(quote_style){
	var s=this.toString();
	s=s.replace(/&amp;/g,'&');
	s=s.replace(/&lt;/g,'<');
	s=s.replace(/&gt;/g,'>');
	s=s.replace(/&quot;/g,'"');
	if(quote_style=='ENT_QUOTES'){
		s=s.replace(/&#039;/g,'\'');
	}
	s=s.toLowerCase();
	return s;
};
String.prototype.cnLength=function(){var arr=this.match(/[^\x00-\xff]/ig);return this.length+(arr==null?0:arr.length);};
String.prototype.cnSubstr=function(num,from,mode){
	from=(from)?from:0;
	if(!/\d+/.test(num)) return(this);
	var str=this.substr(from,num);
	if(!mode) return str;
	var n=str.cnLength()-str.length;
	num=num-parseInt(n/2);
	return this.substr(from,num);
};
String.prototype.isEmail=function(){
	var flag=false,email=this;
	var p1=/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z]{2,3}$)+/;
	var p2=/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z]{2,3})+(\.[a-zA-Z]{2,3}$)+/;
	if(p1.test(email)||p2.test(email))flag=true;
	if(flag){return true;}
};
String.prototype.isPhone=function(){
	var flag=false,str=this;
	var p1=/(^\d{3,4}\-\d{7,8}\-\d{3,}$)|(^\d{3,4}\-\d{7,8}$)/;
	var p2=/(^\(\d{3,4}\)\d{7,8}\-\d{3,}$)|(^\(\d{3,4}\)\d{7,8}$)/;
	var p3=/(^0\d{10,11}$)|(^\d{7,8}$)/;
	if(p1.test(str)||p2.test(str)||p3.test(str))flag=true;if(flag){return true;}
};
String.prototype.isMobile=function(){
	var flag=false,str=this,p=/(^13\d{9}$)|(^153\d{8}$)|(^159\d{8}$)|(^158\d{8}$)|(^188\d{8}$)|(^189\d{8}$)/;
	if(p.test(str))flag=true;if(flag){return true;}
};
String.prototype.trim=function(){return this.replace(/(^s*)|(s*$)/g,'');}
String.prototype.isNum=function(){if(/^\d+(\.\d+)?$/.test(this))return true;};
String.prototype.toNumber=function(){return parseFloat(this);};
String.prototype.hexMd5=function(){return hex_md5(this)}
