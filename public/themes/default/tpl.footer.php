<div class='footer_div boxed'>
<?=get_footer_navigation()?>
</div> <!--footer-->
</div> <!--site_container-->
<span id="goup" class="goup boxed"></span>
<?=ready_val($_CONTENT['focus'])?>
<?=ready_val($_CONTENT['analytics'])?>
<!--<?=ready_val($_CONTENT['support'])?>-->
<script>
        (function(w,d,u){
                var s=d.createElement('script');s.async=true;s.src=u+'?'+(Date.now()/60000|0);
                var h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(s,h);
        })(window,document,'https://cdn.bitrix24.eu/b21450295/crm/site_button/loader_1_l3l2rw.js');
</script>
<?=ready_val($_CONTENT['log'])?>
<? include ('tpl.photoswipe.php'); ?>
<style> 
#editor-3402844035-text-0 {display:none !important}
</style>
</body>
</html>