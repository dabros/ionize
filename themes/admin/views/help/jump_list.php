
<?php die('DEPRECATED'); ?>
<style type="text/css">

.help_list_title{
  margin-left:70px;

  margin-bottom:50px;
  font-size:35px;
}

.my_help_side_container{

  width: 180px;
  min-height: 250px;
  display: -moz-inline-stack;
  display: inline-block;
  vertical-align: top;
  margin:0;
  margin-left:-15px;
  zoom: 1;
  *display: inline;
  _height: 250px;

-webkit-border-top-right-radius: 10px;
-webkit-border-bottom-right-radius: 10px;
-moz-border-radius-topright: 10px;
-moz-border-radius-bottomright: 10px;
border-top-right-radius: 10px;
border-bottom-right-radius: 10px;
}
.my_navigation {
  padding:0px 20px 20px 15px;
}
.my_navigation a{
  color:#666;
  color:#098ED1;
  color:#3f3f3f;
}
.my_navigation .title{

  font-size: 12px;
  font-weight: bold;
}
.my_navigation ul{
  list-style: none;

  margin-bottom:10px;
  margin-left:0;
}
.my_navigation ul li{
  font-size: 12px;
}
.my_navigation ul li a,
.my_navigation span.title a{
  font-size: 12px;
  display: block;
  border-radius: 10px;
  padding:1px 8px;

}
.my_navigation span.title a{
  margin-left: -10px;

  font-weight: bold;

}
.my_navigation ul li a {
  padding:0 8px;

  font-size: 12px;
}
.my_navigation ul li a:hover,
.my_navigation ul li.active a,
.my_navigation span.title a:hover,
.my_navigation span.title.active a {
  text-decoration: none;
}

.my_group_title{
      background-color: transparent;
    border-bottom: 1px solid #666666;
    color: #2563A1;
    color: #555;
    font-size: 24px;
    font-weight: normal;
    margin:10px 0 20px;
    padding: 3px 0 7px 3px;
}

.help_list .my_group_title:first-of-type{
  margin-top:0px;
}

.my_topic_title{
      background-color: transparent;
    border-bottom: 1px solid #EEEEEE;
    color: #333333;
    font-size: 14px;
    font-weight: bold;
    margin: 10px 0 12px;
    padding: 0;
  }
</style>

<!--
  UNFINISHED - even in use??
-->

  <div id="<?php echo $windowId; ?>help_side_container" class='my_help_side_container my_navigation'>
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


<script>

/*
  $$('#<?php echo $windowId; ?>help_list .my_help_topic_list_link').each(function(item){
    item.addEvent('click', function(e){
      e.stop();
      var el = $('<?php echo $windowId; ?>help_list').getElement('#'+item.getProperty('rel'));
      console.log('topic_list_link clicked',{e:e,item:item,el:el});
      if(el) var myFx = new Fx.Scroll('<?php echo $windowId; ?>').toElement(el);
      return false;
    });
  });

  <?php if(!empty($jump_id)): ?> 
      if($('<?php echo $windowId; ?>help_list').getElement('#my_jump<?php echo $jump_id; ?>')) 
        var myFx = new Fx.Scroll('<?php echo $windowId; ?>').toElement($('<?php echo $windowId; ?>help_list').getElement('#my_topic_title<?php echo $jump_id; ?>'));
  <?php endif; ?>
*/
</script>