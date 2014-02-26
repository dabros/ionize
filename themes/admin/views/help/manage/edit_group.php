<?php
// NOTE used as templates for each topic in the topic list

$parent = $topic_group['parent'];
$children = $topic_group['children'];

$id = $parent['id_group'];
$pname =  $parent['name'];
$ptitle = $parent['title'];//( ($parent['title'] != '') ? $parent['title'] : $parent['name'] );
$psubtitle = $parent['subtitle'];

?>
<?php if(!empty($wrap)): // IMPORTANT - $wrap conditional made to widen use case for this code
 ?>

  <li class="sortme nohover topic_group" id="topic_group_<?php echo $id ;?>" rel="<?php echo $id ;?>" data-id="<?php echo $id; ?>">

    <div class="h20">
      <a class="icon delete right" rel="<?php echo $id ;?>"></a>
      <span class="icon left drag mr10"></span>
      <a class="edit edit_field name left" data-name='name' rel="<?php echo $id ;?>"><?php echo $pname ;?></a>
      <a class='right ml10 mr10 lite group_topic_count_right toggle_child_list'><span id='group_topic_count<?php echo $id; ?>' class='group_topic_count'><?php echo count($children); ?></span> Sub-Topics</a>
    </div>

    <div class="clear mt5 mr20 ml20 topic_definition_details" id="topic_definition_details<?php echo $id; ?>">
    
<?php endif; ?>


      <?php 
        //// $data = array('parent'=>$parent,'children'=>$children,'id'=>$id,'pname'=>$pname,'ptitle'=>$ptitle,'psubtitle'=>$psubtitle);
        //// $this->load->view('help/manage/edit_children', $data);
      ?>

      <span class='help_group_edit_field_wrapper block-clear '>
      <label class=' left w50 lite'>ID</label>
      <span class="left lite" style='position:relative'><?php echo $id ;?></span>
      </span>
      <span class='help_group_edit_field_wrapper block-clear '>
      <label class=' left w50 lite'>Title</label>
      <a class="edit edit_field title left " style='width:90%;' data-name='title' rel="<?php echo $id ;?>" title="<?php echo lang('ionize_label_change').' '.lang('ionize_label_title'); ?>"><?php echo $ptitle; // ($ptitle != '') ? $ptitle : "<span class='lite italic'>Empty (click to add)</span>"; ?></a>
      </span>
      <span class='help_group_edit_field_wrapper block-clear '>
      <label class='left w50 lite'>Subtitle</label>
      <a class="edit edit_field subtitle left " style='width:90%;' data-name='subtitle' rel="<?php echo $id ;?>" title="<?php echo lang('ionize_label_change').' '.lang('ionize_label_subtitle'); ?>"><?php echo $psubtitle; // ($psubtitle != '') ? $psubtitle : "<span class='lite italic'>Empty (click to add)</span>"; ?></a>
      </span>
      <br/>
      <hr />

      <!-- Child Topics -->
      <div style="overflow:hidden;clear:both;">

        <div class="pt5" id="def_<?php echo $id ;?>">

          <!-- Add Topic button -->
          <?php if ($id != 0) :?>
            <input type="button" class="light-button plus mb5 ml5 add_topic" value="Add Topic" rel="<?php echo $id ;?>" />
            <input type="button" class="toolbar-button right eye-open my_help_link" id=""  rel="<?php echo $id ;?>"  value="Preview" />

          <?php endif ;?>

          <ul class='topic_child_list' id='topic_child_list<?php echo $id; ?>' rel="<?php echo $id ;?>">
            <?php foreach($children as $topic) : ?>
              <?php

              $title = ( ($topic['title'] != '') ? $topic['title'] : $topic['name'] );
              
              $rel = $topic['id_group'] . '.' . $topic['id_topic'];

              $flat_rel = $topic['id_group'] . 'x' .  $topic['id_topic'];
              
              $status = (!$topic['online']) ? 'offline' : 'online' ;

              ?>

              <li class="sortme topic<?php echo $topic['id_topic']; ?> topic_child topic_child<?php echo $flat_rel; ?> <?php echo $status ;?>" data-id="<?php echo $rel; ?>" id="topic_child<?php echo $flat_rel; ?>" rel="<?php echo $rel; ?>">
                

                <!-- Drag icon -->
                <span class="icon left drag mr5" rel="<?php echo $rel; ?>"></span>

                <!-- Delete icon -->
                <a class="icon right mr5 delete" rel="<?php echo $rel; ?>" title="<?php echo lang('ionize_label_delete'); ?>"></a>

                <!-- Status icon REVERTED hidden for now, though switch functionality is fine so renable if needed -->
                <a style='display:none' class="icon right mr5 status topic<?php echo $topic['id_topic']; ?> topic<?php echo $flat_rel; ?> <?php echo $status ;?>" title="<?php echo lang('ionize_button_switch_online'); ?>" rel="<?php echo $rel; ?>"></a>

                <!-- Title (draggable) -->
                <a style="overflow:hidden;height:16px;display:block;" class=" pl5 pr10 topic edit_topic topic<?php echo $flat_rel; ?> <?php echo $status ;?>" title="<?php echo lang('ionize_label_edit'); ?>" rel="<?php echo $topic['id_topic']; ?>"><?php echo $title; ?></a>
              </li>

            <?php endforeach ;?>
          </ul>
        </div>
      </div>




<script type="text/javascript">

// ION.initCorrectUrl - TODO low priority - update name field on !focus to url form

    $$('#topic_group_<?php echo $id; ?> .toggle_child_list').each(function(item){
      item.removeEvents('click');
      item.addEvent('click',function(e){
        /// console.log('.toggle_child_list click',item);
        e.stop();
        item.getParent('.topic_group').toggleClass('collapsed')
          .getElements('.topic_definition_details').each(function(el,i){
            toggleHelpGroupDetails(el,i);
          });
      });
    });

    if($('group_topic_count<?php echo $id; ?>')) $('group_topic_count<?php echo $id; ?>').set('text','<?php echo count($children); ?>');

    /**
     * Children manager
     * NOTE - call here as well as in edit_list.php to sanity check group itemManager events from cascading
     *
     */
    $$('#topic_definition_details<?php echo $id; ?> .topic_child_list').each(function(item, idx)
    {
      /// console.log('adding itemManager',item);
      item.getElements('.delete').removeEvents();
      item['im' + idx] = new ION.ItemManager({
        controller:'help',
        method:'save_topic_ordering',
        deleteMethod:'delete_topic',
        statusMethod:'switch_topic_online',
        parent_element: 'help_group', id_parent: item.getProperty('rel'),
        container: item.id, 'element':'topic_child',
        confirmDelete:true, confirmDeleteMessage:Lang.get('my_confirm_delete_help_topic')
      });
      item['im' + idx].makeSortable();
      // ,'sortable':true
    });



    /**
     * Topic edit
     *
     */
    $$('#topic_definition_details<?php echo $id ;?> .edit_topic').each(function(item, idx)
    {
      item.removeEvents('click');
      item.addEvent('click', function(e)
      {
        e.stop();
        var id = item.getProperty('rel');
        ION.formWindow('editTopic'+id, 'editTopic'+id+'Form', 'Edit Topic '+id, 'help/edit_topic/'+id, {width:600, height:400}, {'id_topic': id});
      });
    });




    /**
     * Add Topic button event
     *
     */
    $$('#topic_definition_details<?php echo $id ;?> .add_topic').each(function(item)
    {
      item.removeEvents('click');
      item.addEvent('click', function(e)
      {
        e.stop();
        var id = this.getProperty('rel');
        ION.formWindow('createTopic'+id, 'createTopic'+id+'Form', 'Create Help Topic', 'help/create_topic/'+id, {width:600, height:400}, {'id_group': id});
      });
    });

    /**
     * Topic status switch
     *  REVERTED - handled by item manager
     */
    //$$('#manageListContainer li .status').each(function(item){var rel = item.getProperty('rel');rel = rel.split('.');ION.initRequestEvent(item, admin_url + 'help/switch_topic_online/'+rel[0]+'/'+rel[1], {}, {});});
    
    /**
     * Group edit fields
     */
    $$('#topic_definition_details<?php echo $id ;?> .edit_field').each(function(item, idx){
      item.removeEvents('click');
      help_editFields(item);
    });



  // CHANGED help links (such as 'preview' link)
  ION.my_initHelpTopic('#topic_group_<?php echo $id ;?> .my_help_link');
</script>



<?php if(!empty($wrap)): ?>

    </div>
  </li>
<?php endif; ?>
