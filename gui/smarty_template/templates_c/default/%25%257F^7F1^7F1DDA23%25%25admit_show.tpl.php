<?php /* Smarty version 2.6.22, created on 2011-10-23 11:30:07
         compiled from registration_admission/admit_show.tpl */ ?>

<table width="100%" cellspacing="0" cellpadding="0">
  <tbody>
 <div id="hello-win" class="x-hidden"></div>
    <tr>
      <td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "registration_admission/admit_tabs.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
    </tr>

    <tr>
      <td>
			<table cellspacing="0" cellpadding="0" width=800>
			<tbody>
				<tr valign="top">
					<td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "registration_admission/admit_form.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
					<td><?php echo $this->_tpl_vars['sAdmitOptions']; ?>
</td>
				</tr>
			</tbody>
			</table>
	  </td>
    </tr>

	<tr>
      <td valign="top">
	  	<?php echo $this->_tpl_vars['sAdmitBottomControls']; ?>
 <?php echo $this->_tpl_vars['pbBottomClose']; ?>

	</td>
    </tr>

    <tr>
      <td>
	  	&nbsp;
		<br>
	  	<?php echo $this->_tpl_vars['sAdmitLink']; ?>

		<br>
		<?php echo $this->_tpl_vars['sSearchLink']; ?>

		<br>
		<?php echo $this->_tpl_vars['sArchiveLink']; ?>

                <br>
		<input type="button" id="show-btn" value="Update Dates" />
               
	</td>
    </tr>

  </tbody>
</table>