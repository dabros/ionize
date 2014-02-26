<?php
  // NOTE IMPORTANT - used in the 'printable' help files view
  
?>

<script type='text/javascript'>

</script>


<div id='<?php echo $windowId; ?>help_list' class=' help_list '>
  <h2 class="main help mb20"><span class='help_list_title'>OneCNC Backend Help Files</span>
  <?php //<a class="print_help_contentsBtn my_full_help_link right light button print" style='margin:-14px 2px 2px 10px;padding:5px 15px;' title="Print these Help Files" ><span>Print Help Files</span><i class="icon-printer"></i></a> ?>
  
  <?php //<a class="print_help_contentsBtn my_full_help_link  light button print" style='margin:-14px 2px 2px 10px;padding:5px 15px;' title="Print these Help Files" ><span>Print Help Files</span><i class="icon-printer"></i></a> ?>
  </h2>
  <?php /*
  <!-- <?php echo $windowId; ?> -->
              <!-- CHANGED help link 
                <a class="my_help_link right light button helpme type" rel='1'>
                  <i class="icon-helpme"></i><?php echo lang('my_label_help_article_list_page'); ?>
                </a>
-->
  */ ?>

  <div class='my_help_side_container my_navigation'>
     <?php //<a class="print_help_contentsBtn my_full_help_link  light button print" style='margin:-14px 2px 2px -3px;padding:5px 15px;' title="Print these Help Files" ><span>Print Help Files</span><i class="icon-printer"></i></a> ?>
    <?php foreach($topic_list as $topic_group): ?>
      <?php 

      $parent = $topic_group['parent'];
      $children = $topic_group['children'];

      $id = $parent['id_group'];
      $pname =  $parent['name'];
      $ptitle = ( ($parent['title'] != '') ? $parent['title'] : $parent['name'] );
      $psubtitle = $parent['subtitle'];

      ?>
        <span class='title'>
          <a class='my_help_topic_list_link' rel='my_jump<?php echo $id; ?>'><?php echo $ptitle; ?></a>
        </span>

        <ul class='my_help_topics_list'> 
        <?php foreach($children as $k=>$t): ?>
          <?php $rel = $id.$t['id_topic']; ?>
          <li>
            <a class='my_help_topic_list_link' rel="<?php echo 'my_jump'.$rel; ?>"><?php echo $t['title']; ?></a>
          </li>
        <?php endforeach; ?>
        </ul>

    <?php endforeach; ?>
  </div>


  <div class='my_help_main_container' id="my_help_main_container">
  <style type="text/css">
.help_list{}


.help_list .my_help_side_container{

  width: 180px;
  min-height: 250px;
  display: -moz-inline-stack;
  display: inline-block;
  vertical-align: top;
  margin:0;
  margin-left:-15px;
  zoom: 1;

position: absolute;
/*
-webkit-border-top-right-radius: 10px;
-webkit-border-bottom-right-radius: 10px;
-moz-border-radius-topright: 10px;
-moz-border-radius-bottomright: 10px;
border-top-right-radius: 10px;
border-bottom-right-radius: 10px;
*/
}
.help_list .my_help_side_container {}
.help_list .my_help_topics_list{list-style:none;}
.help_list .my_help_topics_list li{}

.help_list .my_help_main_container{
  display:inline-block;
  float:left;
  margin-left:230px;
  clear:none;
}



.help_list .my_help_main_container .my_group_title{
      background-color: transparent;
    border-bottom: 1px solid #666666;
    color: #2563A1;
    color: #555;
    font-size: 24px;
    font-weight: normal;
    margin:35px 0 20px;
    padding: 15px 0 12px 5px;
    clear:both;display:block;
}

.help_list .my_help_main_container .my_group_title:first-of-type{margin-top:0px;}

.help_list .my_help_main_container .group_subtitle{margin-top:-20px;padding-left:7px;display:block;}



.help_list .my_help_main_container .my_topic_title{
      background-color: transparent;
    border-bottom: 1px solid #EEEEEE;
    color: #333333;
    font-size: 14px;
    font-weight: bold;
    margin: 10px 0 12px;
    padding: 0;
  }

.help_list .help_topic{margin:5px 0 15px 20px!important;clear:both;display:block;padding-top:5px;padding-bottom:5px;}

.help_list .help_topic .content{
  font-size:12px;
}
.help_list .help_topic ol,.help_topic ul{
  margin-left:20px; 
}

.help_list .main.help.mb20{
  padding-bottom:15px;
  padding-top:15px;
}
.help_list .help_list_title{
  margin-left:70px;

  margin-bottom:50px;
  font-size:35px;
}




.help_list .my_navigation {
  padding:0px 20px 20px 15px;
}
.help_list .my_navigation a{
  color:#666;
  color:#098ED1;
  color:#3f3f3f;
}
.help_list .my_navigation .title{

  font-size: 12px;
  font-weight: bold;
}
.help_list .my_navigation ul{
  list-style: none;

  margin-bottom:10px;
  margin-left:0;
}
.help_list .my_navigation ul li{
  font-size: 12px;
}
.help_list .my_navigation ul li a,
.help_list .my_navigation span.title a{
  font-size: 12px;
  display: block;
  border-radius: 10px;
  padding:1px 8px;

}
.help_list .my_navigation span.title a{
  margin-left: -10px;

  font-weight: bold;

}
.help_list .my_navigation ul li a {
  padding:0 8px;

  font-size: 12px;
}
.help_list .my_navigation ul li a:hover,
.help_list .my_navigation ul li.active a,
.help_list .my_navigation span.title a:hover,
.help_list .my_navigation span.title.active a {
  text-decoration: none;
}

.my_rtop_link{
  float:right;
  font-size:12px;
  margin: 10px 5px 0;
  width:40px;
}
.my_rtop_link .icon{ 
  background-image: url('<?php echo theme_url(); ?>/images/arrows-up.gif');
background-position: 5px 4px;
  float:left;
}
.my_rtop_link:hover .icon{ 
background-position: 5px -16px;
}

  .my_group_title{ page-break-before:always; }
  .my_group_title:first-of-type{ page-break-before:auto; }
  
  .print_only{display:none!important;}

@media print {
  .no_print{display:none!important;}
  .print_only{display:block!important;}
  .print_ready .help_topic{}
  .print_ready .my_help_main_container .group_subtitle{margin-top:0px!important;padding-left:7px;}
  .print_ready .my_help_main_container{
    display:block;
    float:none;
    margin-left:0;
  }

}

</style>
    <?php foreach($topic_list as $topic_group): ?>
      <?php 

      $parent = $topic_group['parent'];
      $children = $topic_group['children'];

      $id = $parent['id_group'];
      $pname =  $parent['name'];
      $ptitle = ( ($parent['title'] != '') ? $parent['title'] : $parent['name'] );
      $psubtitle = $parent['subtitle'];

      ?>
      <h1 class='my_group_title' id="my_jump<?php echo $id; ?>"><?php echo $ptitle; ?><a class='my_rtop_link no_print'><i class='icon'></i><span class='right'>Top</span></a></h1>
      <?php if(!empty($psubtitle)) echo '<p class=" group_subtitle" style="">'.$psubtitle.'</p>'; ?>

      <?php foreach($children as $k=>$t): ?>

        <?php $rel = $id.$t['id_topic']; ?>
        <div class='help_topic' id='help_topic<?php echo $rel; ?>'>

          <h2 class="my_topic_title mb2" id="<?php echo 'my_jump'.$rel; ?>"><?php echo $t['title']; ?></h2>

          <?php if(!empty($t['subtitle'])) echo '<p class=" subtitle mb5">'.$t['subtitle'].'</p>'; ?>

          <div class='content'><?php echo $t['content']; ?></div>
        </div>
        <br/>
      <?php endforeach ;?>
    <?php endforeach; ?>
  </div>

</div>


<script>

  /**
   * Panel toolbox
   *
   */
  ION.initToolbox('help_print_toolbox');

  // Help links
  ION.my_initHelpTopic('#<?php echo $windowId; ?>help_list .my_help_link');

  $$('#<?php echo $windowId; ?>help_list .my_help_topic_list_link').each(function(item){
    item.addEvent('click', function(e){
      e.stop();
      var el = $('<?php echo $windowId; ?>help_list').getElement('#'+item.getProperty('rel'));
      /// console.log('topic_list_link clicked',{e:e,item:item,el:el});
      if(el) var myFx = new Fx.Scroll('<?php echo $windowId; ?>').toElement(el);
      return false;
    });
  });

  $$('#<?php echo $windowId; ?>help_list .my_rtop_link').each(function(item){
    item.addEvent('click', function(e){
      e.stop();
      var el = $('<?php echo $windowId; ?>help_list');
      if(el) var myFx = new Fx.Scroll('<?php echo $windowId; ?>').toElement(el);
      return false;
    });
  });

  <?php if(!empty($jump_id)): ?> 
      if($('<?php echo $windowId; ?>help_list').getElement('#my_jump<?php echo $jump_id; ?>')) 
        var myFx = new Fx.Scroll('<?php echo $windowId; ?>').toElement($('<?php echo $windowId; ?>help_list').getElement('#my_topic_title<?php echo $jump_id; ?>'));
  <?php endif; ?>

</script>
