<?php

/**
 * View used by Page and Article controller to display parent list (pages) in the parents select dropdown
 * When the selected menu is changed, the page parent select dropdown is reloaded
 *
 */

$parent_name = 'id_group';


?>

<?php 
// NOTE - was $my_isAdmin as first param
  echo my_form_dropdown(TRUE, $parent_name, $groups, array($id_selected), $extra = ' id="'.$windowId.'_dd_'.$parent_name.'" class="select"'); 
 /// ChromePHP::log('help/manage/parent_select form debug',array(TRUE, $parent_name, $groups, array($id_selected),' id="'.$windowId.'_dd_'.$parent_name.'" class="select w210"'));
?>


<script type="text/javascript">

	if (Browser.ie || (Browser.firefox && Browser.version < 4))
	{
		var selected = $('<?php echo $windowId; ?>_dd_<?php echo $parent_name; ?>').getElement('option[selected=selected]');
		selected.setProperty('selected', 'selected');

		if ('<?php echo $parent_name; ?>' == 'id_group')
		{
			if ($('<?php echo $windowId; ?>_origin_<?php echo $parent_name; ?>').value == '0')
				$('<?php echo $windowId; ?>_dd_<?php echo $parent_name; ?>').getFirst('option').setProperty('selected', 'selected');
		}
	}
</script>