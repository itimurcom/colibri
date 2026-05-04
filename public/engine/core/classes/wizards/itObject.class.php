<?php
global $object_counter;
$object_counter = (function_exists('rand_id')) ? rand_id() : time();

definition([
	'DEFAULT_ITEM_TABLE'	=> 'items',
	'DEFAULT_OBJECT_TABLE'	=> 'objects',
	'DEFAULT_CATEGORY_TABLE'=> 'categories',
	'DEFAULT_WIZARD_TABLE'	=> 'categories',
	'DEFAULT_WIZARD_VALUES'	=> 'values_xml',
	'DEFAULT_WIZARD_FIELD' 	=> 'wizard_xml',
	'DEFAULT_OBJECT_ACTION'	=> '/ed_field.php',
	'DEFAULT_OBJECT_METHOD'	=> 'POST',
	'DEFAULT_OBJECT_CAPTCHA'=> false,
	'DEFAULT_OBJECT_OP'	=> 'obj_form',
	'DEFAULT_CAT_FIELD'	=> 'category_id',
	]);

class itObject
	{
	public $table_name, $rec_id, $data, $wizard, $val_field, $wiz_field, $wiz_values, $cat_field, $code;
	public $action, $method, $reCaptcha;
	public $name, $category_name, $placeholder, $op;
	public function __construct($options=NULL)
		{
		global $object_counter;
		$options = is_array($options) ? $options : [];
		$object_counter ++;

		$this->name 		= "object-{$object_counter}";
		$this->table_name	= ready_val(isset($options['table_name']) ? $options['table_name'] : NULL, get_const('DEFAULT_OBJECT_TABLE'));
		$this->category_name	= ready_val(isset($options['category_name']) ? $options['category_name'] : NULL, get_const('DEFAULT_CATEGORY_TABLE'));
		$this->rec_id		= ready_val(isset($options['rec_id']) ? $options['rec_id'] : NULL);
		$this->data 		= itMySQL::_get_rec_from_db($this->table_name, $this->rec_id);
		$this->data		= is_array($this->data) ? $this->data : [];

		$this->wiz_field	= ready_val(isset($options['wiz_field']) ? $options['wiz_field'] : NULL, DEFAULT_WIZARD_FIELD);
		$this->cat_field	= ready_val(isset($options['cat_field']) ? $options['cat_field'] : NULL, DEFAULT_CAT_FIELD);
		$this->wiz_values	= ready_val(isset($options['wiz_values']) ? $options['wiz_values'] : NULL, DEFAULT_WIZARD_VALUES);

		$this->action		= ready_val(isset($options['action']) ? $options['action'] : NULL, DEFAULT_OBJECT_ACTION);
		$this->method		= ready_val(isset($options['method']) ? $options['method'] : NULL, DEFAULT_OBJECT_METHOD);
		$this->placeholder	= ready_val(isset($options['placeholder']) ? $options['placeholder'] : NULL, get_const('DEFAULT_PLACEHOLDER'));
		$this->reCaptcha	= ready_val(isset($options['reCaptcha']) ? $options['reCaptcha'] : NULL, DEFAULT_OBJECT_CAPTCHA);
		$this->op		= ready_val(isset($options['op']) ? $options['op'] : NULL, DEFAULT_OBJECT_OP);

		$this->get_wizard();
		}

	public function get_wizard()
		{
		$this->wizard = [];
		if (isset($this->data[$this->cat_field]))
			{
			$cat_rec['parent_id'] = $this->data[$this->cat_field];
			$i=0;
			$wiz_arr=[];
			while (isset($cat_rec['parent_id']) AND $cat_rec['parent_id']!=0)
				{
				$i++;
				$options = [
				'table_name'	=>	$this->category_name,
				'rec_id'	=> 	$cat_rec['parent_id'],
				];

				$o_wizard = new itWizard($options);
				$cat_rec = is_array($o_wizard->ed_rec) ? $o_wizard->ed_rec : [];
				$wiz_arr[$i] = $cat_rec;
				unset($o_wizard);
				if ($i>5) break;
				}
			if (is_array($wiz_arr))
				{
				krsort($wiz_arr);
				foreach($wiz_arr as $key=>$row)
					{
					if (isset($row[$this->wiz_field]) AND is_array($row[$this->wiz_field]))
						{
						foreach ($row[$this->wiz_field] as $wiz_key=>$wiz_row)
							{
							if (!isset($wiz_row['name'])) continue;
							$this->wizard[$wiz_row['name']] = $wiz_row;
							$this->wizard[$wiz_row['name']]['value']
								= isset($this->data[$this->wiz_values][$wiz_row['name']]) ? $this->data[$this->wiz_values][$wiz_row['name']] : NULL;

							$value_text  = is_null($this->wizard[$wiz_row['name']]['value']) ? get_const('NO_DATA') : $this->wizard[$wiz_row['name']]['value'];

							$type = isset($wiz_row['type']) ? $wiz_row['type'] : DEFAULT_WIZARD_TYPE;
							if (!in_array($type, unserialize(WIZARD_NOTITLES)))
								{
								$sel_arr = [];
								$titles = isset($wiz_row['titles'][CMS_LANG]) && is_array($wiz_row['titles'][CMS_LANG]) ? $wiz_row['titles'][CMS_LANG] : [];
								foreach($titles as $sel_key=>$sel_row)
									{
									if (!isset($wiz_row['values'][$sel_key])) continue;
									$sel_arr[$wiz_row['values'][$sel_key]] =
										[
										'title'	=> get_const($sel_row),
										'value'	=> $wiz_row['values'][$sel_key],
										];
									}
								$value_text = isset($sel_arr[$value_text]['title']) ? $sel_arr[$value_text]['title'] : get_const('NO_DATA');
								}
							$this->wizard[$wiz_row['name']]['text']	= $value_text;
							$this->wizard[$wiz_row['name']]['table_name']	= $this->table_name;
							$this->wizard[$wiz_row['name']]['rec_id'] = $this->rec_id;
							}
						}
					}
				}
			}

		}

	public function store()
		{
		if (!is_array($this->data) OR empty($this->rec_id))
			{
			return false;
			}
		itMySQL::_update_db_rec($this->table_name, $this->rec_id, $this->data);
		return true;
		}

	public function compile()
		{
		$this->get_wizard();
		$rows_code = NULL;
		if (is_array($this->data))
			{
			$rows = NULL;

			$this->code =
				TAB."<div class='wizard'>";

			$this->wizard();
			$this->code .=
				TAB."</div>";
			}
		}

	public function code()
		{
		return ($this->code);
		}

	static function _add($options=NULL)
		{
		global $_USER;
		$options = is_array($options) ? $options : [];
		$cat_field = ready_val(isset($options['cat_field']) ? $options['cat_field'] : NULL, DEFAULT_CAT_FIELD);
		if (isset($options[$cat_field]))
			{
			$options['table_name']	= ready_val(isset($options['table_name']) ? $options['table_name'] : NULL, DEFAULT_WIZARD_TABLE);

			$user_id = (is_object($_USER) AND method_exists($_USER, 'id')) ? $_USER->id() : NULL;

			$values_arr = [
				'user_id'	=> ready_val(isset($options['user_id']) ? $options['user_id'] : NULL, $user_id),
				'avatar'	=> ready_val(isset($options['avatar']) ? $options['avatar'] : NULL),
				$cat_field	=> $options[$cat_field],
				'status'	=> 'PUBLISHED',
			];

			if (isset($options['title']))
				$values_arr['title_xml'][CMS_LANG] = $options['title'];

			if (isset($options['description']))
				$values_arr['ed_xml'][CMS_LANG] = $options['description'];

			if (isset($options['values']))
				$values_arr['values_xml'] = $options['values'];
			$rec_id = itMySQL::_insert_rec($options['table_name'], $values_arr);
			return $rec_id;
			} else add_error_message('ERROR_OPTIONS_OBJECT');
		}

	static function _update_value($options=NULL)
		{
		$options = is_array($options) ? $options : [];
		if (isset($options['rec_id']) AND isset($options['name']))
			{
			$options['table_name'] = ready_val(isset($options['table_name']) ? $options['table_name'] : NULL, DEFAULT_OBJECT_TABLE);
			$o_object = new itObject($options);
			if (!is_array($o_object->data) OR empty($o_object->data))
				{
				add_error_message('ERROR_OPTIONS_OBJECT');
				unset($o_object);
				return;
				}
			if (!isset($o_object->data[$o_object->wiz_values]) OR !is_array($o_object->data[$o_object->wiz_values]))
				{
				$o_object->data[$o_object->wiz_values] = [];
				}
			$o_object->data[$o_object->wiz_values][$options['name']] = ready_val(isset($options['value']) ? $options['value'] : NULL);
			$o_object->store();
			unset($o_object);
			} else add_error_message('ERROR_OPTIONS_OBJECT');
		}

	static function _set_category($options=NULL)
		{
		$options = is_array($options) ? $options : [];
		$cat_field = ready_val(isset($options['cat_field']) ? $options['cat_field'] : NULL, DEFAULT_CAT_FIELD);
		if (isset($options['rec_id']) AND isset($options['value']))
			{
			$options['table_name'] = ready_val(isset($options['table_name']) ? $options['table_name'] : NULL, DEFAULT_OBJECT_TABLE);
			itMySQL::_update_value_db($options['table_name'], $options['rec_id'], $options['value'], $cat_field);
			} else add_error_message('ERROR_OPTIONS_OBJECT');
		}

	static function _set_title($options=NULL)
		{
		$options = is_array($options) ? $options : [];
		if (isset($options['rec_id']) AND isset($options['value']))
			{
			$options['table_name'] = ready_val(isset($options['table_name']) ? $options['table_name'] : NULL, DEFAULT_OBJECT_TABLE);
			$row = itMySQL::_get_rec_from_db($options['table_name'], $options['rec_id']);
			if (!is_array($row))
				{
				add_error_message('ERROR_OPTIONS_OBJECT');
				return;
				}
			if (!isset($row['title_xml']) OR !is_array($row['title_xml']))
				{
				$row['title_xml'] = [];
				}
			$row['title_xml'][CMS_LANG] = $options['value'];
			itMySQL::_update_value_db($options['table_name'], $options['rec_id'], $row['title_xml'], 'title_xml');
			} else add_error_message('ERROR_OPTIONS_OBJECT');
		}

	public function wizard()
		{
		if (is_array($this->wizard))
			{
			$rows = [];
			if (function_exists('get_object_wizard_row_event'))
			foreach($this->wizard as $key=>$row)
				{
				$rows[] =
					TAB."<div class='row'>".
					get_object_wizard_row_event($row).
					TAB."</div>";
				}
			$this->code .=
				TAB."<div class='list'>".
				get_object_category_event(is_array($this->data) ? $this->data : []).
				implode('', $rows).
				TAB."</div>";
			}
		}

	protected function objectFormInputOptions($row, $type=NULL)
		{
		$options = [
			'name'		=> isset($row['name']) ? $row['name'] : '',
			'value'		=> isset($row['value']) ? $row['value'] : NULL,
			'label'		=> get_field_by_lang(isset($row['label']) ? $row['label'] : NULL, CMS_LANG, ''),
			'compact'	=> true,
			];
		if (!is_null($type))
			{
			$options['type'] = $type;
			}
		return $options;
		}

	protected function objectFormSelectOptions($row)
		{
		$sel_arr = [];
		$titles = isset($row['titles'][CMS_LANG]) && is_array($row['titles'][CMS_LANG]) ? $row['titles'][CMS_LANG] : [];
		foreach($titles as $sel_key=>$sel_row)
			{
			if (!isset($row['values'][$sel_key])) continue;
			$sel_arr[$row['values'][$sel_key]] = [
				'title'	=> get_const($sel_row),
				'value'	=> $row['values'][$sel_key],
				];
			}
		return [
			'array' 		=> $sel_arr,
			'titles'        => 'title',
			'values'	=> 'value',
			'name'		=> isset($row['name']) ? $row['name'] : '',
			'compact'	=> 1,
			'value'		=> isset($row['value']) ? $row['value'] : NULL,
			'label'		=> get_field_by_lang(isset($row['label']) ? $row['label'] : NULL),
			];
		}

	protected function appendObjectFormField($o_form, $row)
		{
		$type = isset($row['type']) ? $row['type'] : DEFAULT_WIZARD_TYPE;
		switch ($type)
			{
			case 'text' :
				$o_form->add_input($this->objectFormInputOptions($row));
				break;
			case 'email' :
				$o_form->add_input($this->objectFormInputOptions($row, 'email'));
				break;
			case 'phone' :
				$o_form->add_input($this->objectFormInputOptions($row, 'phone'));
				break;
			case 'select' :
				$o_form->add_selector('select', $this->objectFormSelectOptions($row));
				break;
			default :
				$o_form->add_input(isset($row['name']) ? $row['name'] : '', isset($row['value']) ? $row['value'] : NULL, get_field_by_lang(isset($row['label']) ? $row['label'] : NULL, CMS_LANG, ''), false, 'compact');
				break;
			}
		}

	protected function objectFormRow($row)
		{
		$name = isset($row['name']) ? $row['name'] : NULL;
		$row['value']		= (!is_null($name) AND isset($this->data[$this->wiz_values][$name])) ? $this->data[$this->wiz_values][$name] : NULL;
		$row['table_name']	= $this->table_name;
		$row['rec_id']		= $this->rec_id;
		return $row;
		}

	public function form(&$o_modal)
		{
		global $_USER;
		$result = NULL;
		if (is_array($this->wizard))
			{
			$o_form = new itForm2([
				'action'	=> $this->action,
				'method'	=> $this->method,
				'reCaptcha'	=> $this->reCaptcha,
			]);
			foreach($this->wizard as $key=>$row)
				{
				$this->appendObjectFormField($o_form, $this->objectFormRow($row));
				}

			$o_form->add_data([
				'table_name'	=> $this->table_name,
				'rec_id'	=> $this->rec_id,
				$this->cat_field=> ready_val(isset($this->data[$this->cat_field]) ? $this->data[$this->cat_field] : NULL),
				'user_id'	=> (is_object($_USER) AND method_exists($_USER, 'id')) ? $_USER->id() : NULL,
				]);
			$o_form->add_hidden('op', $this->op);
			$o_form->add_button(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], 'blue' );
			$o_form->add_button(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );
			$o_form->compile();
			$result = $o_form->code();
			unset($o_form);
			}
		return $result;
		}

	public function table()
		{
		global $wiz_types;
		if (is_array($this->wizard))
			{
			$rows = [];
			foreach ($this->wizard as $wiz_key=>$wiz_row)
				{
				$rows[] =
					TAB."<div class='row'>".
					TAB."<div class='field p5'>".get_field_by_lang(isset($wiz_row['label']) ? $wiz_row['label'] : NULL, CMS_LANG, '')."</div>".
					TAB."<div class='field p5'>".(isset($wiz_row['text']) ? $wiz_row['text'] : '')."</div>".
					TAB."</div>";
				}
			return	TAB."<div class='list'>".
				implode('', $rows).
				TAB."</div>";
			}
		}

	static function _form_update($options=NULL)
		{
		$options = is_array($options) ? $options : [];
		$options['category_table'] 	= ready_val(isset($options['category_table']) ? $options['category_table'] : NULL, DEFAULT_CATEGORY_TABLE);
		$options['table_name']		= ready_val(isset($options['table_name']) ? $options['table_name'] : NULL, DEFAULT_OBJECT_TABLE);
		$options['wiz_field']		= ready_val(isset($options['wiz_field']) ? $options['wiz_field'] : NULL, DEFAULT_WIZARD_FIELD);

		$cat_field = ready_val(isset($options['cat_field']) ? $options['cat_field'] : NULL, DEFAULT_CAT_FIELD);

		if (!isset($options[$cat_field]))
			{
			add_error_message('ERROR_OPTIONS_OBJECT');
			return;
			}

		$o_object = new itObject(['table_name' => $options['table_name'], 'rec_id' => $options[$cat_field]]);
		if (!is_array($o_object->wizard))
			{
			unset($o_object);
			return;
			}

		foreach($o_object->wizard as $wiz_key => $wiz_row)
			{
			if (isset($options[$wiz_key]))
				{
				$o_object->data[$o_object->wiz_values][$wiz_key] = $options[$wiz_key];
				}
			}
		$o_object->store();
		unset($o_object);
		}

	static function _count($category_id=NULL, $table_name=DEFAULT_OBJECT_TABLE, $db_prefix=DB_PREFIX)
		{
		$request = itMySQL::_request("SELECT COUNT(*) as count FROM {$db_prefix}{$table_name} WHERE `category_id`='{$category_id}'");
		return (is_array($request) AND isset($request[0]['count'])) ? $request[0]['count'] : 0;
		}

	static function events($url='/', $path=UPLOADS_ROOT)
		{
		return object_events($url, $path);
		}

	}
?>
