<?php /* Smarty version 2.6.22, created on 2009-07-28 23:54:42
         compiled from E:%5Cwamp%5Cwww%5Ccare2x%5Cinstaller/templates/collect_data.tpl */ ?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>
" method="POST">
<table id="fields" align="center" border=0 cellspacing=0 cellpadding=0>
<?php echo $this->_tpl_vars['FORM_FIELDS']; ?>

<tr>
    <td id="field_save"><input id="button" type="submit" value="Save"></td>
    <td id="field_reset"><input id="button" type="reset" value="Reset"></td>
</tr>
</table>
</form>