<?php /* Smarty version 2.6.22, created on 2009-08-06 14:30:20
         compiled from C:%5Cxampp%5Chtdocs%5Ccare2x%5Cinstaller/templates/action_complete.tpl */ ?>
<tr><td valign="top"><?php if ($this->_tpl_vars['ACTION']->getResult() == @INSTALLER_TEST_SUCCESS): ?>
<img src='images/green_check.gif'>
<?php elseif ($this->_tpl_vars['ACTION']->getResult() == @INSTALLER_TEST_WARNING): ?>
<img src='images/yellow_check.gif'>
<?php else: ?>
<img src='images/red_check.gif'>
<?php endif; ?></td>
<td align="left" valign="bottom"><?php echo $this->_tpl_vars['ACTION']->getResultMessage(); ?>
</td>
</tr>