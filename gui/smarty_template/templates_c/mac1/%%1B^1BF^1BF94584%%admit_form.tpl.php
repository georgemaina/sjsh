<?php /* Smarty version 2.6.22, created on 2009-08-14 12:14:22
         compiled from registration_admission/admit_form.tpl */ ?>

	<?php if ($this->_tpl_vars['bSetAsForm']): ?>
	<form method="post" action="<?php echo $this->_tpl_vars['thisfile']; ?>
" name="aufnahmeform" onSubmit="return chkform(this)">
	<?php endif; ?>
		
		<table border="0" cellspacing=1 cellpadding=0 width="100%">

		<?php if ($this->_tpl_vars['error']): ?>
				<tr>
					<td colspan=4 class="warnprompt">
						<center>
						<?php echo $this->_tpl_vars['sMascotImg']; ?>

						<?php echo $this->_tpl_vars['LDError']; ?>

						</center>
					</td>
				</tr>
		<?php endif; ?>

		<?php if ($this->_tpl_vars['is_discharged']): ?>
				<tr>
					<td bgcolor="red" colspan="3">
						&nbsp;
						<?php echo $this->_tpl_vars['sWarnIcon']; ?>

						<font color="#ffffff">
						<b>
						<?php echo $this->_tpl_vars['sDischarged']; ?>

						</b>
						</font>
					</td>
				</tr>
		<?php endif; ?>

				<tr>
					<td  class="adm_item">
						<?php echo $this->_tpl_vars['LDCaseNr']; ?>

					</td>
					<td class="adm_input">
						<?php echo $this->_tpl_vars['encounter_nr']; ?>

						<br>
						<?php echo $this->_tpl_vars['sEncBarcode']; ?>
 <?php echo $this->_tpl_vars['sHiddenBarcode']; ?>

					</td>
					<td <?php echo $this->_tpl_vars['sRowSpan']; ?>
 align="center" class="photo_id">
						<?php echo $this->_tpl_vars['img_source']; ?>

					</td>
				</tr>

				<tr>
					<td  class="adm_item">
						<?php echo $this->_tpl_vars['LDAdmitDate']; ?>
:
					</td>
					<td class="adm_input">
						<?php echo $this->_tpl_vars['sAdmitDate']; ?>

					</td>
				</tr>

				<tr>
					<td class="adm_item">
					<?php echo $this->_tpl_vars['LDAdmitTime']; ?>
:
					</td>
					<td class="adm_input">
						<?php echo $this->_tpl_vars['sAdmitTime']; ?>

					</td>
				</tr>

				<tr>
					<td class="adm_item">
						<?php echo $this->_tpl_vars['LDTitle']; ?>
:
					</td>
					<td class="adm_input">
						<?php echo $this->_tpl_vars['title']; ?>

					</td>
				</tr>

				<tr>
					<td class="adm_item">
						<?php echo $this->_tpl_vars['LDLastName']; ?>
:
					</td>
					<td bgcolor="#ffffee" class="vi_data"><b>
						<?php echo $this->_tpl_vars['name_last']; ?>
</b>
					</td>
				</tr>

				<tr>
					<td class="adm_item">
						<?php echo $this->_tpl_vars['LDFirstName']; ?>
:
					</td>
					<td bgcolor="#ffffee" class="vi_data">
						<?php echo $this->_tpl_vars['name_first']; ?>
 &nbsp; <?php echo $this->_tpl_vars['sCrossImg']; ?>

					</td>
				</tr>

			<?php if ($this->_tpl_vars['name_2']): ?>
				<tr>
					<td class="adm_item">
						<?php echo $this->_tpl_vars['LDName2']; ?>
:
					</td>
					<td bgcolor="#ffffee">
						<?php echo $this->_tpl_vars['name_2']; ?>

					</td>
				</tr>
			<?php endif; ?>

			<?php if ($this->_tpl_vars['name_3']): ?>
				<tr>
					<td class="adm_item">
						<?php echo $this->_tpl_vars['LDName3']; ?>
:
					</td>
					<td bgcolor="#ffffee">
						<?php echo $this->_tpl_vars['name_3']; ?>

					</td>
				</tr>
			<?php endif; ?>

			<?php if ($this->_tpl_vars['name_middle']): ?>
				<tr>
					<td class="adm_item">
						<?php echo $this->_tpl_vars['LDNameMid']; ?>
:
					</td>
					<td bgcolor="#ffffee">
						<?php echo $this->_tpl_vars['name_middle']; ?>

					</td>
				</tr>
			<?php endif; ?>

				<tr>
					<td class="adm_item">
						<?php echo $this->_tpl_vars['LDBday']; ?>
:
					</td>
					<td bgcolor="#ffffee" class="vi_data">
						<?php echo $this->_tpl_vars['sBdayDate']; ?>
 &nbsp; <?php echo $this->_tpl_vars['sCrossImg']; ?>
 &nbsp; <font color="black"><?php echo $this->_tpl_vars['sDeathDate']; ?>
</font>
					</td>
					<td bgcolor="#ffffee">
						<?php echo $this->_tpl_vars['LDSex']; ?>
: <?php echo $this->_tpl_vars['sSexType']; ?>

					</td>
				</tr>

			<?php if ($this->_tpl_vars['LDBloodGroup']): ?>
				<tr>
					<td class="adm_item">
						<?php echo $this->_tpl_vars['LDBloodGroup']; ?>
:
					</td>
					<td class="adm_input" colspan=2>&nbsp;
						<?php echo $this->_tpl_vars['blood_group']; ?>

					</td>
				</tr>
			<?php endif; ?>

				<tr>
					<td class="adm_item">
						<?php echo $this->_tpl_vars['LDAddress']; ?>
:
					</td>
					<td colspan=2 class="adm_input">
						<?php echo $this->_tpl_vars['addr_str']; ?>
  <?php echo $this->_tpl_vars['addr_str_nr']; ?>

						<br>
						<?php echo $this->_tpl_vars['addr_zip']; ?>
 <?php echo $this->_tpl_vars['addr_citytown_name']; ?>

					</td>
				</tr>

				<tr>
					<td class="adm_item">
						<font color="red"><?php echo $this->_tpl_vars['LDAdmitClass']; ?>
</font>:
					</td>
					<td colspan=2 class="adm_input">
						<?php echo $this->_tpl_vars['sAdmitClassInput']; ?>

					</td>
				</tr>
			<?php if ($this->_tpl_vars['LDWard']): ?>
				<tr>
					<td class="adm_item">
						<font color="red"><?php echo $this->_tpl_vars['LDWard']; ?>
</font>:
					</td>
					<td colspan=2 class="adm_input">
						<?php echo $this->_tpl_vars['sWardInput']; ?>

					</td>
				</tr>
			<?php endif; ?>

			<?php if ($this->_tpl_vars['LDDepartment']): ?>
				<tr>
					<td class="adm_item">
						<font color="red"><?php echo $this->_tpl_vars['LDDepartment']; ?>
</font>:
					</td>
					<td colspan=2 class="adm_input">
						<?php echo $this->_tpl_vars['sDeptInput']; ?>

					</td>
				</tr>
			<?php endif; ?>
			
				<tr>
					<td class="adm_item">
						<?php echo $this->_tpl_vars['LDDiagnosis']; ?>
:
					</td>
					<td colspan=2 class="adm_input">
						<?php echo $this->_tpl_vars['referrer_diagnosis']; ?>

					</td>
				</tr>
				<tr>
					<td class="adm_item">
						<?php echo $this->_tpl_vars['LDRecBy']; ?>
:
					</td>
					<td colspan=2 class="adm_input">
						<?php echo $this->_tpl_vars['referrer_dr']; ?>

					</td>
				</tr>
				<tr>
					<td class="adm_item">
						<?php echo $this->_tpl_vars['LDTherapy']; ?>
:
					</td>
					<td colspan=2 class="adm_input">
						<?php echo $this->_tpl_vars['referrer_recom_therapy']; ?>

					</td>
				</tr>
				<tr>
					<td class="adm_item">
						<?php echo $this->_tpl_vars['LDSpecials']; ?>
:
					</td>
					<td colspan=2 class="adm_input">
						<?php echo $this->_tpl_vars['referrer_notes']; ?>

					</td>
				</tr>

				<!-- The insurance class  -->
				<tr>
					<td class="adm_item">
						<?php echo $this->_tpl_vars['LDBillType']; ?>
:
					</td>
					<td colspan=2 class="adm_input">
						<?php echo $this->_tpl_vars['sBillTypeInput']; ?>

					</td>
				</tr>

				<tr>
					<td class="adm_item">
						<?php echo $this->_tpl_vars['LDInsuranceNr']; ?>
:
					</td>
					<td colspan=2 class="adm_input">
						<?php echo $this->_tpl_vars['insurance_nr']; ?>

					</td>
				</tr>
				<tr>
					<td class="adm_item">
						<?php echo $this->_tpl_vars['LDInsuranceCo']; ?>
:
					</td>
					<td colspan=2 class="adm_input">
						<?php echo $this->_tpl_vars['insurance_firm_name']; ?>

					</td>
				</tr>
			<?php if ($this->_tpl_vars['LDCareServiceClass']): ?>
				<tr>
					<td class="adm_item">
						<?php echo $this->_tpl_vars['LDCareServiceClass']; ?>
:
					</td>
					<td colspan=2 class="adm_input">
						<?php echo $this->_tpl_vars['sCareServiceInput']; ?>
 <?php echo $this->_tpl_vars['LDFrom']; ?>
 <?php echo $this->_tpl_vars['sCSFromInput']; ?>
 <?php echo $this->_tpl_vars['LDTo']; ?>
 <?php echo $this->_tpl_vars['sCSToInput']; ?>
 <?php echo $this->_tpl_vars['sCSHidden']; ?>

					</td>
				</tr>
			<?php endif; ?>

			<?php if ($this->_tpl_vars['LDRoomServiceClass']): ?>
				<tr>
					<td class="adm_item">
						<?php echo $this->_tpl_vars['LDRoomServiceClass']; ?>
:
					</td>
					<td colspan=2 class="adm_input">
						<?php echo $this->_tpl_vars['sCareRoomInput']; ?>
 <?php echo $this->_tpl_vars['LDFrom']; ?>
 <?php echo $this->_tpl_vars['sRSFromInput']; ?>
 <?php echo $this->_tpl_vars['LDTo']; ?>
 <?php echo $this->_tpl_vars['sRSToInput']; ?>
 <?php echo $this->_tpl_vars['sRSHidden']; ?>

					</td>
				</tr>
			<?php endif; ?>
			
			<?php if ($this->_tpl_vars['LDAttDrServiceClass']): ?>
				<tr>
					<td class="adm_item">
						<?php echo $this->_tpl_vars['LDAttDrServiceClass']; ?>
:
					</td>
					<td colspan=2 class="adm_input">
						<?php echo $this->_tpl_vars['sCareDrInput']; ?>
 <?php echo $this->_tpl_vars['LDFrom']; ?>
 <?php echo $this->_tpl_vars['sDSFromInput']; ?>
 <?php echo $this->_tpl_vars['LDTo']; ?>
 <?php echo $this->_tpl_vars['sDSToInput']; ?>
 <?php echo $this->_tpl_vars['sDSHidden']; ?>

					</td>
				</tr>
			<?php endif; ?>

				<tr>
					<td class="adm_item">
						<?php echo $this->_tpl_vars['LDAdmitBy']; ?>
:
					</td>
					<td colspan=2 class="adm_input">
						<?php echo $this->_tpl_vars['encoder']; ?>

					</td>
				</tr>

				<?php echo $this->_tpl_vars['sHiddenInputs']; ?>


				<tr>
					<td colspan="3">
						&nbsp;
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $this->_tpl_vars['pbSave']; ?>

					</td>
					<td align="right">
						<?php echo $this->_tpl_vars['pbRefresh']; ?>
 <?php echo $this->_tpl_vars['pbRegData']; ?>

					</td>
					<td align="right">
						<?php echo $this->_tpl_vars['pbCancel']; ?>

					</td>
				</tr>

		</table>
	
			<?php echo $this->_tpl_vars['sErrorHidInputs']; ?>

			<?php echo $this->_tpl_vars['sUpdateHidInputs']; ?>


	<?php if ($this->_tpl_vars['bSetAsForm']): ?>
	</form>
	<?php endif; ?>

	<?php echo $this->_tpl_vars['sNewDataForm']; ?>

	<p>