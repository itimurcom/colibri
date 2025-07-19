<?
//..............................................................................
// умный переход в случае успешного логина или выхода из системы
//..............................................................................
function cms_smart_redirect($outgo_page=NULL, $exclude=['login','enter'])
	{
	if (isset($_SESSION['HTTP_REFERER']))
		{
	 	$referer_url = $_SESSION['HTTP_REFERER'];

		unset($_SESSION['HTTP_REFERER']);
		} else if (isset($_SERVER['HTTP_REFERER']))
			{
		 	$referer_url = $_SERVER['HTTP_REFERER'];
			} else $referer_url = is_null($outgo_page) ? '/' : $outgo_page;

	$split = explode('?', $referer_url);
	$referer_url = $split[0];

	$referer_url = in_array(basename($referer_url), $exclude) ? '/' : $referer_url;

	cms_redirect_page($referer_url);
	}
?>