/**
 * bjs calendar
 * @author Junbo Bao
 * @version 1.0.0
 */
function BjsCalendar() {
	var t=this;
	var objTip=null;
	t.passdayDisable = true;
	t.obj = null;

	var createCalendar = function(day) {
		var date;
		if (!day) date = new Date();
		else {
			var _arr = day.split('-');
			date = new Date(_arr[0],_arr[1]-1,_arr[2]);
		}
		var theYear = date.getFullYear();
		var theMonth= date.getMonth()+1;
		this.days=function() {
			var n = 0;
			var trtd = '';
			for(var i = 0; i < 6; i ++) {
				trtd += '<tr>';
				for(var j = 0; j < 7; j ++) {
					trtd += '<td id="day_'+n+'" Author="dtd"></td>';
					n++;
				}
				trtd += '</tr>';
			}
			return trtd
		}
		var calendar='<table class="yearMonth" Author="ymt"><tr Author="ymtr"><td onclick="calendar.changeDate(\'sub\')" Author="lt">&lt;</td><td Author="ymtd" style="cursor:default;"><span id="year" Author="y">'+theYear+'</span>-<span id="month" Author="m">'+theMonth+'</span></td><td onclick="calendar.changeDate(\'add\')" Author="gt">&gt;</td></tr></table>';
		calendar+='<table class="days" Author="days">';
		calendar+='<tr Author="ctr"><td Author="sund"><font color="#ff5500" Author="sundf">日</font></td><td Author="md">一</td><td Author="fr">二</td><td Author="wed">三</td><td Author="fred">四</td><td Author="frid">五</td><td Author="stad"><font color="#ff5500" Author="stadf">六</font></td></tr>'
		calendar+=this.days()+'</table>';
		return '<div id="calendar" class="calendar" Author="calendar">'+calendar+'</div>';
	}

	var isPassDay = function(day) {
		var theDate = new Date();
		var today = new Date(theDate.getFullYear(),theDate.getMonth(),theDate.getDate());
		if (day.getTime() < today.getTime() && t.passdayDisable) {
			return true;
		} else {
			return false;
		}
	}

	t.changeDate=function(type) {
		var date = new Date();
		var year = parseInt($('year').getInnerHTML());
		var month= parseInt($('month').getInnerHTML());
		if (type == 'add') {
			month += 1;
		} else {
			month -= 1;
		}
		if (month <= 0) {
			month = 12;
			year -= 1;
		} else if (month >= 12) {
			month = 1;
			year += 1;
		}
		$('year').setInnerHTML(year);
		$('month').setInnerHTML(month);
		t.bindDate(year+'-'+month+'-'+date.getDate());
	}
	t.selectDate=function(day) {
		t.obj.setValue(day);
		objTip.hiddenObjTip();
	}

	t.bindDate=function(date) {
		var _date;
		var _monthDays = new Array(31,30,31,30,31,30,31,31,30,31,30,31);
		if (!date) _date = new Date();
		else {
			var _arr = date.split('-');
			_date = new Date(_arr[0],_arr[1]-1,_arr[2]);
		}
		var _year = _date.getFullYear();
		var _month = _date.getMonth();
		var _day = 1;
		var _theDay = '';
		var _startDay = new Date(_year,_month,1).getDay();
		var _previYear = _month == 0 ? _year - 1 : _year;
		var _previMonth = _month == 0 ? 11 : _month - 1;
		var _previDay = _monthDays[_previMonth];
		if (_previMonth == 1) _previDay =((_previYear%4==0) && (_previYear%100!=0)||(_previYear%400==0))?29:28;
		_previDay -= _startDay - 1;
		var _nextDay = 1;
		_monthDays[1] = ((_year%4==0) && (_year%100!=0)||(_year%400==0))?29:28;
		var _curMonth = _month+1;
		for(var i = 0; i < 42; i ++) {
			var _dayElement = $('day_'+i);
			var _curDate = new Date(_year, _month, _day);
			_dayElement.setStyle('background', 'white');
			if(i >= new Date(_year,_month,1).getDay() && _day <= _monthDays[_month]) {
				if (isPassDay(_curDate)) {
					_theDay = '<font color="gray" Author="dayf">'+_day+'</font>';
					_dayElement.setStyle('cursor', 'default');
					_dayElement.onmouseover=Function('');
					_dayElement.onmouseout=Function('');
					_dayElement.onclick=Function('');
				} else {
					_theDay = _day;
					_dayElement.setStyle('cursor', 'pointer');
					_dayElement.onmouseover=Function('$("day_'+i+'").setStyle("background","#ccc")');
					_dayElement.onmouseout=Function('$("day_'+i+'").setStyle("background","white")');
					_dayElement.onclick=Function('calendar.selectDate("'+_year+'-'+_curMonth+'-'+_day+'")');
					if (_day == _date.getDate()) {
						_dayElement.setStyle('background', '#f0f0f0');
						_dayElement.onmouseover=Function('');
						_dayElement.onmouseout=Function('');
					}
				}
				_dayElement.setInnerHTML(_theDay);
				_day++;
			} else {
				_dayElement.setInnerHTML('');
				_dayElement.onmouseover=Function('');
				_dayElement.onmouseout=Function('');
			}
		}
	}

	t.show=function(src) {
		t.obj = $(src);
		objTip = new createObjTip(t.obj);
		objTip.content=createCalendar(t.obj.getValue());
		objTip.showObjTip();
		t.bindDate(t.obj.getValue());
	};

	t.hidden=function(evt){
		if (objTip && !eSrc(evt).getAttribute('Author') && eSrc(evt) != t.obj) objTip.hiddenObjTip();
	}
}
var calendar = new BjsCalendar;
