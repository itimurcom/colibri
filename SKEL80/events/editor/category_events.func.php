<?php
// ================ CRC ================
// version: 1.15.02
// hash: 6e00b212cce6231ba1e8e05ae4ac30400bb5d5f533ba3422e54ae3443bafa291
// date: 16 September 2018 19:58
// ================ CRC ================
//..............................................................................
// обработчик событий объектов
//..............................................................................
function category_event_request_value($key, $default=NULL)
	{
	return (isset($_REQUEST) AND is_array($_REQUEST) AND array_key_exists($key, $_REQUEST)) ? $_REQUEST[$key] : $default;
	}

function category_event_ready_request_value($key, $default=NULL)
	{
	return ready_value(category_event_request_value($key), $default);
	}

function category_event_data($payload=NULL)
	{
	$payload = is_null($payload) ? category_event_request_value('data') : $payload;
	return function_exists('skel80_decode_encrypted_array')
		? skel80_decode_encrypted_array($payload, [])
		: (is_array(@unserialize(simple_decrypt($payload))) ? @unserialize(simple_decrypt($payload)) : []);
	}

function category_event_data_value($data, $key, $default=NULL)
	{
	return (is_array($data) AND array_key_exists($key, $data)) ? $data[$key] : $default;
	}

function category_event_positive_id($value)
	{
	$value = (int)$value;
	return $value>0 ? $value : NULL;
	}

function category_event_table_name($data, $default=DEFAULT_CATEGORY_TABLE)
	{
	$table_name = ready_value(category_event_data_value($data, 'table_name'), $default);
	return is_string($table_name) ? trim($table_name) : $default;
	}

function category_event_redirect($url)
	{
	cms_redirect_page($url);
	}

function category_events($url='/', $path=UPLOADS_ROOT)
	{
	$op = category_event_ready_request_value('op');
	if (empty($op)) return;

	switch ($op)
		{
		// категории
		case 'add_category' : {
			$data = category_event_data();
			$table_name = category_event_table_name($data);
			$value = category_event_ready_request_value('value');
			$lang = category_event_ready_request_value('lang', defined('CMS_LANG') ? CMS_LANG : '');
			$parent_id = (int)category_event_ready_request_value('category_id', 0);
			if (empty($table_name) OR empty($lang))
				{
				add_error_message('ERROR_OPTIONS_CATEGORY');
				category_event_redirect($url);
				break;
				}
			$values_arr = [
				'title_xml'	=> [$lang => $value],
				'parent_id'	=> $parent_id,
				'datetime'	=> get_mysql_time_str(strtotime('now')),
				];
			$rec_id = itMySQL::_insert_rec($table_name, $values_arr);
			category_event_redirect($url);
			break;
			}
			
		case 'set_parent' : {
			$data = category_event_data();
			$rec_id = category_event_positive_id(category_event_data_value($data, 'rec_id'));
			$table_name = category_event_table_name($data);
			$parent_id = (int)category_event_ready_request_value('parent_id', 0);
			if (is_null($rec_id) OR empty($table_name))
				{
				add_error_message('ERROR_SETTING_PARENT');
				category_event_redirect($url);
				break;
				}
			itCategory::set_parent($rec_id, $parent_id, $table_name);
			category_event_redirect($url);
			break;
			}

		case 'category_x' : {
			$data = category_event_data();
			$rec_id = category_event_positive_id(category_event_data_value($data, 'rec_id'));
			$table_name = category_event_table_name($data);
			if (is_null($rec_id) OR empty($table_name))
				{
				add_error_message('ERROR_REMOVEING_CATEGORY');
				category_event_redirect($url);
				break;
				}
			itCategory::x($rec_id, $table_name);
			category_event_redirect($url);
			break;
			}
		}
	}
?>
