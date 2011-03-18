<?php /* Smarty version 2.6.20, created on 2011-03-09 11:39:07
         compiled from index.htm */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['webUrl']; ?>
/misc/script/common/bjs_dialog.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['webUrl']; ?>
/misc/script/common/bjs_ajax.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['webUrl']; ?>
/misc/script/common/bjs_calendar.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['webUrl']; ?>
/misc/script/common/bjs_keyup.js"></script>
<script type="text/javascript">
	function dialog() {
		var dialog = new BjsDialog();
		// dialog.showMsg('dialog title', 'hi', 'okay');
		dialog.showChoose('dialog title', '<font><strong>hi</strong></font>', 'okay', 'cancle');
		dialog.onconfirm=function() {
			dialog.setDialogTitle('set dialog title');
			dialog.setDialogContent('hello dialog!');
			dialog.setConfirmButton('Next Step');
			// ajax();
		}
	}
	function ajax() {
		var ajax = new BjsAjax('loadingBar'); // with loading bar
		// var ajax = new BjsAjax(); // with out loading bar
		var params={'uName':'baojunbo'};
		// ajax.method='get';
		ajax.send('index.php?mod=user', params);
		ajax.ondone = function(data) {
			alert(data);
		}
	}

	keyup.getInputValue=function() {
		var tmp = $(keyup.src).getValue();
		$('jsinput').setValue(tmp);
		if (tmp) {
			var ajax = new BjsAjax;
			var params = {'p':tmp};
			ajax.method='get';
			ajax.send('index.php', params);
			ajax.ondone=function(data){
			}
		}
	}
	keyup.keydown=function(e){
		$('jsinput').setValue(e.keyCode);
	}
</script>
<title>BFrameWork for PHP</title>
</head>
<body>
	<div>
		<div><?php echo $this->_tpl_vars['uName']; ?>
</div>
		<!-- <div><table><tr><?php $_from = $this->_tpl_vars['user']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?><th><?php echo $this->_tpl_vars['key']; ?>
</th><td><?php echo $this->_tpl_vars['item']; ?>
</td><?php endforeach; endif; unset($_from); ?></tr></table></div> -->
		<div>
			<a href="javascript:void(0)" onclick="dialog();">dialog</a>
			<a href="javascript:void(0)" onclick="ajax();">ajax</a>
		</div>
		<div><input id="date1" type="text" value="" onclick="calendar.show('date1');" /></div>
		<div><input id="date" type="text" value="" onclick="calendar.show('date');" /></div>
		<div><input id="input1" type="text" value="" autocomplete="off" onfocus="keyup.keyupInput('input1')"/><input id="input" type="text" value="" autocomplete="off" onfocus="keyup.keyupInput('input')"/><input id="jsinput" type="text"></div>
	</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>