<style type='text/css'>
  @media only screen and (min-width: 800px){
    .topic_group{ width:600px; float:left;clear:none;display:block;margin:15px 10px;}
    .topic_group:nth-of-type(2n+1){  clear:left;}
    .topic_group .help_group_edit_field_wrapper input{width: 450px!important;}
    .topic_group .edit_field{ width:490px!important;overflow:hidden;}
    .topic_group .edit_field.name{ width:400px!important;}
    .topic_group.collapsed{ width:350px; float:left;display:block;margin:5px;clear:none;}
    .topic_group.collapsed:nth-of-type(2n+1){  clear:none;}
    .topic_group.collapsed:nth-of-type(4n+5){  clear:left;}
    .topic_group.collapsed .help_group_edit_field_wrapper label{margin-left: -14px!important;width: 36px!important;}
    .topic_group.collapsed .help_group_edit_field_wrapper input{width: 250px!important;}
    .topic_group.collapsed .edit_field{ width:276px!important;overflow:hidden;white-space: nowrap;}
    .topic_group.collapsed .edit_field.name{ width:200px!important;}
  }
</style>
<?php foreach($topic_list as $gid=>$topic_group) :?>

<?php // NOTE IMPORTANT - to allow for updating individual groups, use a param $wrap to tell view/help/manage/edit_group when to print <li> holder ?>
  <?php if(empty($wrap)): ?>
    <li class="sortme nohover topic_group" id="topic_group_<?php echo $gid ;?>" rel="<?php echo $gid ;?>" data-id="<?php echo $gid; ?>">

    <?php 
      $children = $topic_group['children'];
      $pname =  $topic_group['parent']['name'];
    ?>
    <div class="h20">
      <a class="icon delete right" rel="<?php echo $gid ;?>"></a>
      <span class="icon left drag mr10"></span>
      <a class="edit edit_field name left" data-name='name' rel="<?php echo $gid ;?>"><?php echo $pname ;?></a>
      <a class='right ml10 mr10 lite group_topic_count_right toggle_child_list'><span id="group_topic_count<?php echo $gid; ?>" class='group_topic_count'><?php echo count($children); ?></span> Sub-Topics</a>
    </div>
    <div class="clear mt5 mr20 ml20 topic_definition_details" id="topic_definition_details<?php echo $gid; ?>">

  <?php endif; ?>
    
  <?php 
    $data = array('topic_group'=>$topic_group);
    $this->load->view('help/manage/edit_group', $data);
  ?>

  <?php if(empty($wrap)): ?>

    </div>
    </li>
  <?php endif; ?>
<?php endforeach ;?>

<?php 
?>

<script type="text/javascript">

    /**
     * Content Element itemManager
     *
     */
    var groupManager = new ION.ItemManager({
        controller:'help',
        method:'save_group_ordering',
        deleteMethod:'delete_group',
        statusMethod:'switch_group_online',
        container: 'manageListContainer', 'element':'topic_group',
        confirmDelete:true, confirmDeleteMessage:Lang.get('my_confirm_delete_help_group')
      });
    groupManager.makeSortable();

    


    /**
     * Children manager
     * NOTE - call here as well as in edit_list.php to sanity check group itemManager events from cascading
     * NOTE - loading child lists after call groupManager should fix this, but not an ideal solution
     */
    $$('#manageListContainer .topic_child_list').each(function(item, idx)
    {

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


    function help_editFields(item)
    {
      var id = item.getProperty('rel');
      var field = item.getProperty('data-name');

      var title = item.getProperty('title');
      if(item.get('text') == false){ item.set('text', title).addClass('lite').addClass('italic'); }
      else{ item.removeClass('lite').removeClass('italic'); }

      var input = new Element('input', {'type': 'text', 'class':' left block mb10 '+( (field=='name') ? 'w180' : (field=='title') ? 'w300' : 'w600')+' ','name':field}); // , 'style':'min-width:180px'
      if (item.get('text') != title) { input.value = item.get('text'); }


      input.inject(item, 'before').hide();

      input.addEvent('blur', function(e)
      {
        var value = input.value;

        if( (field != 'name' || value != '') && value != title)
        {
          ION.sendData('help/save_group_field', {'id':id, 'field':field, 'value':value, selector:'.topic_group a.'+field+'[rel='+id+']' });
        }
        input.hide();
        item.show();
      });

      item.addEvent('click', function(e){
        input.show().focus();
        item.hide();
      });
    }


    $$('#manageListContainer .topic_group .edit_field.name').each(function(item, idx){
      item.removeEvents('click');
      help_editFields(item); 
    });



</script>
