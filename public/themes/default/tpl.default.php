<?php
if (!isset($_CONTENT) OR !is_array($_CONTENT)) $_CONTENT = [];
$tpl_controller = ready_value($_REQUEST['controller'] ?? NULL);
?>
<div class='siterow boxed'>
	<div class='flex boxed'>
		<div class='left80 boxed'>
			<div class='flex vertical boxed'>			
				<?=ready_value($_CONTENT['menu'] ?? NULL, get_menus_block());?>
			</div>
		</div>
		<div class='right20 boxed noipad'>
				<?=ready_value($_CONTENT['widgets'] ?? NULL)?>
		</div>
	</div>
</div>
<?=( ($tpl_controller!='cabinet') ? TAB."<div id='wishlist'>".wishlist().TAB."</div>" : NULL )?>
<?=ready_value($_CONTENT['content'] ?? NULL)?>
<div class='siterow empty boxed ipadonly'>
	<?=ready_value($_CONTENT['widgets-cell'] ?? NULL)?>
</div>
