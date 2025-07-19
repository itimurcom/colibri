<?php
// ================ CRC ================
// version: 1.15.02
// hash: 6e00b212cce6231ba1e8e05ae4ac30400bb5d5f533ba3422e54ae3443bafa291
// date: 16 September 2018 19:58
// ================ CRC ================
//..............................................................................
// обработчик событий объектов
//..............................................................................
function category_events($url='/', $path=UPLOADS_ROOT)
	{
	switch ($_REQUEST['op'])
		{
		// категории
		case 'add_category' : {
			$data = unserialize(simple_decrypt($_REQUEST['data']));
			$values_arr = [
				'title_xml'	=> [$_REQUEST['lang'] => $_REQUEST['value']],
				'parent_id'	=> $_REQUEST['category_id'],
				'datetime'	=> get_mysql_time_str(strtotime('now')),
				];
			$rec_id = itMySQL::_insert_rec($data['table_name'], $values_arr);
			cms_redirect_page("$url");
			break;
			}
			
		case 'set_parent' : {
			$data = unserialize(simple_decrypt($_REQUEST['data']));
			itCategory::set_parent($data['rec_id'], $_REQUEST['parent_id'], $data['table_name']);
			cms_redirect_page("$url");
			}

		case 'category_x' : {
			$data = unserialize(simple_decrypt($_REQUEST['data']));
			itCategory::x($data['rec_id'], $data['table_name']);
			cms_redirect_page("$url");
			}			
		}
	}
?>