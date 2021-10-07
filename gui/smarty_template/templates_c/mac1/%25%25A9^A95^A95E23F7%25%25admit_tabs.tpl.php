<?php /* Smarty version 2.6.22, created on 2009-08-13 12:32:28
         compiled from registration_admission/admit_tabs.tpl */ ?>

<table width="100%" cellspacing="0" cellpadding="0">
  <tbody>
  <?php if ($this->_tpl_vars['bShowTabs']): ?>
    <tr>
      <td height=24><div align="center"><?php echo $this->_tpl_vars['pbNew']; ?>
<?php echo $this->_tpl_vars['pbSearch']; ?>
<?php echo $this->_tpl_vars['pbAdvSearch']; ?>
<?php echo $this->_tpl_vars['sHSpacer']; ?>
<?php echo $this->_tpl_vars['pbSwitchMode']; ?>
</div></td>
    </tr>
  <?php endif; ?>
    <tr>
      <td <?php echo $this->_tpl_vars['sRegDividerClass']; ?>
><img src="p.gif" border=0 width=1 height=5><?php echo $this->_tpl_vars['sSubTitle']; ?>
<?php echo $this->_tpl_vars['sWarnText']; ?>
</td>
    </tr>
  </tbody>
</table>