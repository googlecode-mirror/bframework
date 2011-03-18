/**
 * bjs dialog
 * @author Junbo Bao <baojunbo@gmail.com>
 * @version 1.0.0
 */
function BjsDialog(w,h) {
	var t=this,id=Math.random();
	getPageSize();
	t.pH= pH;
	t.w = (w) ? w : 400;
	t.h = (h) ? h : 200;
	t.onconfirm='';t.oncancle ='';

	var getTopPlace=function(h){
		if (h) t.h=h;
		return (t.pH - t.h - 90)/2;
	};

	var showDialog=function(c){
		var cobj=d.getElementById('pop_dialog_'+id);
		if(cobj!=null) d.body.removeChild(cobj);
		var div=d.createElement('div');
		div.id='pop_dialog_'+id;
		div.innerHTML=c;
		d.body.appendChild(div);
		$('confirm_'+id).focus();
		$('confirm_'+id).onclick=hiddenDialog;
		$('closeButton_'+id).onclick=hiddenDialog;
	};

	var hiddenDialog=function(event){
		var rs=true;
		src=eSrc(event);
		if(t.onconfirm!=''&&src.id=='confirm_'+id) rs=t.onconfirm();
		if(t.oncancle!=''&&src.id=='cancle_'+id) rs=t.oncancle();
		if(rs){
			var cobj=d.getElementById('pop_dialog_'+id);
			if(cobj!=null) d.body.removeChild(cobj);
		}
		stopEvent(event);
	};

	var getDialogContent=function(title,content,confirmB,cancleB){
		t.t = getTopPlace();
		var cb='',sb='<input id="confirm_'+id+'" type="button" value="'+confirmB+'" class="confirm">';
		if(typeof cancleB!='undefined'){
			cb='&nbsp;<input id="cancle_'+id+'" type="button" value="'+cancleB+'" class="confirm cancle">';
		}
		var c='<div class="pop_bg" style="height:'+t.pH+'px;"></div><div id="pop_dialog_div_'+id+'" class="pop_dialog_div" style="top:'+t.t+'px;"><table class="pop_dialog_table" id="pop_dialog_table_'+id+'" style="width:'+t.w+'px;"><tr><td class="pop_topleft"></td><td class="pop_border"></td><td class="pop_topright"></td></tr><tr><td class="pop_border"></td><td class="pop_title"><div id="pop_title_'+id+'" class="left">'+title+'</div><div class="right"><a id="closeButton_'+id+'" href="#">X</a></div></td><td class="pop_border"></td></tr><tr><td class="pop_border"></td><td class="pop_content"><div id="pop_content_'+id+'" style="height:'+t.h+'px">'+content+'</div></td><td class="pop_border"></td></tr><tr><td class="pop_border"></td><td class="pop_bottom">'+sb+cb+'</td><td class="pop_border"></td></tr><tr><td class="pop_bottomleft"></td><td class="pop_border"></td><td class="pop_bottomright"></td></tr></table></div>';
		return c;
	};

	t.setSize=function(w,h) {
		$('pop_dialog_table_'+id).setStyle('width',w+'px');
		$('pop_content_'+id).setStyle('height',h+'px');
		if (t.h != h){
			t.t = getTopPlace(h);
			$('pop_dialog_div_'+id).setStyle('top',t.h+'px');
		}
	};

	t.setConfirmButton=function(caption){
		$('confirm_'+id).setValue(caption);
	}

	t.setDialogTitle=function(title){
		$('pop_title_'+id).setInnerHTML(title);
	}

	t.setDialogContent=function(content){
		$('pop_content_'+id).setInnerHTML(content);
	}

	t.showMsg = function(title,content,confirmB) {
		var c=getDialogContent(title,content,confirmB);
		showDialog(c);
	};

	t.showChoose=function(title,content,confirmB,cancleB) {
		var c=getDialogContent(title,content,confirmB,cancleB);
		showDialog(c);
		$('cancle_'+id).onclick=hiddenDialog;
	};

	t.hiddenDialog=function(){
		var cobj= d.getElementById('pop_dialog_'+id);
		if(cobj!=null) d.body.removeChild(cobj);
	};
}
