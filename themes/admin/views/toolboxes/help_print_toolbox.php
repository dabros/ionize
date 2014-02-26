
<div class="divider toolbox">
  <a class="button light print_help_contentsBtn" id="print_help_contentsBtn">
    <i class="icon-print"></i>Print Help Files
  </a>
</div>
<script type="text/javascript">

  /**
   * Init of all main menu links
   *
   */
  $$('.print_help_contentsBtn').each(function(item)
  {
    item.addEvent('click', function(event)
    {
      event.preventDefault();
      var divName = "my_help_main_container";
      var printContents = document.getElementById(divName).innerHTML;
      var myWindow=window.open('','_blank','');//width=200,height=100
      myWindow.document.write('<html><head><title>OneCNC CMS Help Files</title><link type="text/css" rel="stylesheet" href="<?php echo theme_url(); ?>javascript/mochaui/Themes/ionize/css/core.css" /><link rel="stylesheet" href="<?php echo theme_url(); ?>css/form.css" type="text/css" /><link rel="stylesheet" href="<?php echo theme_url(); ?>css/content.css" type="text/css" /><link rel="stylesheet" href="<?php echo theme_url(); ?>css/mystyles.css" type="text/css" /></head><body>');
      myWindow.document.write('<div class="help_list print_ready"><div class="my_help_main_container">'+printContents+'</div></div>');
      myWindow.document.write('</body></html>');

      myWindow.document.close();
      myWindow.focus();
      myWindow.print();
      myWindow.close();
    });
  });

</script>
