<?
function support()
	{
	if (
		(!is_null(DENY_JIVOSITE) AND in_array($_REQUEST['controller'], str_getcsv(DENY_JIVOSITE))) OR
		(!is_null(ALOW_JIVOSITE) AND !in_array($_REQUEST['controller'], str_getcsv(ALOW_JIVOSITE)))
			)
		{
		return;
		}
	$chatroom_id = (CMS_LANG=='ru') ? "lIfyrCWKWL" : "UQ0fJuCrzD";
	return TAB."<script src='//code.jivosite.com/widget.js' data-jv-id='{$chatroom_id}' async></script>";
	}
?>