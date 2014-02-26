 
  <?php foreach($topic_list as $topic_group): ?>
    <?php 
    /**
     * Special view that is loaded into a model or a screen, not unlike help/manage/definition.php, and populates 
     * the html with data for that single topic.
     *
     * Useful as it also allows for repopulating the content of help/topic.php modal
     */
    
    $parent = $topic_group['parent'];
    $children = $topic_group['children'];

    $id = $parent['id_group'];
    $pname =  $parent['name'];
    $ptitle = ( ($parent['title'] != '') ? $parent['title'] : $parent['name'] );
    $psubtitle = $parent['subtitle'];

    ?>
    <div id="<?php echo $windowId.'_'.$id; ?>_view_group" class='view_group mb50'>

      <h2 class="main help refresh_content"><?php echo $ptitle; ?></h2>

      <?php if(!empty($psubtitle)) echo '<p class=" subtitle group_subtitle pl2 mb10 block" style="margin-top:-10px;">'.$psubtitle.'</p>'; ?>


      <?php foreach($children as $t): ?>
        <div class='help_topic' id='help_topic<?php echo $id.'.'.$t['id_topic']; ?>'>

          <h3 class="mb10"><?php echo $t['title']; ?></h3>

          <?php if(!empty($t['subtitle'])) echo '<p class=" subtitle " style="margin-top:-10px;">'.$t['subtitle'].'</p>'; ?>

          <div class='content'><?php echo my_fix_image_paths($t['content'],array('preloadReady'=>false)); ?></div>

        </div>

      <?php endforeach ;?>
    </div>
    <script>

    $$('#<?php echo $windowId.'_'.$id; ?>_view_group .my_help_link').each(function(item){
      item.addEvent('click',function(e){
        e.stop();
        <?php echo $windowId; ?>_navigate('open',item.getProperty('rel'),item.getProperty('text'));
      });
    });

    $$('#<?php echo $windowId.'_'.$id; ?>_view_group .refresh_content').each(function(item){
      item.addEvent('click',function(e){
        e.stop();
       // <?php echo $windowId; ?>_refresh_content('<?php echo $id; ?>');
        ION.HTML('help/refresh_group',
          {'windowId' : '<?php echo $windowId; ?>','id_group': '<?php echo $id; ?>'},
          {'update':'<?php echo $windowId.'_'.$id; ?>_view_group','spinner':'#w<?php echo $windowId; ?>_spinner.spinner'});
      });
    }); 
      
    $$('#<?php echo $windowId.'_'.$id; ?>_view_group .my_article_type_list').each(function(item){ item.removeProperty('href'); });
    ION.initHelp('#<?php echo $windowId.'_'.$id; ?>_view_group .my_article_type_list', 'article_type', Lang.get('ionize_title_help_articles_types'));

    </script>

  <?php endforeach; ?>