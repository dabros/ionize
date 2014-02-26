
<div class="toolbox divider">
  <input type="button" class="toolbar-button plus" id="addHelpGroupButton" value="Create New Group" />
</div>

<div class="toolbox divider">
	<input type="button" class="toolbar-button eye-open" id="collapseGroupDetailsButton" value="Show Simple View" />
</div>

<script type="text/javascript">

	$('addHelpGroupButton').addEvent('click', function(e){	
		ION.JSON('help/create_group');
	});

  if($('collapseGroupDetailsButton'))
  {
    $('collapseGroupDetailsButton').removeEvents('click');

    $$('.topic_definition_details').each(function(el){
      el.store('max',el.height); //.getStyle('height'));
      //el.fx = new Fx.Morph(el, {duration: 500, transition: Fx.Transitions.Sine.easeOut});
     // el.fx2 = new Fx.Morph(el, {duration: 1000, transition: Fx.Transitions.Sine.easeIn});
      //el.fx2 = new Fx.Morph($(el.getParent('td')), {duration: 200, transition: Fx.Transitions.Sine.easeOut});
    });

    var toggleHelpGroupDetails = function(item,t)
    {
      // item.fx.toggle();
      item.toggleClass('collapsed').getParent().toggleClass('collapsed');
     //item.getElements('.help_group_edit_field_wrapper').toggleClass('block-clear');//.toggleClass('left');
      // var c = item.getElements('.topic').length;

      var max = item.retrieve('max');
      var from = 0;
      var to = max;

      if (item.hasClass('collapsed') == 0){
        from = max;
        to = 0;
       // item.fx2.start({'height': [from, to]});
      }else{
      //  item.fx.start({'height': [from, to]});
      }
   //   if(item.hasOwnProperty('fx')) item.fx.start({'height': [from, to]});
      var fx = new Fx.Morph(item, {duration: 500, transition: Fx.Transitions.Sine.easeOut}).start({'height': [from, to]});
    };

    $('collapseGroupDetailsButton').addEvent('click',function(e){
      e.stop();
      this.toggleClass('collapsed');
      this.value = (this.hasClass('collapsed')) ? "Show Full View" : "Show Simple View";
      $$('.topic_definition_details').each(function(item,i){
        toggleHelpGroupDetails(item,i);
      });
    });


    /*
    // TODO UNFINISHED, 3 levels of detail - allowing for more control
    var toggleGroupDetailLevels = function(item,t)
    {
      var vl = item.getProperty('data-viewlevel');
      if(!vl || vl > 2) vl = 0;
      else vl++;

      var parent = item.getParent();
      var max = item.retrieve('max');
      //var med = item.retrieve('med');
      var from = 0;
      var to = max;

      switch(vl)
      {
        case 0:
          parent.addClass('collapsed')
          parent.addClass('min-view');
          parent.removeClass('simple-view');
          parent.removeClass('full-view');
          from = max;
          to = 0;
        break;
        case 1:
          parent.addClass('collapsed')
          parent.removeClass('min-view');
          parent.addClass('simple-view');
          parent.removeClass('full-view');
          from = 0;
          to = 0;
        break;
        case 2:
          parent.removeClass('collapsed')
          parent.removeClass('min-view');
          parent.removeClass('simple-view');
          parent.addClass('full-view');
          from = 0;
          to = max;
        break;
      }

      parent.setProperty('data-viewlevel',vl);

      // item.fx.toggle();
      item.toggleClass('collapsed').getParent().toggleClass('collapsed');
     //item.getElements('.help_group_edit_field_wrapper').toggleClass('block-clear');//.toggleClass('left');
      // var c = item.getElements('.topic').length;


      if (item.hasClass('collapsed') == 0){
        from = max;
        to = 0;
       // item.fx2.start({'height': [from, to]});
      }else{
      //  item.fx.start({'height': [from, to]});
      }
   //   if(item.hasOwnProperty('fx')) item.fx.start({'height': [from, to]});
      var fx = new Fx.Morph(item, {duration: 500, transition: Fx.Transitions.Sine.easeOut}).start({'height': [from, to]});
    };

*/
  }

</script>
