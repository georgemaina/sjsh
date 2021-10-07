<?php /* Smarty version 2.6.22, created on 2009-07-28 23:54:42
         compiled from E:%5Cwamp%5Cwww%5Ccare2x%5Cinstaller/templates/field_select.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'E:\\wamp\\www\\care2x\\installer/templates/field_select.tpl', 5, false),)), $this); ?>
<tr>
    <td id="field_label"><?php echo $this->_tpl_vars['field']->label; ?>
</td>
    <td id="field_value">
        <select name='FIELDS[<?php echo $this->_tpl_vars['field']->name; ?>
]'>
            <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['field']->values,'output' => $this->_tpl_vars['field']->options,'selected' => $this->_tpl_vars['field']->default), $this);?>

        </select>
    </td>
</tr>