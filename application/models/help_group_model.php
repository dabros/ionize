<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author Daniel Brose dabros_inc@hotmail.com
 */
class Help_group_model extends Base_model 
{
	/**
	 * Constructor
	 *
	 * @access	public
	 */
	public function __construct()
	{
		parent::__construct();

		$this->table =		'help_group';
		$this->pk_name 	=	'id_group';
    $this->lang_table = 'help_group_lang';
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
    
    $this->{$this->db_group}->order_by($this->table.'.ordering', 'ASC');


    return parent::get_lang_list($where, $lang);
  }



  // ------------------------------------------------------------------------


  /**
   * Saves the group
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
    if( ! $data['id_group'] OR $data['id_group'] == '')
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
      $lang_data = array('en'=>$this->clean_data($data,$this->lang_table));

    }

    /// return array('data'=>$data,'lang_data'=>$lang_data);

    // saving
    $id = parent::save($data, $lang_data);

    return $id;
  }






  // ------------------------------------------------------------------------


  /** 
   * Delete Group and all linked Topics contexts (leaves orphans)
   *
   * @param int   id_group
   *
   * @return  int   Affected rows number
   */
  public function delete($id,$dryrun = false)
  {
    $affected_rows = 0;
    
    // CHANGED NOTE DEBUG allows for 'dryrun' to see what would be affected
    if($dryrun) $affected_rows = array();

    // Check if group exists
    if( $this->exists(array($this->pk_name => $id)) )
    {
      // Group delete
      if($dryrun){
        $_v = $this->{$this->db_group}->where($this->pk_name, $id)->get($this->table);
        $affected_rows['Group'] = ( ($_v && $_v->num_rows() > 0) ? $_v->result_array() : array());
        $_v->free_result();
      }
      else $affected_rows += $this->{$this->db_group}->where($this->pk_name, $id)->delete($this->table);
      
      // Lang
      if($dryrun){
        $_v = $this->{$this->db_group}->where($this->pk_name, $id)->get($this->lang_table);
        $affected_rows['Lang'] = ( ($_v && $_v->num_rows() > 0) ? $_v->result_array() : array());
        $_v->free_result();
      }
      else $affected_rows += $this->{$this->db_group}->where($this->pk_name, $id)->delete($this->lang_table);
  
      // Group Contexts: Delete links between this Group and all Topics (leaves orphans)
      if($dryrun){
        $_v = $this->{$this->db_group}->where($this->pk_name, $id)->get($this->parent_table);
        $affected_rows['Linked Topics'] = ( ($_v && $_v->num_rows() > 0) ? $_v->result_array() : array());
        $_v->free_result();
      }
      else $affected_rows += $this->{$this->db_group}->where($this->pk_name, $id)->delete($this->parent_table);

    }
    
    return $affected_rows;
  }





























}

/* End of file helpfiles_model.php */
/* Location: ./application/models/helpfiles_model.php */