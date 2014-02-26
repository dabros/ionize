

<ul id="help_manage_list" class="sortable-container mt10 mb50">

<?php
?>

<?php foreach($topic_list as $tgk=>$topic_group) : ?>
  <h3>Topic Group <?php echo $tgk; ?></h3>
  <ul class='topic_group' id='topic_group<?php echo $tgk; ?>'>
    <?php foreach($topic_group['children'] as $topic) : ?>
    	<?php

      $title = ( ($topic['title'] != '') ? $topic['title'] : $topic['name'] );
    	
    	$rel = $topic['id_parent'] . '.' . $topic['id_topic'];

    	$flat_rel = $topic['id_parent'] . 'x' .  $topic['id_topic'];
    	
    	$status = (!$topic['online']) ? 'offline' : 'online' ;

    	?>

    	<li id="topic<?php echo $topic['id_topic']; ?>" class="sortme topic<?php echo $topic['id_topic']; ?> topic<?php echo $flat_rel; ?> <?php echo $status ;?>" rel="<?php echo $rel; ?>">
    		

    		<!-- Drag icon -->
    		<span class="icon left drag mr5"></span>
    		<!-- Status icon -->
    		<a class="icon right mr5 status topic<?php echo $topic['id_topic']; ?> topic<?php echo $flat_rel; ?> <?php echo $status ;?>" rel="<?php echo $rel; ?>"></a>

    		<!-- Unlink icon -->
    		<a class="icon right mr5 unlink" rel="<?php echo $rel; ?>" title="<?php echo lang('ionize_label_unlink'); ?>"></a>


    	
        <!-- Main parent -->
        <?php if( isset($topic['parents']) && count($topic['parents']) > 1 ) :?>
          <span class="right type-block" rel="<?php echo $rel; ?>">
            
            <select id="amp<?php echo $flat_rel; ?>" class="select w100 parent left" style="padding:0;" rel="<?php echo $rel; ?>">
              <?php foreach($topic['parents'] as $parent) :?>
                <option <?php if($topic['id_main_parent'] == $parent) :?>selected="selected"<?php endif; ?> value="<?php echo $parent; ?>"><?php echo $parent; ?></option>
              <?php endforeach; ?>
            </select>
      
          </span>
        <?php endif ;?>
    		<!-- Title (draggable) -->
    		<a style="overflow:hidden;height:16px;display:block;" class=" pl5 pr10 topic topic<?php echo $flat_rel; ?> <?php echo $status ;?>" title="<?php echo lang('ionize_label_edit'); ?> / <?php echo lang('ionize_label_drag_to_page'); ?>" rel="<?php echo $rel; ?>"><?php echo $title; ?></a>
    	</li>

    <?php endforeach ;?>
  </ul>
<?php endforeach ;?>

</ul>

<script type="text/javascript">


	/**
	 * itemManager
	 * NOTE - this controls important behaivour such as sortable, find in javascript/ionize/ionize_itemsmanager.js
	 */
    topicManager = new ION.ItemManager(
    {
      parent:   '',
      element:  'topic',
      container:  'help_manage_list'
    });
	
</script>
