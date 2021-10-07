<?php /* Smarty version 2.6.22, created on 2017-10-03 11:56:44
         compiled from nursing/discharge_patient_form.tpl */ ?>

<ul>

<div class="prompt"><?php echo $this->_tpl_vars['sPrompt']; ?>
</div>
    <div class="balance" id="balance"><?php echo $this->_tpl_vars['sBalance']; ?>
</div>

<form action="<?php echo $this->_tpl_vars['thisfile']; ?>
" name="discform" method="post" onSubmit="return pruf(this)">

	<table border=0 cellspacing="1">
		<tr>
			<td colspan=2 class="adm_input">
				<?php echo $this->_tpl_vars['sBarcodeLabel']; ?>
 <?php echo $this->_tpl_vars['img_source']; ?>

			</td>
            <td<?php echo $this->_tpl_vars['LDHasBalance']; ?>
></td>
		</tr>
		<tr>
			<td class="adm_item"><?php echo $this->_tpl_vars['LDLocation']; ?>
:</td>
			<td class="adm_input"><?php echo $this->_tpl_vars['sLocation']; ?>
</td>
            <td></td>
		</tr>
			<td class="adm_item"><?php echo $this->_tpl_vars['LDDate']; ?>
:</td>
			<td class="adm_input">
				<?php if ($this->_tpl_vars['released']): ?>
					<?php echo $this->_tpl_vars['x_date']; ?>

				<?php else: ?>
					<?php echo $this->_tpl_vars['sDateInput']; ?>
 <?php echo $this->_tpl_vars['sDateMiniCalendar']; ?>

				<?php endif; ?>
			</td>
            <td></td>
		</tr>
		<tr>
			<td class="adm_item"><?php echo $this->_tpl_vars['LDClockTime']; ?>
:</td>
			<td class="adm_input">
				<?php if ($this->_tpl_vars['released']): ?>
					<?php echo $this->_tpl_vars['x_time']; ?>

				<?php else: ?>
					<?php echo $this->_tpl_vars['sTimeInput']; ?>

				<?php endif; ?>
			</td>
            <td></td>
		</tr>
		<tr>
			<td class="adm_item"><?php echo $this->_tpl_vars['LDReleaseType']; ?>
:</td>
			<td class="adm_input">
				<?php echo $this->_tpl_vars['sDischargeTypes']; ?>

			</td>
            <td></td>
		</tr>
		<tr>
			<td class="adm_item"><?php echo $this->_tpl_vars['LDNotes']; ?>
:</td>
			<td class="adm_input">
				<textarea rows="5" cols="40" name="info"></textarea>

            </td>
            <td></td>
		</tr>
		<tr>
			<td class="adm_item"><?php echo $this->_tpl_vars['LDNurse']; ?>
:</td>
			<td class="adm_input">
				<?php if ($this->_tpl_vars['released']): ?>
					<?php echo $this->_tpl_vars['encoder']; ?>

				<?php else: ?>
					<input type="text" name="encoder" size=25 maxlength=30 value="<?php echo $this->_tpl_vars['encoder']; ?>
">
				<?php endif; ?>
			</td>
            <td></td>
		</tr>

	<?php if ($this->_tpl_vars['bShowValidator']): ?>
		<tr>
			<td class="adm_item"><?php echo $this->_tpl_vars['pbSubmit']; ?>
</td>
			<td class="adm_input"><?php echo $this->_tpl_vars['sValidatorCheckBox']; ?>
 <?php echo $this->_tpl_vars['LDYesSure']; ?>
</td>
            <td></td>
		</tr>
	<?php endif; ?>

	</table>

	<?php echo $this->_tpl_vars['sHiddenInputs']; ?>


</form>

<?php echo $this->_tpl_vars['pbCancel']; ?>

<?php echo $this->_tpl_vars['pbSummary']; ?>


</ul>