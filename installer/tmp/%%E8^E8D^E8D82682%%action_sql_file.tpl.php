<?php /* Smarty version 2.6.22, created on 2009-07-28 23:57:58
         compiled from E:%5Cwamp%5Cwww%5Ccare2x%5Cinstaller/templates/action_sql_file.tpl */ ?>
<tr><td>&nbsp;</td></tr>
<table align="center">
<?php if ($this->_tpl_vars['loop'] < 2): ?>
<tr><td align="center">The database file is being installed.</td></tr>
<tr><td align="center"><img src="images/animated_progress.gif"/></td></tr>
<?php elseif ($this->_tpl_vars['loop'] == 3): ?>
<tr><td align="center">Database installation complete.</td></tr>
<?php endif; ?>
</table>