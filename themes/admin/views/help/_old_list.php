
<style type="text/css">
.help_topic p{
  margin-bottom:5px;
  margin-left:5px;
}

.main.help.mb20{
  padding-bottom:15px;
  padding-top:15px;
}
.help_list_title{
  margin-left:70px;

  margin-bottom:50px;
  font-size:35px;
}

.my_help_side_container{

  width: 150px;
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
.my_help_side_container {
}
.my_help_topics_list{
  list-style:none;
}
.my_help_topics_list li{

  }
.my_help_main_container{
  display:inline-block;

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

.my_category_title{
      background-color: transparent;
    border-bottom: 1px solid #666666;
    color: #2563A1;
    color: #555;
    font-size: 24px;
    font-weight: normal;
    margin: 0 0 20px;
    padding: 3px 0 7px 3px;
}

.my_topic_title{
      background-color: transparent;
    border-bottom: 1px solid #EEEEEE;
    color: #333333;
    font-size: 14px;
    font-weight: bold;
    margin: 25px 0 12px;
    padding: 0;
  }
</style>

<div id='help_list'>
  <h2 class="main help mb20"><span class='help_list_title'>OneCNC Backend Help Files</span></h2>


              <!-- CHANGED help link -->
                <a class="my_help_link right light button helpme type" rel='1'>
                  <i class="icon-helpme"></i><?php echo lang('my_label_help_article_list_page'); ?>
                </a>


  <div class='my_help_side_container my_navigation'>
    <span class='title'><a class='my_help_topic_list_link'><?php echo $category_title; ?></a></span>
    <ul class='my_help_topics_list'> 
    <?php foreach($topics as $d): ?>
      <li>
        <a class='my_help_topic_list_link'><?php echo $d['title']; ?></a>
      </li>
    <?php endforeach; ?>
    </ul>
  </div>
  <div class='my_help_main_container'>
    <h1 class='my_category_title'><?php echo $category_title; ?></h1>
    <?php foreach($topics as $d): ?>

      <h2 class="my_topic_title mb0"><?php echo $d['title']; ?></h2>

      <p><?php echo $d['content']; ?></p>

    <?php endforeach ;?>
  </div>

</div>


<script>

  // CHANGED page help link
  ION.my_initHelpTopic('#help_list .my_help_link');
  

</script>