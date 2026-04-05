<div class='siterow boxed'>
	<div class='flex boxed'>
		<div class='left80 boxed'>
			<div class='flex vertical boxed'>			
				<?=ready_val($_CONTENT['menu'], get_menus_block());?>
			</div>
		</div>
		<div class='right20 boxed noipad'>
				<?=ready_val($_CONTENT['widgets'])?>
		</div>
	</div>
</div>
<?=( ($_REQUEST['controller']!='cabinet') ? TAB."<div id='wishlist'>".wishlist().TAB."</div>" : NULL )?>
<?=ready_val($_CONTENT['content'])?>
<div class='siterow empty boxed ipadonly'>
	<?=ready_val($_CONTENT['widgets-cell'])?>
</div>