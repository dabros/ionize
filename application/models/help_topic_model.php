<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author Daniel Brose dabros_inc@hotmail.com
 */
class Help_topic_model extends Base_model 
{
  public $group_table = 'help_group';


	/**
	 * Constructor
	 *
	 * @access	public
	 */
	public function __construct()
	{
		parent::__construct();

		$this->table =		'help_topic';
		$this->pk_name 	=	'id_topic';
    $this->lang_table = 'help_topic_lang';
		$this->parent_table = 'help_group_topic';
	}



  // ------------------------------------------------------------------------


  /**
   * @param array $where
   * @param null  $lang
   *
   * @return array
   *
   */
  public function get_lang_list($where = array(), $lang = 'en', $filter = NULL)
  {
    // Group_Topic table
    $this->{$this->db_group}->select($this->parent_table.'.*', FALSE);
    $this->{$this->db_group}->join(
      $this->parent_table,
      $this->parent_table.'.id_topic = ' .$this->table.'.id_topic',
      'left'
    );

    // Correction on $where['id_media']
    if (is_array($where) && isset($where['id_topic']) )
    {
      $where[$this->table.'.id_topic'] = $where['id_topic'];
      unset($where['id_topic']);
    }

    // Default ordering
    if( !isset($where['order_by']) || (isset($where['order_by']) && empty($where['order_by'])) )
      $where['order_by'] = $this->parent_table.'.ordering ASC';

    // Correction on $where['id_group']
    if (is_array($where) && isset($where['id_group']) )
    {
      $where[$this->parent_table.'.id_group'] = $where['id_group'];
      unset($where['id_group']);
    }

    // Correction on $where['where_in']
    if (isset($where['where_in']))
    {
      foreach($where['where_in'] as $key => $value)
      {
        if ($key == 'id_group')
        {
          $where['where_in'][$this->parent_table.'.id_group'] = $value;
          unset($where['where_in']['id_group']);
        }
      }
    }

    // Correction on $where['where_not_in']
    if(isset($where['where_not_in']))
    {
      foreach($where['where_not_in'] as $key => $value)
      {
        if($key == 'id_group')
        {
          $where['where_not_in'][$this->parent_table.'.id_group'] = $value;
          unset($where['where_not_in']['id_group']);
        }
      }
    }


    return parent::get_lang_list($where, $lang);
  }





  // ------------------------------------------------------------------------


  /**
   * Saves the topic
   *
   * @param   array Standard data table
   * @param   array Lang depending data table
   *
   * @return  int   Articles saved ID
   *
   */
  public function save($data, $lang_data = null)
  {
    // New : Created field
    if( ! $data['id_topic'] OR $data['id_topic'] == '')
      $data['created'] = $data['updated'] = date('Y-m-d H:i:s');
    // Existing : Update date
    else
      $data['updated'] = date('Y-m-d H:i:s');

    if(is_null($lang_data))
    {
      /*
      // NOTE base_model->clean_data has similar functionality 
      $bf = $this->list_fields();
      $lf = $this->list_fields($this->lang_table);

      $lang_data = array_intersect_key($data, array_flip($lf));
      $data = array_intersect_key($data, array_flip($bf));
      */
      $lang_data = $this->clean_data($data,$this->lang_table);
    }

    if(isset($lang_data['title'])) $lang_data = array('en'=>$lang_data);

    /// return array('data'=>$data,'lang_data'=>$lang_data);

    // saving
    $id = parent::save($data, $lang_data);

    if(empty($data['id_topic'])){ $data['id_topic'] = $id; }
    if(empty($data['id_group'])){ $data['id_group'] = 0; }

    $old_id = (isset($data['origin_id_group'])) ? $data['origin_id_group'] : null;
    $cs = $this->save_context($data,$old_id);

    /// ChromePHP::log('help_topic_model->save',array('data'=>$data,'lang_data'=>$lang_data,'cs'=>$cs));

    return $id;
  }



  // ------------------------------------------------------------------------

  
  /**
   * Saves the topic context
   *
   */
  public function save_context($data,$old_id = null)
  {
    if ( ! empty($data['id_topic']));
    {
      $data = $this->clean_data($data, $this->parent_table);

      $where = array('id_group'=>((!is_null($old_id)) ? $old_id : $data['id_group']),'id_topic'=>$data['id_topic']);

      if($this->exists($where, $this->parent_table) == FALSE)
        return $this->{$this->db_group}->insert($this->parent_table, $data);
      else
        return $this->{$this->db_group}->where($where)->update($this->parent_table, $data);
      
    }

    return 0;
  }
  




  // ------------------------------------------------------------------------

  
  /**
   * Returns simple context list
   *
   */
  public function get_simple_context($id_topic,$id_group = null)
  {
    $where = array('id_topic'=>$id_topic);

    $id_topic = (count($rel)>1) ? $rel[1] : $rel[0];
    $id_group = (count($rel)>1) ? $rel[0] : null;

    if(!is_null($id_group))
      $where['id_group'] = $id_group;

    // Topic Contexts 
    $_v = $this->{$this->db_group}->where($where)->get($this->parent_table);
    $contexts = ( ($_v && $_v->num_rows() > 0) ? $_v->result_array() : array());
    $_v->free_result();
      
    return $contexts;
  }
  
  // ------------------------------------------------------------------------


  /**
   * Returns topic's context data for a given group
   *
   */
  public function get_context($id_topic, $id_group, $lang = 'en')
  {
    // NOTE - not tested fully yet, consider UNFINISHED
    $data = array();
    
    $lang = (is_null($lang)) ? Settings::get_lang('default') : $lang;
    
    $this->{$this->db_group}->select($this->table.'.*');
    $this->{$this->db_group}->select($this->lang_table.'.*');
    $this->{$this->db_group}->select($this->parent_table.'.*');

    $this->{$this->db_group}->join($this->lang_table, $this->table.'.'.$this->pk_name.' = ' .$this->lang_table.'.'.$this->pk_name, 'inner');      
    $this->{$this->db_group}->join($this->parent_table, $this->table.'.'.$this->pk_name.' = ' .$this->parent_table.'.'.$this->pk_name, 'inner');      

    $this->{$this->db_group}->where(array($this->lang_table.'.lang' => $lang));
    $this->{$this->db_group}->where(array($this->table.'.'.$this->pk_name => $id_topic, $this->parent_table.'.id_group' => $id_group));

    $query = $this->{$this->db_group}->get($this->table);

    if( !$query ) { log_message('error', "help_topic_model get_context query failed:".$this->{$this->db_group}->last_query()); }
    if( $query and $query->num_rows() > 0 ) 
    {
      $data = $query->row_array();
      $query->free_result();
    }

    return $data;
  }




  // ------------------------------------------------------------------------


  /**
   * Set an article online / offline in a given context (page)
   *
   * @param int     Page ID
   * @param int     Article ID
   *
   * @return  boolean   New status
   *
   */
  public function switch_online($id_group,$id_topic)
  {
    // Current status
    //$topic = $this->get_context($id_topic, $id_group);
    //$status = $topic['online'];

    $where = array($this->pk_name=>$id_topic,'id_group'=>$id_group);
    
    $q = $this->{$this->db_group}->select('online')->where($where)->get($this->parent_table);
    $topic = $q->row();
    $q->free_result();

    /// ChromePHP::log("help_topic_model switch_online query",array('sql'=>$this->{$this->db_group}->last_query(),'topic'=>$topic)); 

    $status = $topic->online;

    // New status
    $status = ($status == 1) ? 0 : 1;

    // Save   
    $this->{$this->db_group}->where($where)->set('online', $status)->update($this->parent_table);
    /// ChromePHP::log("help_topic_model switch_online SAVE query",array('sql'=>$this->{$this->db_group}->last_query())); 
    
    return $status;
  }

  // ------------------------------------------------------------------------


  /** 
   * Delete Topic and all linked Topics contexts (leaves orphans)
   *
   * @param int   id_topic
   *
   * @return  int   Affected rows number
   */
  public function delete($id,$dryrun = false,$id_group = null)
  {
    $affected_rows = 0;
    $where = array($this->pk_name=>$id);
    
    // CHANGED NOTE DEBUG allows for 'dryrun' to see what would be affected
    if($dryrun) $affected_rows = array();

    // Check if topic exists
    if( $this->exists(array($this->pk_name => $id)) )
    {
      // Topic delete
      if($dryrun){
        $_v = $this->{$this->db_group}->where($where)->get($this->table);
        $affected_rows['Topic'] = ( ($_v && $_v->num_rows() > 0) ? $_v->result_array() : array());
        $_v->free_result();
      }
      else $affected_rows += $this->{$this->db_group}->where($where)->delete($this->table);
      
      // Lang
      if($dryrun){
        $_v = $this->{$this->db_group}->where($where)->get($this->lang_table);
        $affected_rows['Lang'] = ( ($_v && $_v->num_rows() > 0) ? $_v->result_array() : array());
        $_v->free_result();
      }
      else $affected_rows += $this->{$this->db_group}->where($where)->delete($this->lang_table);
  
      // Topic Contexts : Delete links between this Topic and all Groups
      if(!is_null($id_group)) $where['id_group'] = $id_group;
      if($dryrun){
        $_v = $this->{$this->db_group}->where($where)->get($this->parent_table);
        $affected_rows['Contexts'] = ( ($_v && $_v->num_rows() > 0) ? $_v->result_array() : array());
        $_v->free_result();
      }
      else $affected_rows += $this->{$this->db_group}->where($where)->delete($this->parent_table);

    }
    
    return $affected_rows;
  }



  // ------------------------------------------------------------------------


  /** 
   * Removes a single context linkage between the group and topic ids passed
   *
   */
  public function unlink($id_topic,$id_group,$dryrun = false)
  {
    $affected_rows = 0;
    
    // CHANGED NOTE DEBUG allows for 'dryrun' to see what would be affected
    if($dryrun) $affected_rows = array();

    // Check if topic exists
    if( $this->exists(array($this->pk_name => $id)) )
    {
      $where = array('id_topic'=>$id_topic,'id_group'=>$id_group);
      // Topic Contexts : Delete links between this Topic and all Groups
      if($dryrun){
        $_v = $this->{$this->db_group}->where($where)->get($this->parent_table);
        $affected_rows['Contexts'] = ( ($_v && $_v->num_rows() > 0) ? $_v->result_array() : array());
        $_v->free_result();
      }
      else $affected_rows += $this->{$this->db_group}->where($where)->delete($this->parent_table);

    }
    
    return $affected_rows;
  }





















}

/* End of file helpfiles_model.php */
/* Location: ./application/models/helpfiles_model.php */