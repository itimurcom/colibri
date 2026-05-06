<?php
if (!isset($_CONTENT) OR !is_array($_CONTENT)) $_CONTENT = [];
?>
<div class='footer_div boxed'>
<?=get_footer_navigation()?>
</div> <!--footer-->
</div> <!--site_container-->
<span id="goup" class="goup boxed"></span>
<?=ready_value($_CONTENT['focus'] ?? NULL)?>
<?=ready_value($_CONTENT['analytics'] ?? NULL)?>
<!--<?=ready_value($_CONTENT['support'] ?? NULL)?>-->
<?=ready_value($_CONTENT['log'] ?? NULL)?>
<?php  include ('tpl.photoswipe.php'); ?>
<style> 
#editor-3402844035-text-0 {display:none !important}
</style>
</body>
</html>
