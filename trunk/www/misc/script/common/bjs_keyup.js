/**
 * bjs_keyup.js
 * @author Junbo Bao <baojunbo@gmail.com>
 * @version 1.0.0
 */
function BjsKeyup() {
	var t=this;
	var src=null
	t.getInputValue=null
	t.keydown=null
	t.keyupInput=function(src) {
		t.src=src;
		if (isIE) {
			if (t.getInputValue) {
				$(src).onkeyup = t.getInputValue;
			}
		} else {
			var intervalName;
			if (t.getInputValue) {
				$(src).addEvent('input',t.getInputValue,false);
				$(src).onfocus = function(){
					// intervalName = setInterval(handle, 1000);
				};
				$(src).onblur = function(){
					// clearInterval(intervalName);
				};
			}
		}
	}
}
var keyup = new BjsKeyup;
