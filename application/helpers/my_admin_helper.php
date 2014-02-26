<?php if (!defined('BASEPATH')) exit('No direct script access allowed');



  // ------------------------------------------------------------------------

/**
 *  NOTE - unpolished, but an example of one of the functions i use to print 'read-only' content for all field types, 
 *  whilst acheiving more control over look and feel
 * 
 * Note - always ensure underlying data_model has conditional sanity checks against any select fields run 
 * through this method, as no 'input:type:hidden' is created.
 * 
 * @param  [type] $hasAccess  [description]
 * @param  string $name     [description]
 * @param  array  $options  [description]
 * @param  array  $selected [description]
 * @param  string $extra    [description]
 * @return [type]           [description]
 */
function my_form_dropdown($hasAccess, $name = '', $options = array(), $selected = array(), $extra = '')
{
  //ChromePHP::log('running my_form_dropdown() ');
  // return editable dropdown if hasAccess
  if($hasAccess){ return form_dropdown( $name, $options, $selected, $extra); }

  // else generate and return a read-only-list 
  if(!is_array($selected)){$selected = array($selected); }

  // If no selected state was submitted we will attempt to set it automatically
  if(count($selected) === 0){ 
    if (isset($_POST[$name])){ $selected = array($_POST[$name]); }// If the form name appears in the $_POST array we have a winner!
  }

  if(empty($selected)){ return '<span class="read-only-value lite ">'. lang('my_note_not_set') . '</span>'; }
//  if ($extra != '') $extra = ' '.$extra; //  $multiple = (count($selected) > 1 && strpos($extra, 'multiple') === FALSE) ? ' multiple="multiple"' : ''; //   $form = '<select name="'.$name.'"'.$extra.$multiple.">\n";

    $results = '<ul class="read-only-list read-only-select mb0 " >';
    $selectedValues= array();
    foreach ($options as $key => $val)
    {
      $key = (string) $key;
      if (is_array($val) && ! empty($val))
      {
        $results .= '$key = ' . $key . ' : ';//   $form .= '<optgroup label="'.$key.'">'."\n";
        foreach ($val as $optgroup_key => $optgroup_val)
        {
          $results .= '$optgroup_key = ' . $optgroup_key . ' : '; //   $sel = (in_array($optgroup_key, $selected)) ? ' selected="selected"' : '';
          if( (in_array($optgroup_key, $selected)) )
          {
            $selectedValues[] = $optgroup_val;
            $results .= '<span class="read-only-value read-only-select-value">' . $optgroup_val . '</span>';
          }
          $results .= '$optgroup_key = ' . $optgroup_key . ''; // $form .= '<option value="'.$optgroup_key.'"'.$sel.'>'.(string) $optgroup_val."</option>\n";
        }//$form .= '</optgroup>'."\n";
      }
      elseif( (in_array($key, $selected)) ){ $selectedValues[] = $val; $results .= '<li class="read-only-value">' . trim($val) . '</li>'; } //   $sel = (in_array($key, $selected)) ? ' selected="selected"' : '';//  $form .= '<option value="'.$key.'"'.$sel.'>'.(string) $val."</option>\n";
    }
    $results .= '</ul>';//   $form .= '</select>';
    if( sizeof($selectedValues) > 1 ){ // NOTE - no easy way to pass select fields with multiple values so as a workaround (to stop the data_model breaking) a hidden version of the original field is included
      $results .= form_dropdown( $name, $options, $selected, $extra . lang('my_hidden_field_css') ); // foreach( $selectedValues as $value )   {    }
    }
    else{
      // $results .= '<span class="red-text">HIDDEN FIELD</span>'. _my_hidden_field( $name, '', $selectedValues[0]);
      // NOTE, for now use the same approach for single option selects
      $results .= form_dropdown( $name, $options, $selected, $extra . lang('my_hidden_field_css') );
    }
    return $results;
  }

  // ------------------------------------------------------------------------

  function my_fix_image_paths($content, $param = array())
  {
    // NOTE - at one point had to run in subdirectory, tied code to fix that and to enhance a preloader image script here
    //        still rough and reverted as uneeded right now but fn kept to simplify merges and if needed later

    return $content;

    /*
    if($content == ''){return '';}
   
    $html = $content;

    $find = (isset($param['find']) ? $param['find']  : array("files/","/files/","../files/","../../files/","../../../files/","../../../../files/" ) );
    $replace = (isset($param['replace']) ? $param['replace']  : base_url().'files/' );
    if(FALSE)
    {

      $html = str_get_html( $content ); 
      foreach( $html->find("img") as $element) {
        $count = 1;
        foreach($find as $f){ if(substr_count(strtoupper($element->src),strtoupper($f),0,strlen($f))){ $element->src = str_ireplace($f,$replace,$element->src, $count); break; } }
        // NOTE IMPORTANT REVERTED cannot reliably change path to low res for intitial load as no way to check if js is truly enabled, instead switch it on domReady with js
        if(isset($param['preloadReady']) && $param['preloadReady']){ $src = $element->src; $element->src = my_get_preload_ready_path($src); }
      }

    }else{
      // NOTE simple replace, issues with mailto links when using html parser

      $fset = array();
      foreach($find as $k=>$f){ $fset[] = 'src="'.$f; $fset[] = "src='".$f;}
      $replace = 'src="'.$replace;
      $html = str_ireplace($fset, $replace, $html);

    }
    return $html;
    */
  }

  // ------------------------------------------------------------------------


  // Define stripos() if not defined (PHP < 5).
  if (!is_callable("stripos")) {
      function stripos($str, $needle, $offset = 0) {
          return strpos(strtolower($str), strtolower($needle), $offset);
      }
  }

  // ------------------------------------------------------------------------


  function my_substrpos($str, $start, $end = false, $ignore_case = false) {
      // Use variable functions
      if ($ignore_case === true) {
          $strpos = 'stripos'; // stripos() is included above in case it's not defined (PHP < 5).
      } else {
          $strpos = 'strpos';
      }

      // If end is false, set it to the length of $str
      if ($end === false) {
          $end = strlen($str);
      }

      // If $start is a string do what's needed to make it an integer position for substr().
      if (is_string($start)) {
          // If $start begins with '-' start processing until there's no more matches and use the last one found.
          if ($start{0} == '-') {
              // Strip off the '-'
              $start = substr($start, 1);
              $found = false;
              $pos = 0;
              while(($curr_pos = $strpos($str, $start, $pos)) !== false) {
                  $found = true;
                  $pos = $curr_pos + 1;
              }
              if ($found === false) {
                  $pos = false;
              } else {
                  $pos -= 1;
              }
          } else {
              // If $start begins with '\-', strip off the '\'.
              if ($start{0} . $start{1} == '\-') {
                  $start = substr($start, 1);
              }
              $pos = $strpos($str, $start);
          }
          $start = $pos !== false ? $pos : 0;
      }

      // Chop the string from $start to strlen($str).
      $str = substr($str, $start);

      // If $end is a string, do exactly what was done to $start, above.
      if (is_string($end)) {
          if ($end{0} == '-') {
              $end = substr($end, 1);
              $found = false;
              $pos = 0;
              while(($curr_pos = strpos($str, $end, $pos)) !== false) {
                  $found = true;
                  $pos = $curr_pos + 1;
              }
              if ($found === false) {
                  $pos = false;
              } else {
                  $pos -= 1;
              }
          } else {
              if ($end{0} . $end{1} == '\-') {
                  $end = substr($end, 1);
              }
              $pos = $strpos($str, $end);
          }
          $end = $pos !== false ? $pos : strlen($str);
      }

      // Since $str has already been chopped at $start, we can pass 0 as the new $start for substr()
      return substr($str, 0, $end);
  }

  // ------------------------------------------------------------------------


  /**
 * Sort a 2 dimensional array based on 1 or more indexes.
 * 
 * msort() can be used to sort a rowset like array on one or more
 * 'headers' (keys in the 2th array).
 * 
 * @param array        $array      The array to sort.
 * @param string|array $key        The index(es) to sort the array on.
 * @param int          $sort_flags The optional parameter to modify the sorting 
 *                                 behavior. This parameter does not work when 
 *                                 supplying an array in the $key parameter. 
 * 
 * @return array The sorted array.
 */
function my_msort($array, $key, $sort_flags = SORT_REGULAR) {
    if (is_array($array) && count($array) > 0) {
        if (!empty($key)) {
            $mapping = array();
            foreach ($array as $k => $v) {
                $sort_key = '';
                if (!is_array($key)) {
                    $sort_key = $v[$key];
                } else {
                    // @TODO This should be fixed, now it will be sorted as string
                    foreach ($key as $key_key) {
                        $sort_key .= $v[$key_key];
                    }
                    $sort_flags = SORT_STRING;
                }
                $mapping[$k] = $sort_key;
            }
            asort($mapping, $sort_flags);
            $sorted = array();
            foreach ($mapping as $k => $v) {
                $sorted[] = $array[$k];
            }
            return $sorted;
        }
    }
    return $array;
}