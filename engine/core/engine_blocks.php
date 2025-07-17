<?php
//..............................................................................
// виджет контактов
//..............................................................................
function get_colibri_block($block_id=1, $main=false)
	{
	global $_USER, $plug_og;
	
	$title = NULL;
	$admin = NULL;

	$o_block = new itBlock($block_id, [
		'no_data'	=> true,
		'no_title'	=> true,
		'no_related'	=> true,
		'no_lang'	=> true,
		'class'		=> 'copy',
		'edclass'	=> 'dashed_blue',
		]);
	$o_block->compile();

	$btn_str = NULL;

	$tag = $main ? "h1" : "h2";
	
	if (!is_null($o_block->editor))
		{
		switch($block_id)
			{
			case BLOCK_SHOP : {
				break;
				}
			case BLOCK_LASTSEEN : {
				$btn_str = get_lastseen_event();
				}
			default : {
				$title = TAB."<{$tag} class='tit'>".($title_str=get_field_by_lang($o_block->editor->data['title_xml'], CMS_LANG, ''))."</{$tag}>";
				$admin = ($_USER->is_logged())
					?	TAB."<div class='admin_panel_div'>".
//						(function_exists('get_content_remove_event') ? get_content_remove_event($o_block->editor->data) : "").
						(function_exists('get_content_title_event') ? get_content_title_event($o_block->editor->data) : "").			
						TAB."</div>"
					: NULL;
				$admin.= TAB.get_block_content_event($o_block->data);
				break;
				}
			}
		}
	
			
	$code = !is_null($o_block->editor)
		?	TAB."<div class='siterow boxed'>".
			$o_block->editor->code().
			TAB."</div>".
			TAB."<div class='ed_devider'></div>"
		: NULL;

	unset($o_block);

	return 
		TAB."<div class='block'>".
		$title.
		$btn_str.
		$admin.
		$code.		
		TAB."</div>";
	}
?>