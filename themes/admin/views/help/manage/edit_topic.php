<?php

/**
 * Modal window for element field creation / edition
 *
 */

            ///  ChromePHP::log('topic',$topic);

            // NOTE IMPORTANT FIXME TODO - why is this needed in this version?  
            // The query has a join, but doesnt return a set of empty indexes/columns (for when 'create new') like it did in previous versions
     //       $_fix_fields = array('lang','title','subtitle','content','keywords','online','ordering','main_parent','keywords');
     //       foreach($_fix_fields as $ff){ if(!isset($topic[$ff])) $topic[$ff] = ''; }

$id = $topic['id_topic'];

$id_group = $topic['id_group'];

$rel = $id_group.$id;

$name = $topic['name'];

$title = $topic['title']; //( ($topic['title'] != '') ? $topic['title'] : $topic['name'] );

$subtitle = $topic['subtitle'];

//$rel = $id_group . '.' . $topic['id_topic'];

//$flat_rel = $id_group . 'x' .  $topic['id_topic'];

$status = (!$topic['online']) ? 'offline' : 'online' ;

$ordering = (isset($topic['ordering'])) ? $topic['ordering'] : 0;

$windowId = (!empty($windowId)) ? $windowId : 'editTopic'.$rel;

?>

<form name="<?php echo $windowId; ?>Form" class='<?php echo $windowId; ?>Form' id="<?php echo $windowId; ?>Form" action="<?php echo admin_url(); ?>help/save_topic">

	<!-- Hidden fields -->
	<input id="<?php echo $windowId; ?>_id_topic" name="id_topic" type="hidden" value="<?php echo $id; ?>" />
  <input id="<?php echo $windowId; ?>_ordering" name="ordering" type="hidden" value="<?php echo $ordering; ?>" />
  <input id="<?php echo $windowId; ?>_created" name="created" type="hidden" value="<?php echo $topic['created']; ?>" />
  <input id="<?php echo $windowId; ?>_online" name="online" type="hidden" value="<?php echo $topic['online']; ?>" />
	<input id="<?php echo $windowId; ?>_keywords" name="keywords" type="hidden" value="<?php echo $topic['keywords']; ?>" />
  <input id="<?php echo $windowId; ?>_origin_id_group" name="origin_id_group" type="hidden" value="<?php echo $topic['id_group']; ?>" />


  <!-- Group ID -->
  <?php /*
  <dl class="small ">
    <dt class=''>
      <label for="<?php echo $windowId; ?>_input_id_group" title=""><?php echo lang('ionize_label_group'); ?></label>
    </dt>
    <dd class='' style=''>
      <input id="<?php echo $windowId; ?>_input_id_group" name="id_group" class="inputtext required" type="text" value="<?php echo $topic['id_group']; ?>" />
    </dd>
  </dl>
  */ ?>

  <!-- Parent Group -->
  <dl class="small">
    <dt>
      <label class='right mt3' for="<?php echo $windowId; ?>_dd_id_group"><?php echo lang('ionize_label_parent'); ?></label>
    </dt>
    <dd>
      <div id="<?php echo $windowId; ?>_parentSelectContainer"></div>
    </dd>
  </dl>


	<!-- Name -->
	<dl class="small ">
		<dt class=''>
			<label for="<?php echo $windowId; ?>_name" title=""><?php echo lang('ionize_label_name'); ?></label>
		</dt>
		<dd class='' style=''>
			<input id="<?php echo $windowId; ?>_name" name="name" class="inputtext required" type="text" value="<?php echo $name; ?>" />
		</dd>
		
	</dl>
	

  <!-- Title -->
  <dl class="small ">
    <dt class=''>
      <label for="<?php echo $windowId; ?>_title" title=""><?php echo lang('ionize_label_title'); ?></label>
    </dt>
    <dd class='' style=''>
      <input id="<?php echo $windowId; ?>_title" name="title" class="inputtext" type="text" value="<?php echo $title; ?>" />
    </dd>
  </dl>
  
  <!-- Subtitle -->
  <dl class="small ">
    <dt class=''>
      <label for="<?php echo $windowId; ?>_subtitle" title=""><?php echo lang('ionize_label_subtitle'); ?></label>
    </dt>
    <dd class='' style=''>
      <input id="<?php echo $windowId; ?>_subtitle" name="subtitle" class="inputtext" type="text" value="<?php echo $subtitle; ?>" />
    </dd>
  </dl>
  


  <!-- Content -->
  <dl class="small ">
    <dt class=''>
      <label for="<?php echo $windowId; ?>_subtitle" title=""><?php echo lang('ionize_label_content'); ?></label>
    </dt>
    <dd class='' style=''>
      <textarea id="<?php echo $windowId; ?>_content" class="tinyTextarea inputtext h80" name="content"><?php echo $topic['content']; ?></textarea>
    </dd>
  </dl>
  


</form>


<!-- Save / Cancel buttons
	 Must be named bSave[windows_id] where 'window_id' is the used ID for the window opening through ION.formWindow()
--> 
<div class="buttons">
	<button id="bSave<?php echo $windowId; ?>" type="button" class="button yes right mr40"><?php echo lang('ionize_button_save_close'); ?></button>
	<button id="bCancel<?php echo $windowId; ?>"  type="button" class="button no right"><?php echo lang('ionize_button_cancel'); ?></button>
</div>

<script type="text/javascript">

	/**
	 * Init help tips on label
	 *
	 */
	ION.initLabelHelpLinks('#<?php echo $windowId; ?>Form');

  ION.initTinyEditors(null, '.<?php echo $windowId; ?>Form .tinyTextarea', 'small', {height:230});




  // Current & parent page ID
  var id_group = ($('<?php echo $windowId; ?>_origin_id_group').value) ? $('<?php echo $windowId; ?>_origin_id_group').value : '0';


  // Update when opened as sanity check for creating topics (callback in save not always reliable timing)
  if(id_group) ION.HTML('help/edit_group/'+id_group, {}, {'update': 'topic_definition_details'+id_group });
  // if(id_group) ION.HTML('help/get_topic_count/'+id_group, {}, {'update': 'topic_group_count'+id_group });
  
  // ION.HTML('help/get_manage_list', {}, {'update': 'manageListContainer' });

  if( $('<?php echo $windowId; ?>_parentSelectContainer') ) // CHANGED sanity check for $('id_menu')
  {
    var id_topic = ($('<?php echo $windowId; ?>_id_topic')) ? $('<?php echo $windowId; ?>_id_topic').value : '0';
    ION.HTML(
      admin_url + 'help/get_parents_select',
      {'windowId' : '<?php echo $windowId; ?>','id_topic': id_topic,'id_group': id_group,'rel':'<?php echo $rel; ?>'},
      {'update': '<?php echo $windowId; ?>_parentSelectContainer'}
    );
  }

</script>

