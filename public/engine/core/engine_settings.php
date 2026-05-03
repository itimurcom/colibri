<?php 
function settings_request_value($key)
	{
	return isset($_REQUEST[$key]) ? ready_val($_REQUEST[$key]) : NULL;
	}

function get_settings_form($class='', $data=[], $form_options=[])
	{
	$o_form = new itForm2(array_merge(['class' => $class], $form_options));
	$o_form->add_data(array_merge([
		'table_name'	=> DEFAULT_SETTING_TABLE,
		'op'		=> 'settings',
		], $data));
	return $o_form;
	}

function add_settings_input($o_form, $label, $name, $value=NULL, $required=false)
	{
	$o_form->add_input([
		'label'		=> $label,
		'type'		=> 'input',
		'name'		=> $name,
		'value'		=> is_null($value) ? itSettings::get($name) : $value,
		'compact'	=> true,
		'required'	=> $required,
		'more'		=> false,
		]);
	}

function settings_panel_code($o_form, $class='calculator')
	{
	$o_form->add_button([
		'title' => 'Сохранить',
		'type'	=> 'submit',
		]);
	$o_form->compile();
	$result = TAB."<div class='{$class}'>".$o_form->_view().TAB."</div>";
	unset($o_form);
	return $result;
	}

// настройки сайта
function get_settings_panel()
	{
	$o_form = get_settings_form();
	add_settings_input($o_form, 'Скидка на все изделия <small>(% или 0)</small>', 'DISCOUNT');
	add_settings_input($o_form, 'Курс USD в Евро', 'EUR');
	add_settings_input($o_form, 'Курс USD в Гривне', 'UAH');
	add_settings_input($o_form, 'Стандартный налог, %', 'TAX');
	return settings_panel_code($o_form);
	}

// настройки потовых сообщений
function get_smtp_settings_panel()
	{
	$o_form = get_settings_form();
	add_settings_input($o_form, 'Адрес администратора', 'SITE_ADMIN_EMAIL');
	add_settings_input($o_form, 'Ящик отправки SMTP', 'SITE_SMTP_USER');
	add_settings_input($o_form, 'Пароль входа SMTP', 'SITE_SMTP_PASSWORD');
	return settings_panel_code($o_form, 'calculator big');
	}

// настройки сайта
function get_password_panel()
	{
	$o_form = get_settings_form('', [
		'table_name'	=> DEFAULT_USER_TABLE,
		'rec_id'	=> 1,
		'form_id'	=> 'adminpass',
		'op'		=> 'password',
		], [
		'form_id'	=> 'adminpass',
		'reCaptcha'	=> false,
		]);
	add_settings_input($o_form, 'введите пароль', 'new_password', settings_request_value('new_password'), true);
	add_settings_input($o_form, 'повторите пароль', 'new_password2', settings_request_value('new_password2'), true);
	return settings_panel_code($o_form, 'calculator big');
	}

function get_lang_panel()
	{
	global $lang_cat;
	$rows = [];

	foreach($lang_cat as $l_key=>$l_row)
		{
		$enabled = $l_row['allowed'] ? 'bg_blue' : 'bg_gray';
		$rows[] = ($l_key == DEFAULT_LANG)
			? "<a href='#/' class='itButton bg_gray' >{$l_row['name_orig']}</a>"
			: "<a href='#/' onclick='onoff_land(this);' rel='{$l_key}' class='itButton {$enabled}'>{$l_row['name_orig']}</a>";
		}
	return TAB."<div class='modal_row'><div class='buttons_div'>".
		implode($rows).
	TAB."</div></div>";
	}
?>