<style type='text/css'>
  @media only screen and (min-width: 800px){
    .orphan_topics_list .orphan_topic{ width:200px; float:left;clear:none;display:block;margin:5px 5px 5px 0px;}
    .orphan_topic:nth-of-type(4n+1){  clear:left;}
  }
</style>

<?php if(count($orphan_topics)): ?>
  <!-- Orphan Topics : no linked to any Group -->
  <h3 class='ml10'>Orphan Topics</h3>

  <div class="element pl5 pr5 pt0 pb20 mt0 mb50 ml5 ">


    <ul class='orphan_topics_list' id='orphan_topics_list'>
      <?php foreach($orphan_topics as $topic) : ?>
        <?php

        if(!isset($topic['id_group'])) $topic['id_group'] = 0;

        $title = ( ($topic['title'] != '') ? $topic['title'] : $topic['name'] );
        
        $rel = $topic['id_group'] . '.' . $topic['id_topic'];

        $flat_rel = $topic['id_group'] . 'x' .  $topic['id_topic'];

        ?>

        <li class="sortme  topic<?php echo $topic['id_topic']; ?> orphan_topic orphan_topic<?php echo $flat_rel; ?> " id="orphan_topic<?php echo $flat_rel; ?>" rel="<?php echo $rel; ?>">
          

          <!-- Delete icon -->
          <a class="icon right mr5 delete" rel="<?php echo $rel; ?>" title="<?php echo lang('ionize_label_delete'); ?>"></a>


          <!-- Title  -->
          <a style="overflow:hidden;height:16px;display:block;" class=" pl5 pr10 topic edit_topic topic<?php echo $flat_rel; ?> " title="Click to Edit" rel="<?php echo $topic['id_topic']; ?>"><?php echo $title; ?></a>
        </li>

      <?php endforeach ;?>
    </ul>


  </div>

  <script>

    /**
     * Orphan Topic edit
     *
     */
    $$('#orphan_topics_list .orphan_topic').each(function(item, idx)
    {
      ///console.log('orphan item',{item:item,del:item.getElements('.delete'),edit:item.getElements('.edit_topic')});
      item.getElements('.edit_topic').addEvent('click', function(e){
        e.stop();
        var id = this.getProperty('rel');
        ION.formWindow('editTopic'+id, 'editTopic'+id+'Form', 'Edit Topic '+id, 'help/edit_topic/'+id, {width:600, height:400}, {'id_topic': id});
      });

      ION.initRequestEvent(item.getElements('.delete'), ION.adminUrl+'help/delete_topic/' + item.getProperty('rel'), {}, {'confirm': true, 'message': 'my_confirm_delete_help_topic'})
      
    });
    /**
     * Orphan Topic delete
     *
     */
    $$('#orphan_topics_list .edit_topic').each(function(item, idx)
    {
      item.addEvent('click', function(e)
      {
        e.stop();
        var id = item.getProperty('rel');
        ION.formWindow('editTopic'+id, 'editTopic'+id+'Form', 'Edit Topic '+id, 'help/edit_topic/'+id, {width:600, height:400}, {'id_topic': id});
      });
    });


  </script>



<?php endif; ?>

