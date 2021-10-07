<?php /* Smarty version 2.6.22, created on 2009-08-06 14:30:32
         compiled from C:%5Cxampp%5Chtdocs%5Ccare2x%5Cinstaller/templates/action_sql_options.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'C:\\xampp\\htdocs\\care2x\\installer/templates/action_sql_options.tpl', 9, false),)), $this); ?>
<tr><td>&nbsp;</td></tr>
<table align="center">
<?php if ($this->_tpl_vars['loop'] != 3): ?>
<tr><td align="center">Choose optional database tables for installation:</td></tr>
<table align="center">
<?php $_from = $this->_tpl_vars['files']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['sqloptions'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['sqloptions']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['name'] => $this->_tpl_vars['file']):
        $this->_foreach['sqloptions']['iteration']++;
?>
<tr><td align="left"><input id="radio" type="radio" name="optfile" value="<?php echo $this->_tpl_vars['file']; ?>
"
<?php if (($this->_foreach['sqloptions']['iteration'] <= 1)): ?> checked<?php endif; ?>
><?php echo ((is_array($_tmp=$this->_tpl_vars['name'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</td></tr>
<?php endforeach; endif; unset($_from); ?>
</table>
<br>
<tr><td>&nbsp;</td></tr>
<tr><td align="center"><input id="button" type="submit" name="install_sql" value="Install">&nbsp;
<input id="button" type="submit" name="install_sql_done" value="Done"></td></tr>
<?php else: ?>
<tr><td align="center">The database table is being installed</td></tr>
<tr><td align="center"><img src="images/animated_progress.gif"/></td></tr>
<?php endif; ?>
</table>
