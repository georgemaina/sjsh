<?php 
/* 
*    Writed by Setec Astronomy - setec@freemail.it 
* 
*    This script is distributed  under the GPL License 
* 
*    This program is distributed in the hope that it will be useful, 
*    but WITHOUT ANY WARRANTY; without even the implied warranty of 
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
*    GNU General Public License for more details. 
* 
*    http://www.gnu.org/licenses/gpl.txt 
* 
*/ 

class CLinkedSelect
{ 
    var $primaryFormName; 
    var $secondaryFormName; 
    var $primaryFieldName; 
    var $secondaryFieldName; 
	var $fieldValues;    
     
    function CLinkedSelect  () 
    {
		$this->primaryFormName = ""; 
		$this->secondaryFormName = ""; 
		$this->primaryFieldName = ""; 
		$this->secondaryFieldName = "";  
		$this->fieldValues = array ();  
	}

	function _safe_set (&$var_true, $var_false="")
	{
		if (!isset ($var_true))
		{ $var_true = $var_false; }
		return $var_true;
	}
	
	function get_function_js ()
	{
		ob_start ();
?>
<script language="JavaScript" type="text/JavaScript">
<!--
function <?php print ($this->get_function_name ()); ?> (code_item)
{
	// clear secondary_field	
	var secondary_field_length = document.<?php print ($this->secondaryFormName); ?>.<?php print ($this->secondaryFieldName); ?>.length;
	for (i = secondary_field_length - 1; i >=0; i--) {
		document.<?php print ($this->secondaryFormName); ?>.<?php print ($this->secondaryFieldName); ?>.options[i] = null;
	}

	var primary_field_index = document.<?php print ($this->primaryFormName); ?>.<?php print ($this->primaryFieldName); ?>.selectedIndex;

<?php 
	foreach ($this->fieldValues as $list)
	{
		$this->_safe_set ($list["value"], "");
		$this->_safe_set ($list["text"], "");
		$this->_safe_set ($list["selected"], false);
		$this->_safe_set ($list["items"], array ());
?>
	if (document.<?php print ($this->primaryFormName); ?>.<?php print ($this->primaryFieldName); ?>.options[primary_field_index].value == '<?php print ($list["value"]); ?>') {
		document.<?php print ($this->secondaryFormName); ?>.<?php print ($this->secondaryFieldName); ?>.length = <?php print (count ($list["items"])); ?>;
<?php 
		$i = 0;
		foreach ($list["items"] as $value => $text) 
		{
?>
			document.<?php print ($this->secondaryFormName); ?>.<?php print ($this->secondaryFieldName); ?>.options[<?php print ($i); ?>].value = "<?php print (addslashes ($value)); ?>";
			document.<?php print ($this->secondaryFormName); ?>.<?php print ($this->secondaryFieldName); ?>.options[<?php print ($i); ?>].text = "<?php print (addslashes ($text)); ?>";
<?php
			$i++;
		}  // foreach ($items as $item) 
?>		
	} // if (document.f<?php print ($this->primaryFormName); ?>.<?php print ($this->primaryFieldName); ?>.options[primary_field_index].value == '<?php print ($value["value"]); ?>') 
<?php		
	} // foreach ($values as $value)
?>	
}
//-->
</script>
<?php
		$result = ob_get_contents ();
		ob_end_clean ();	
		return $result;
	}

	function get_function_name ()
	{
		if (empty ($this->primaryFormName) || empty ($this->primaryFieldName))
		{ return false; }
		return "Modify" . ucfirst ($this->primaryFormName) . ucfirst ($this->primaryFieldName);
	}

	function get_reset_js ()
	{
		if (empty ($this->primaryFormName) || empty ($this->primaryFieldName))
		{ return false; }

		ob_start ();
?>
<script language="JavaScript" type="text/JavaScript">
<!--
	<?php print ($this->get_function_name ()); ?> (-1);
//-->
</script>
<?php
		$result = ob_get_contents ();
		ob_end_clean ();	
		return $result;
	}
	
	function get_primary_field ($attributes = "")
	{
		if (empty ($this->primaryFieldName) || empty ($this->fieldValues))
		{ return false; }

		ob_start ();
?>		
<select onChange="<?php print ($this->get_function_name ()); ?>(-1)" name="<?php print ($this->primaryFieldName); ?>"<?php print (" " . $attributes); ?>>
<?php
	foreach ($this->fieldValues as $list)
	{
		$this->_safe_set ($list["value"], "");
		$this->_safe_set ($list["text"], "");
		$this->_safe_set ($list["selected"], false);

		$attribute = "";
		if ($list["selected"])
		{ $attribute = " selected"; }

		print ("	<option value=\"" . $list["value"] . "\"" . $attribute . ">" . $list["text"] . "</option>\n");
	} // foreach ($this->fieldValues as $list)
?>
</select>
<?php
		$result = ob_get_contents ();
		ob_end_clean ();	
		return $result;
	}
	
	function get_secondary_field ($attributes = "", $default_value = "-1", $default_caption = "")
	{
		if (empty ($this->secondaryFieldName))
		{ return false; }

		ob_start ();
?>		
<select name="<?php print ($this->secondaryFieldName); ?>"<?php print (" " . $attributes); ?>>
<?php
	if (!empty ($default_caption))
	{ print ("	<option value=\"" . $default_value . "\">" . $default_caption . "</option>\n"); }
?>
</select>
<?php
		$result = ob_get_contents ();
		ob_end_clean ();	
		return $result;
	}
}
?>