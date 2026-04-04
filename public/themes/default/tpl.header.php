<?
global $_LDJSON, $_RDFA, $_SCHEMA;
$o_org = new itMarkOrg([
	'logo'	=> SERVER_HTTP_DEBUG."/themes/default/images/top_left_logo.png",
	]);
if (isset($_CONTENT['refresh']) AND isset($_CONTENT['redirect']))
	{
	$refresh = TAB."<meta http-equiv='refresh' content='{$_CONTENT['refresh']};URL={$_CONTENT['redirect']}'>";
	}
?><!DOCTYPE html>
<html lang='<?=CMS_LANG?>'>
	<head>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-NCHR6VK');</script>
<!-- End Google Tag Manager -->
	<?=ready_val($_CONTENT['header'])?>
<?=$_LDJSON?>
	<?=minify_css(get_background_css());?>
	</head>
<?

?>
<body class='boxed'>
  <!-- Messenger Плагин чата Code -->
    <div id="fb-root"></div>

    <!-- Your Плагин чата code -->
    <div id="fb-customer-chat" class="fb-customerchat">
    </div>

    <script>
      var chatbox = document.getElementById('fb-customer-chat');
      chatbox.setAttribute("page_id", "152473004821835");
      chatbox.setAttribute("attribution", "biz_inbox");
    </script>

    <!-- Your SDK code -->
    <script>
      window.fbAsyncInit = function() {
        FB.init({
          xfbml            : true,
          version          : 'v13.0'
        });
      };

      (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));
    </script>  
    
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NCHR6VK"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<?=$_RDFA?>
<?=$_SCHEMA?>

<?=ready_val($_CONTENT['async'])?>
<!-- <div id='wrapper'> -->
<?=get_iphone_menu();?>
<div class='site_container boxed rounded'>
<?=ready_val($_CONTENT['admin']);?>
<?=ready_val($_CONTENT['error'])?>