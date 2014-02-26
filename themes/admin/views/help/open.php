
<style type="text/css">
#<?php echo $windowId; ?>_view_screen .help_view_screen{font-family: Verdana,"lucida grande",tahoma;}
#<?php echo $windowId; ?>_view_screen.help_view_screen{font-size: 11px;}
#<?php echo $windowId; ?>_view_screen.help_view_screen p {margin: 2px 1px 0px 0; padding:1px 0 3px; }
#<?php echo $windowId; ?>_view_screen.help_view_screen h1{}
#<?php echo $windowId; ?>_view_screen.help_view_screen h2{}
#<?php echo $windowId; ?>_view_screen.help_view_screen h3{font-size: 14px;margin-top: 15px;}
#<?php echo $windowId; ?>_view_screen.help_view_screen h4{}
#<?php echo $windowId; ?>_view_screen.help_view_screen h5{}
#<?php echo $windowId; ?>_view_screen.help_view_screen .subtitle{color:#777;font-family: tahoma;}
#<?php echo $windowId; ?>_view_screen.help_view_screen .group_subtitle{font-size:11px;margin-left: 56px;margin-top: -15px!important;}
#<?php echo $windowId; ?>_view_screen.help_view_screen .help_topic .subtitle:hover{color:#666;}
#<?php echo $windowId; ?>_view_screen.help_view_screen .help_topic{margin:5px 10px 15px;clear:both;display:block;padding-top:10px;padding-bottom:10px;}
#<?php echo $windowId; ?>_view_screen.help_view_screen .help_topic:first-of-type{margin-top:10px; padding-top:0px;}
#<?php echo $windowId; ?>_view_screen.help_view_screen .help_topic .content{}
#<?php echo $windowId; ?>_view_screen.help_view_screen .help_topic .content p{}
#<?php echo $windowId; ?>_view_screen.help_view_screen .help_topic .content br{}
#<?php echo $windowId; ?>_view_screen.help_view_screen .help_topic .subtitle{font-size:11px;}
#<?php echo $windowId; ?>_view_screen.help_view_screen .help_topic p.subtitle{margin-bottom:3px}
#<?php echo $windowId; ?>_view_screen.help_view_screen .help_topic h3{}
#<?php echo $windowId; ?>_view_screen.help_view_screen .help_topic ol,
#<?php echo $windowId; ?>_view_screen.help_view_screen .help_topic ul{margin:5px 20px;}
#<?php echo $windowId; ?>_view_screen.help_view_screen .help_topic ol li,
#<?php echo $windowId; ?>_view_screen.help_view_screen .help_topic ul li{margin:5px 0;}
#<?php echo $windowId; ?>_view_screen.help_view_screen img.small{margin:4px 1px -4px}
#<?php echo $windowId; ?>_view_screen.help_view_screen img.med{margin:14px 1px -14px}
#<?php echo $windowId; ?>_view_screen.help_view_screen img.border{border: 1px solid #ccc;}

#<?php echo $windowId; ?>_view_screen.help_view_screen img.inline{}
XXX .view_group {
border-bottom: 1px inset #ddd;
padding-bottom: 25px;
padding-top: 25px;
border: 1px solid #ddd;
padding: 20px;
margin-right: -5px;
margin-left: -5px;
}
#<?php echo $windowId; ?>_view_screen.help_view_screen_actions .button{ margin:0px; }
#<?php echo $windowId; ?>_view_screen.help_view_screen_actions .button.disabled{ border-width:0;margin:1px; }
#<?php echo $windowId; ?>_view_screen.help_view_screen_actions .button.disabled:hover{ border-width:1px;margin:0px; }
</style>


<div class='help_view_screen' id="<?php echo $windowId; ?>_view_screen">

  <input type='hidden' id="<?php echo $windowId; ?>_id_group_current" value="<?php echo $id_group; ?>" />
  <input type='hidden' id="<?php echo $windowId; ?>_id_group_h_index" value="0" />
  <input type='hidden' class='' id="<?php echo $windowId; ?>_id_group_history" value="<?php echo $id_group; ?>" />

  <span class='help_view_screen_actions h25 right m5 mt0' style='clear:right;'>

    <!-- link to printable help files -->
      <a class="my_full_help_link right light button print pl7 pr2 m1" title='Printable Version'>
        <i class="icon-printer"></i>
      </a> 

    <!-- link to toc -->
    <a class="my_help_link my_help_toc_link right light button direction pl7 pr2 m1" rel='50' title="Table of Contents">
      <i class="icon-direction"></i>
    </a> 

  <!-- </span><span class='h25 right m5 mt0' style='clear:right'> -->


    <!-- next nav -->
    <a id="" class="nav_next disabled  right  button light pl7 pr2 " style=''  title="Foward to Next Topic">
      <i class="icon-arrow-right"></i>
    </a>

    <!-- prev nav -->
    <a id="" class="nav_prev disabled  right  button light pl7 pr2 " style=''  title='Return to Previous Topic'>
      <i class="icon-arrow-left "></i>
    </a>

  </span>

  <div id="<?php echo $windowId; ?>_topic_groups_holder" class='topic_groups_holder '>
    <?php //foreach($topic_list as $topic_group): ?>
      <?php 
        $data = array('topic_list'=>$topic_list,'windowId'=>$windowId);//'topic_group'=>$topic_group
        $this->load->view('help/view_group', $data);
      ?>
    <?php // endforeach; ?>
  </div>
</div>

<div class="buttons">
  <button id="bClose<?php echo $windowId; ?>" type="button" class="button yes right mr40"><?php echo lang('ionize_button_ok'); ?></button>
</div>

<script type="text/javascript">



  var <?php echo $windowId; ?>_refresh_content = function(id_group){
    ION.HTML('help/refresh_group',
      {'windowId' : '<?php echo $windowId; ?>','id_group': id_group},
      {'update':'<?php echo $windowId; ?>_topic_groups_holder','spinner':'#w<?php echo $windowId; ?>_spinner.spinner'});
  }

  var <?php echo $windowId; ?>_navigate = function(action,id_group,title){
    var el_current = $('<?php echo $windowId; ?>_id_group_current');
    var el_h_index = $('<?php echo $windowId; ?>_id_group_h_index');
    var el_history = $('<?php echo $windowId; ?>_id_group_history');
    var el_nav_next = $$('#w<?php echo $windowId; ?> .nav_next');
    var el_nav_prev = $$('#w<?php echo $windowId; ?> .nav_prev');

    var h = el_history.value.split(',');
    var hindex = el_h_index.value;
    var gid = id_group;
    if(action == 'prev'){
      hindex--;
      gid = (hindex > -1 && (hindex < h.length)) ? h[hindex] : null;
    }else if(action == 'next'){
      hindex++;
      gid = (hindex < h.length) ? h[hindex] : null;
    }else{
      hindex++;
      // if opening new topic, check if not same as current
      if(gid != el_current.value){ // gid != h[hindex] && 
        h = h.splice(0,hindex);
        h.push(gid);
      }else
        gid = null;
    }

    /// console.debug('navigate',{gid:gid,h:h,hindex:hindex});

    // if !null, assume valid id and open topic group
    if(gid){
      <?php echo $windowId; ?>_refresh_content(gid);
      h.clean('');

      el_current.value = gid;
      el_history.value = h.join(',');
      el_h_index.value = hindex;

      if($('w<?php echo $windowId; ?>_title')){
        if(title){ $('w<?php echo $windowId; ?>_title').setProperty('text','Help: '+title); }
        else{ $('w<?php echo $windowId; ?>_title').setProperty('text','Help Files'); }
      }
      if($('w<?php echo $windowId; ?>_taskbarTabText')){
        if(title){ $('w<?php echo $windowId; ?>_taskbarTabText').setProperty('text','Help: '+title.substring(0,9)+'...'); }
        else{ $('w<?php echo $windowId; ?>_taskbarTabText').setProperty('text','Help Files'); }
      }


      if(hindex > 0){ el_nav_prev.removeClass('disabled'); }
      else{ el_nav_prev.addClass('disabled'); }
      if(hindex < (h.length - 1) ){ el_nav_next.removeClass('disabled'); }
      else{ el_nav_next.addClass('disabled'); }
    }else{
      $('w<?php echo $windowId; ?>').shake();
    }
  }


  $$('#w<?php echo $windowId; ?> .nav_prev').each(function(item){
    item.addEvent('click',function(e){
      e.stop();
      <?php echo $windowId; ?>_navigate('prev');
    });
  });

  $$('#w<?php echo $windowId; ?> .nav_next').each(function(item){
    item.addEvent('click',function(e){
      e.stop();
      <?php echo $windowId; ?>_navigate('next');
    });
  });

  // ION.my_initHelpTopic('#w<?php echo $windowId; ?> .my_help_toc_link');

  $$('#w<?php echo $windowId; ?> .my_help_toc_link').each(function(item){
    item.addEvent('click',function(e){
      e.stop();
      <?php echo $windowId; ?>_navigate('open',item.getProperty('rel'),item.getProperty('text'));
    });
  });


  // Event on btn No : Simply close the window
  $('bClose<?php echo $windowId; ?>').addEvent('click', function()
  {
    ION.closeWindow($('w<?php echo $windowId; ?>'));
  }.bind(this));

  

  // CHANGED page help link
  $$('#w<?php echo $windowId; ?> .my_full_help_link').each(function(item){

    item.addEvent('click', function(e){
      e.stop();
      ION.my_gotoHelpFiles($("<?php echo $windowId; ?>_id_group_current").value);
    });
  });
</script>


