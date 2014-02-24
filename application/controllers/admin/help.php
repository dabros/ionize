<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * my controller for backend help files
 * 
 */
class Help extends MY_Admin 
{

  protected $topic_list = array();
  protected $link_info = array();
  protected $topics = array();

  public function __construct()
  {
    parent::__construct();

    $this->load->model('help_group_model', '', TRUE);
    $this->load->model('help_topic_model', '', TRUE);
  }

// ------------------------------------------------------------------------



  function index()
  {
    // Do nothing.
  }
  

// ------------------------------------------------------------------------


  public function open($id_group = null,$jumpMenu = false,$windowId = null)
  {
  ///  ChromePHP::log('help->open',array('id_group'=>$id_group));

    $groups = array();
    $topics = array();

    if(!empty($id_group) && $id_group !== 'ALL')
    {
      if(!$jumpMenu)
      {
        if(is_numeric($id_group)){
          $groups = $this->help_group_model->get_lang_list(array('id_group'=>$id_group));
        }else{ 
          $groups = $this->help_group_model->get_lang_list(array('name'=>$id_group));
          $id_group = $groups[0]['id_group'];
        }

        $topics = $this->help_topic_model->get_lang_list(array('id_group'=>$id_group));
      }else{
        $this->template['jump_id'] = $id_group;
      }
    }else{
      $id_group = 'ALL';
    }

    $this->_prepare_topics($groups,$topics,true);

    $this->template['windowId'] = (!empty($windowId)) ? $windowId : 'help'.$id_group;
    $this->template['topic_list'] = $this->topic_list;
    $this->template['id_group'] = $id_group;

    // $this->template['link_info'] = $this->link_info;

    if($jumpMenu)
      $this->output('help/list');
    else
      $this->output('help/open');
  }

  // ------------------------------------------------------------------------

  public function open_full($id_group = 'All')
  {
    $this->open($id_group,true,'mainPanel');
  }


// ------------------------------------------------------------------------

  function manage()
  {

    $this->output('help/manage/manage');
  }

  
  // ------------------------------------------------------------------------
  
  
  /**
   */
  function refresh_group($id_group = null)
  {

    // $holder = $this->input->post('holder');
    $windowId = $this->input->post('windowId');
    if(is_null($id_group)) $id_group = $this->input->post('id_group');

    $groups = array();
    $topics = array();

    if(!empty($id_group) && $id_group !== 'ALL')
    {
      if(is_numeric($id_group)){
        $groups = $this->help_group_model->get_lang_list(array('id_group'=>$id_group));
      }else{ 
        $groups = $this->help_group_model->get_lang_list(array('name'=>$id_group));
        $id_group = $groups[0]['id_group'];
      }
      $topics = $this->help_topic_model->get_lang_list(array('id_group'=>$id_group));
    }else{
      $id_group = 'ALL';
    }

    $this->_prepare_topics($groups,$topics,true);

    $this->template['windowId'] = (!empty($windowId)) ? $windowId : 'help'.$id_group;
    $this->template['topic_list'] = $this->topic_list;

    // $html = $this->load->view('help/view_group', $this->template, TRUE);

    $this->output('help/view_group');
    
    // $this->response();
  }
  

  // ------------------------------------------------------------------------

  
  /**
   * Returns the topics list, 'manage-ready'
   *
   * XHR call
   *
   */
  function get_manage_list()
  {
    $this->_prepare_topics();

    $this->template['topic_list'] = $this->topic_list;

    $this->output('help/manage/edit_list');
  }


  // ------------------------------------------------------------------------

  
  /**
   * Returns the topics list, 'manage-ready'
   *
   * XHR call
   *
   */
  function get_orphan_topics_list()
  {
    $topics = array();
    $topics = $this->help_topic_model->get_lang_list(array("help_topic.id_topic NOT IN( SELECT id_topic FROM help_group_topic)"));

    $this->template['orphan_topics'] = $topics;

    $this->output('help/manage/orphan_topics');
  }

  // ------------------------------------------------------------------------

  
  /**
   * Gets the parent list list for the parent select dropdown
   *
   * Receives by $_POST :
   * - id_current : Current id_topic
   * - id_parent : Parent id_group
   *
   * @returns string  HTML string of options items
   *
   */
  public function get_parents_select()
  {
    $id_current = $this->input->post('id_topic');
    $id_group = $this->input->post('id_group');
    $rel = $this->input->post('rel');
    $windowId = $this->input->post('windowId');

    $data = $this->help_group_model->get_lang_list();

    $parents = array('0' => '/');
    foreach($data as $gk=>$gv) $parents[$gv['id_group']] = ''.$gv['id_group'].':&nbsp;&nbsp;'.( ($gv['title'] != '') ? $gv['title'] : $gv['name'] ).'&nbsp;&nbsp;&nbsp;';
  //  ($parents_array = $this->structure->get_parent_select($data, $id_current) ) ? $parents += $parents_array : '';
    
    $this->template['groups'] = $parents;
    $this->template['id_selected'] = $id_group;
    $this->template['windowId'] = (!empty($windowId)) ? $windowId : 'editTopic'.$rel;

    $this->output('help/manage/parent_select');
  }


// ------------------------------------------------------------------------

  public function _get_toc($sort = false)
  {

    $groups = $this->help_group_model->get_lang_list();

    if($sort) $groups = my_msort($groups, 'title', SORT_REGULAR);

    $parents = array();
    foreach($groups as $gk=>$gv) $parents[$gv['id_group']] = "{{".$gv['id_group']."}}<span class='lite block pt0'>".( (!empty($gv['subtitle'])) ? " ".$gv['subtitle']." " : '&nbsp;').'</span>';
      //''.$gv['id_group'].':&nbsp;&nbsp;'.( ($gv['title'] != '') ? $gv['title'] : $gv['name'] ).'&nbsp;&nbsp;&nbsp;';
      //  ($parents_array = $this->structure->get_parent_select($groups, $id_current) ) ? $parents += $parents_array : '';
    
    // $this->template['toc'] = $parents;

    $html = "<ul class='toc'><li>";
    $html .= implode('</li><li>', $parents);
    $html .= "</ul>";

    return $html;
  }
  


// ------------------------------------------------------------------------


  protected function _prepare_topics($groups = null,$topics = null,$parse_links = false)
  {

    /// ChromePHP::log('help->_prepare_topics STARTED ',array('groups'=>$groups,'topics'=>$topics));

    if(empty($groups)){
      $groups = $this->help_group_model->get_lang_list();
      $this->_prep_link_info($groups);
    }else{
      $this->_prep_link_info();
    }

    if(empty($topics)) $topics = $this->help_topic_model->get_lang_list();

    $sorted = array();
    foreach($topics as $t)
    {
      $id = $t['id_topic'];

      if(!isset($sorted[$id]))
        $sorted[$id] = array_merge($t,array('parents'=>array(),'id_main_parent'=>$t['id_group']));//,'parents'=>array(),'main_parent'=>$t['id_parent']);

      $sorted[$id]['parents'][] = $t['id_group'];
      if($t['main_parent'] == 1){
        $sorted[$id]['id_main_parent'] = $t['id_group'];
      }
    }
    $this->topics = $sorted;

    $grouped = array();
    foreach($groups as $gk=>$gv)
    {
      $gid = $gv['id_group'];

       /// ChromePHP::log('sorted -> grouped check',array('sk'=>$sk,'st'=>$st,'in_array(0,['.implode(',',$st['parents']).'])'=>(in_array(0,$st['parents']))));
      $grouped[$gid] = array('parent'=>$gv,'children'=>array());
      foreach($sorted as $tk=>$tv){
        if(in_array($gid,$tv['parents'])){
          if($parse_links) $tv['content'] = $this->_parse_help_links($tv['content']);
          $grouped[$gid]['children'][] = $tv;
        }
      }
          
      $grouped[$gid]['children'] = my_msort($grouped[$gid]['children'], 'ordering', SORT_NUMERIC );

    }

    /// ChromePHP::log('Help->_prepare_topics',array('all'=>array('groups'=>$groups,'topics'=>$topics),'sorted'=>$sorted,'grouped'=>$grouped,'link_info'=>$this->link_info));

    $this->topic_list = $grouped;
    return $grouped;
  }


  // ------------------------------------------------------------------------
  
  /** Sets up $this->link_info for later use, such as in internal links */
  protected function _prep_link_info($groups = array())
  {
    if(empty($groups)) $groups = $this->help_group_model->get_lang_list();

    foreach($groups as $gk=>$gv){
      $this->link_info[$gv['id_group']] = array('title'=>( ($gv['title'] != '') ? $gv['title'] : $gv['name'] ),'subtitle'=>$gv['subtitle']);
    }
  }


  // ------------------------------------------------------------------------
  
  
  /**
   * Creates one Help Group 
   * Used by help_manage_toolbox view
   *
   */
  function create_group()
  {
    $data = $this->help_group_model->feed_blank_template();
    $lang_data = array();
    $this->help_group_model->feed_blank_template($lang_data,$this->help_group_model->lang_table);

    $data['name'] = 'new_group';
    $lang_data['title'] = 'New Group';
    $data['id_group'] = $this->help_group_model->save($data, $lang_data);

    $this->template['parent'] = array_merge($data, $lang_data);
    $this->template['children'] = array();


    ///ChromePHP::log('help->create_group ',array('template'=>$this->template,'data'=>$data,'lang_data'=>$lang_data));
    
    // $html = $this->load->view('help/manage/edit_group', $this->template, TRUE);

    $this->callback[] = array('fn' => 'ION.HTML','args' => array('help/get_manage_list','',array('update'=> 'manageListContainer')));
    
    $this->response();
  }
  

  // ------------------------------------------------------------------------
  
  
  /**
   * Used to return the edit_group template for a single group
   *
   */
  function edit_group($id_group)
  {
    $groups = $this->help_group_model->get_lang_list(array('id_group'=>$id_group));
    $topics = $this->help_topic_model->get_lang_list(array('id_group'=>$id_group));
    $this->_prepare_topics($groups,$topics);

    $this->template['topic_group'] = $this->topic_list[$id_group];

    // $c = $this->update_topics_count($id_group, $topics);

    //$this->callback[] = array('fn' => 'ION.updateElementHtmlContent','args' => array('#group_topic_count'.$id_group,$c));
    
  // if(id_group) ION.HTML('help/get_topic_count/'+id_group, {}, {'update': 'topic_group_count'+id_group });


    $this->output('help/manage/edit_group');

    // $this->response();
  }
  

  // ------------------------------------------------------------------------

  /**
   * Sends XHR update with the count for the current group's topics
   */
  function update_topics_count($id_group,$topics = array())
  {
    if(empty($this->topic_list)){
      $topics = $this->help_topic_model->get_lang_list(array('id_group'=>$id_group));
    }else{
      $topics = $this->topic_list[$id_group]['children'];
    }

    $c = count($topics); 
   // if($c == 0) $c = "<span style='display:none'>0</span>";

    //$this->xhr_output($c);

        // Update array - update redirects count
      //  $this->update[] = array('element' => 'rules_count_'.$rel,'url' => admin_url() . 'redirects/update_rules_count/' . $rel);

    return $c;
  }


  // ------------------------------------------------------------------------
  
  
  /**
   * Creates one Help Topic 
   * Used by help/manage/list view
   *
   */
  function create_topic($id_group = null)
  {
    if(is_null($id_group)) $id_group = $this->input->post('id_group');
    if(empty($id_group)) $id_group = 0;

    // Get blank templates of all values
    $data = array();
    $this->help_topic_model->feed_blank_template($data);
    $this->help_topic_model->feed_blank_template($data,$this->help_topic_model->parent_table);

    $lang_data = array();
    $this->help_topic_model->feed_blank_template($lang_data,$this->help_topic_model->lang_table);

    // Override what values are needed
    $data['id_group'] = $id_group;
    $lang_data['lang'] = 'en';
    $data['name'] = 'new-topic';

    // Run save to produce id_topic
    $id_topic = $this->help_topic_model->save($data, $lang_data);

    // save to both as sanity check
    $data['id_topic'] = $id_topic;
    $lang_data['id_topic'] = $id_topic;

    $this->template['topic'] = array_merge($data, $lang_data);
    
    /// ChromePHP::log('help->create_topic ',array('template'=>$this->template,'data'=>$data,'lang_data'=>$lang_data));

    // NOTE - first callback not always updating UI as expected
    if(!empty($id_group)) $this->callback[] = array('fn' => 'ION.HTML','args' => array('help/edit_group/'.$id_group,'',array('update'=> 'topic_definition_details'.$id_group)));
    else $this->callback[] = array('fn' => 'ION.HTML','args' => array('help/get_manage_list','',array('update'=> 'manageListContainer')));

    $this->template['windowId'] = 'createTopic'.$id_group;

    $this->output('help/manage/edit_topic');

    // $this->response('xx');
  }
  
  
  // ------------------------------------------------------------------------


  /**
   * Called to populate data in the topic modal
   */
  function edit_topic($rel = null,$topic = null)
  {

    $id_topic = $rel;
    $topics = array();

   // if(empty($topic)){

      if(!is_null($rel)){
        $id_topic = explode('.',$rel);
        $id_topic = (count($id_topic)>1) ? $id_topic[1] : $id_topic[0];

        $topics = $this->help_topic_model->get_lang_list(array('id_topic'=>$id_topic));
       // $topics = (empty($this->topics)) ? $this->help_topic_model->get_lang_list(array('id_topic'=>$id_topic)) : $this->topics;
        if(!empty($topics)){
          // TODO expand to allow multiple context
          $topic = $topics[0];
        }
      }else{
        // $topics = $this->help_topic_model->get_lang_list();
      }
   // }
    
      if(!empty($topic))
      {
        $this->template['topic'] = $topic;
        $this->template['windowId'] = 'editTopic'.$rel;

        $this->output('help/manage/edit_topic');
      }else{
        /// 
        ChromePHP::log('help->edit_topic debug',array('rel'=>$rel,'id_topic'=>$id_topic,'topic'=>$topic,'topics'=>$topics));
        $this->error('Error preparing topic data');
      }

  }







  // ------------------------------------------------------------------------


  /**
   * Saves one extend field definition based on $_POST data
   *
   */
  function save_topic()
  {
    if($this->input->post('name') != '')
    {
      $data = $this->input->post();

      $data['updated'] = date('Y-m-d H:i:s');
      $data['main_parent'] = 1;
      $data['lang'] = 'en';
      $data['name'] = url_title($data['name']);

      $topic = array();
     /// $topic = $this->help_topic_model->get_lang_list(array('id_topic'=>$data['id_topic']));
     /// $topic = (!empty($topic)) ? $topic[0] : array();

      // Save data
      $saved = $this->help_topic_model->save($data);

      /// ChromePHP::log('help->save_topic saving data ',array('data'=>$data,'saved'=>$saved,'keys'=>array('topic'=>array_keys($topic),'data'=>array_keys($data),'_diff'=>array_diff_key($topic, $data))));
      
      //$this->callback = array(array('fn' => 'ION.HTML','args' => array('help/get_manage_list','',array('update'=> 'manageListContainer'))));
      
      $this->callback[] = array('fn' => 'ION.HTML','args' => array('help/edit_group/'.$data['id_group'],'',array('update'=> 'topic_definition_details'.$data['id_group'])));
      if(!empty($data['id_group_origin'])) 
        $this->callback[] = array('fn' => 'ION.HTML','args' => array('help/edit_group/'.$data['origin_id_group'],'',array('update'=> 'topic_definition_details'.$data['origin_id_group'])));

      $this->success(lang('my_help_message_topic_saved'));
      
    }
    else
    {
      $this->error(lang('my_help_message_topic_not_saved'));      
    }
  }







  // ------------------------------------------------------------------------


  /**
   * Set an item online / offline depending on its current status
   *
   * @param int   item ID
   *
   */
  public function switch_topic_online($rel)
  {

    $rel = explode('.', $rel);
    $id_topic = (count($rel)>1) ? $rel[1] : $rel[0];
    $id_group = (count($rel)>1) ? $rel[0] : null;

    // Clear the cache
    Cache()->clear_cache();

    if(!is_null($id_group))
    {
      $status = $this->help_topic_model->switch_online($id_group,$id_topic);

      ChromePHP::warn('switch_topic_online DEPRECATED!!!!!!! ',array("rel"=>$rel,"status"=>$status,"selector"=>'.topic'.$id_group.'x'.$id_topic));

      $this->callback[] = array('fn' => 'ION.switchOnlineStatus','args' => array('status' => $status,'selector' => '.topic'.$id_group.'x'.$id_topic));
      
      // Answer send
      $this->success(lang('ionize_message_operation_ok'));
    }else{
      $this->error(lang('ionize_message_operation_nok'));
    }

  }



  // ------------------------------------------------------------------------
  
  /**
   * Changes a editable field for a topic_group
   * XHR call
   *
   * @return  Mixed Success message
   *
   */
  function save_group_field()
  {
    // $table = $this->input->post('table');
    $field = $this->input->post('field');
    $value = $this->input->post('value');
    $id = $this->input->post('id');
    $selector = $this->input->post('selector');
    
    /// ChromePHP::warn('help->save_group_field STARTED',array('field'=>$field,'value'=>$value,'id'=>$id,'selector'=>$selector));
    
    if($field && ($field !== 'name' || ($value && $value != '')))
    {
      // TODO REVERTED removed unique constraint for now, add if needed
      /*
      // Check for name : must be unique !
      if($field == 'name'){
        $element = $this->element_definition_model->get($data);
        if(!empty($element) && $element['id_element_definition'] != $id){
          $this->callback[] = array('fn' => 'ION.notification','args' => array ('error',lang('ionize_message_element_definition_name_already_exists')));
          $this->response();
        }
      }
      */
     
      if($field == 'name'){
        $value = url_title($value);
        $where = array('id_group' => $id);
        $data = array($field => $value);
        $id = $this->help_group_model->update($where, $data);
      }else{
        $data = array('id_group' => $id);
        $lang_data = array( 'en' => array($field => $value) );
        $id = $this->help_group_model->save($data, $lang_data);
      }
      
      if ($id !== FALSE){
        $this->callback[] = array('fn' => 'ION.notification','args' => array ('success',lang('my_help_message_group_saved')));
        $this->callback[] =  array('fn' => 'ION.updateElementHtmlContent','args' => array($selector,$value,'title'));
        //  array('fn' => 'ION.HTML','args' => array('help/get_manage_list','',array('update'=> 'manageListContainer')))
         

      }else{
        $this->callback[] = array('fn' => 'ION.notification','args' => array ('error',lang('my_help_message_group_not_saved')));
      }

      $this->response();
    }
  }



  // ------------------------------------------------------------------------
  
  
  /**
   * Saves the Group order
   *
   */
  function save_group_ordering()
  {
    $order = $this->input->post('order');

///    ChromePHP::log('help->save_group_ordering',array('order'=>$order));
    
    if( $order !== FALSE )
    {
      // Saves the new ordering
      // $this->element_definition_model->save_ordering($order);

      $this->help_group_model->save_ordering($order);
      
      // Answer send
      $this->success(lang('ionize_message_element_ordered'));
    }
    else 
    {
      // Answer send
      $this->error(lang('ionize_message_operation_nok'));
    }
  }



  // ------------------------------------------------------------------------
  
  
  /**
   * Saves the Topics order for one Group parent
   *
   */
  function save_topic_ordering($parent=null,$id_parent=null)
  {
    $order = $this->input->post('order');

   /// ChromePHP::log('help->save_topic_ordering',array('id_parent'=>$id_parent,'order'=>$order));
    
    if( $order !== FALSE )
    {
      // Saves the new ordering
      // $this->element_definition_model->save_ordering($order);

     // 
      $this->help_topic_model->save_ordering($order, 'help_group', $id_parent,'help_');
      
      // Answer send
      $this->success(lang('ionize_message_element_ordered'));
    }
    else 
    {
      // Answer send
      $this->error(lang('ionize_message_operation_nok'));
    }
  }




  // ------------------------------------------------------------------------
  
  
  /**
   * Deletes one Group
   *
   */
  function delete_group($id,$dryrun = false)
  {

   // $topics = $this->help_topic_model->get_list(array('id_group' => $id));

    $affected_rows = $this->help_group_model->delete($id,$dryrun);
    
///    ChromePHP::log('help->delete_group affected_rows',array('id'=>$id,'affected_rows'=>$affected_rows,'dryrun'=>$dryrun));

    // $this->callback[] = array('fn' => 'ION.notification','args' => array ('success',lang('my_help_message_group_deleted')));
    $this->callback[] = array('fn' => 'ION.HTML','args' => array('help/get_manage_list','',array('update'=> 'manageListContainer')));
      
    $this->success(lang('my_help_message_group_deleted'));
    
  }



  // ------------------------------------------------------------------------
  
  
  /**
   * Deletes one Group
   *
   */
  function delete_topic($rel,$dryrun = false)
  {
    $r = explode('.', $rel);
    $id_topic = (count($r)>1) ? $r[1] : $r[0];
    $id_group = (count($r)>1) ? $r[0] : null;
    $affected_rows = array();

    $contexts = array();
    /* // TODO create proper unlink functionality - 
    // $cond = array('id_topic'=>$id_topic);if(!is_null($id_group)) $cond['id_group'] = $id_group;
    if(!is_null($id_group)){
     // $contexts = $this->help_topic_model->get_context($id_topic,$id_group);
      $contexts = $this->help_topic_model->get_simple_context($id_topic,$id_group);
    }

    if(count($contexts) < 2) $affected_rows = $this->help_topic_model->delete($id_topic,$dryrun);
    else $affected_rows = $this->help_topic_model->unlink($id_topic,$id_group,$dryrun);
    */
    
    // NOTE - delete currently takes optional id_group param to answer mult context issue
    $affected_rows = $this->help_topic_model->delete($id_topic,$dryrun,$id_group);

 ///  ChromePHP::warn('help->delete_topic affected_rows',array('contexts'=>$contexts,'rel'=>$rel,'id_group'=>$id_group,'affected_rows'=>$affected_rows,'dryrun'=>$dryrun));


    // $this->callback[] = array('fn' => 'ION.notification','args' => array ('success',lang('my_help_message_topic_deleted')));
    if(!empty($id_group)) $this->callback[] = array('fn' => 'ION.HTML','args' => array('help/edit_group/'.$id_group,'',array('update'=> 'topic_definition_details'.$id_group)));
    else $this->callback[] = array('fn' => 'ION.HTML','args' => array('help/get_orphan_topics_list','',array('update'=> 'orphanTopicsListContainer')));
      
   // $this->callback[] = array('fn' => 'ION.HTML','args' => array('help/get_manage_list','',array('update'=> 'manageListContainer')))

    
    $this->success(lang('ionize_message_operation_ok'));
    
  }



  // ------------------------------------------------------------------------
  
  
  /**
   * // TODO UNFINISHED add unlink functionality and change delete_topic to only delete (currently checks contexts and unlinks if count > 1)
   * // NOTE - assess if still needed
   */
  function unlink_topic($rel,$dryrun = true)
  {
  ///  ChromePHP::warn('help->unlink_topic UNFINISHED DEPRECATED ',array('rel'=>$rel,'dryrun'=>$dryrun));    
  }






  // ------------------------------------------------------------------------
  
  
  protected function _parse_help_links($content)
  {
    while(strpos($content, '{{') !== FALSE)
    {
      $value = my_substrpos($content,'{{','}}');
      $rel = substr($value, 2);
      if($rel == 'toc'){ $content = str_replace($value.'}}',$this->_get_toc(),$content); }
      else{
        $title = 'Unknown';
        $subtitle = '';
        if(isset($this->link_info[$rel])){
          $title = $this->link_info[$rel]['title'];
          $subtitle = $this->link_info[$rel]['subtitle'];
        }
        $content = str_replace($value.'}}','<a class="my_help_link nested_menu_link" '.( (!empty($subtitle)) ? ' title="'.$subtitle.'" ' : ''). ' rel="'.$rel.'">'.$title.'</a>',$content);
      }
    }
      //$content = str_replace(array('{{','}}'),array('<a class="my_help_link nested_menu_link" rel="','">SS</a>' , $content);

    return $content;
  }








// ------------------------------------------------------------------------

  function XXXopen()
  {
    /*
    $this->template['parent'] = $element['parent'];
    $this->template['id_parent'] = $element['id_parent'];
    $this->template['ordering'] = $element['ordering'];
    $this->template['id_element'] = $id_element;
    
    $this->output('element/detail');
    */



    $this->template['windowId'] = $this->input->post('regarding');
    $this->template['category_title'] = lang('my_help_category_title_article_list');
    $this->template['topics'][] = array('title'=>lang('my_help_topic_title_article_list_page'), 'content'=>lang('my_help_topic_content_article_list_page'));
    $this->template['topics'][] = array('title'=>lang('my_help_topic_title_article_list_page_location'), 'content'=>lang('my_help_topic_content_article_list_page_location'));
    $this->template['topics'][] = array('title'=>lang('my_help_topic_title_article_list_pages_column'), 'content'=>lang('my_help_topic_content_article_list_pages_column'));
    $this->template['topics'][] = array('title'=>lang('my_help_topic_title_article_list_orphaned_articles'), 'content'=>lang('my_help_topic_content_article_list_orphaned_articles'));
    $this->template['topics'][] = array('title'=>lang('my_help_topic_title_article_list_content_column'), 'content'=>lang('my_help_topic_content_article_list_content_column'));
    $this->output('help/open');
  }

// ------------------------------------------------------------------------

  function XXXlist_topics()
  {
    $this->template['category_title'] = lang('my_help_category_title_article_list');

    $this->template['topics'][] = array('title'=>lang('my_help_topic_title_article_list_page'), 'content'=>lang('my_help_topic_content_article_list_page'));
    $this->template['topics'][] = array('title'=>lang('my_help_topic_title_article_list_page_location'), 'content'=>lang('my_help_topic_content_article_list_page_location'));
    $this->template['topics'][] = array('title'=>lang('my_help_topic_title_article_list_pages_column'), 'content'=>lang('my_help_topic_content_article_list_pages_column'));
    $this->template['topics'][] = array('title'=>lang('my_help_topic_title_article_list_orphaned_articles'), 'content'=>lang('my_help_topic_content_article_list_orphaned_articles'));
    $this->template['topics'][] = array('title'=>lang('my_help_topic_title_article_list_content_column'), 'content'=>lang('my_help_topic_content_article_list_content_column'));
    $this->output('help/list');
  }
}

