<?
//..............................................................................
// возвращает код баннера
//..............................................................................
function get_colibri_banner_event($page_id=NULL)
	{
	global $_USER;
	
	$result = NULL;
	$btn_str = NULL;

	if ($row = itMySQL::_get_rec_from_db('banners', $page_id))
		{
		if (isset($row['images_xml']) AND isset($row['images_xml'][CMS_LANG]))
			$result = TAB."<img src='".get_thumbnail($row['images_xml'][CMS_LANG], 'BANNER')."'>";
		} else	{
			itMySQL::_insert_rec('banners',['id'=>$page_id, 'images_xml'=>NULL]);
			}

	if ($_USER->is_logged())
		{
		if (is_null($row) OR !isset($row['images_xml'][CMS_LANG]))
			{
			$options = [
				'type'		=> 'file',
				'class' 	=> 'admin', 
				'name' 		=> get_const('DEFAULT_FILES_NAME'),
				'table_name' 	=> 'banners',
				'rec_id' 	=> $row['id'],
				'op' 		=> 'banner',
				];
			$btn = 'Добавить Баннер';
			$color = 'green';
			$b_files = new itButton(get_const($btn), 'file', $options, $color);
			$btn_str = $b_files->code();
			unset($b_files);
			} else	{
				$o_modal = new itModal();
				$o_modal->set_size('small');
				$o_modal->set_animation('fadeAndPop');

				$o_form = new itForm2();
				$o_form->add_title("Удалить баннер на этой странице?");
				$o_form->add_data([
					'table_name'	=> 'banners',
					'rec_id'	=> $row['id'],
					'op'		=> 'bannerx',
					]);
				$o_form->add_itButton(get_const('BUTTON_REMOVE'), 'submit', ['form' => $o_form->form_id()], 'red' );	
				$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
				$o_form->compile();

				$o_modal->add_field($o_form->code());
				$o_modal->compile();

				$o_button = new itButton('Удалить Баннер', 'modal', ['class' => 'admin', 'form' => $o_modal->form_id()], 'red' );
				$btn_str = $o_button->code().$o_modal->code();
				unset($o_button, $o_form, $o_modal);
				}
		}		
	return ($_USER->is_logged() OR $result)
		? TAB."<div class='siterow boxed'>".
			TAB."<div class='banner boxed'>".
			$result.
			TAB."</div>".
			$btn_str.
		TAB."</div>"
		: NULL;
	}
?>