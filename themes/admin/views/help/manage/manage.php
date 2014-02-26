
<div id="maincolumn">
	
<!-- Existing topics -->
	<h2 class="main elements"><?php echo lang('my_help_link_manage'); ?></h2>
	
	<ul id="manageListContainer" class="sortable-container mt20"></ul>
	
  
  <div id="orphanTopicsListContainer" class="mt50 left clear-block block-clear" style="width:90%"></div>

</div>

<script type="text/javascript">


  ION.HTML('help/get_manage_list', {}, {'update': 'manageListContainer' });
	ION.HTML('help/get_orphan_topics_list', {}, {'update': 'orphanTopicsListContainer' });
	
	
	/**
	 * Panel toolbox
	 *
	 */
	
	ION.initToolbox('help_manage_toolbox');
	
	

</script>
