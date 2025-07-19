<?
//..............................................................................
// любое изображение из массива изображений "нет картинки"
//..............................................................................
function get_random_noavatar()
	{
	global $no_avatar;
	$index = rand (1, count($no_avatar));
	$result = "themes/".CMS_THEME."/images/".$no_avatar[ $index -1 ];
	return $result;
	}
?>