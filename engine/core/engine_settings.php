<?
//..............................................................................
// настройки сайта
//..............................................................................
function get_settings_panel()
	{
	$o_form = new itForm2([
		'class'	=> '',
		]);
		
	$o_form->add_data([
		'table_name'	=> DEFAULT_SETTING_TABLE,
		'op'		=> 'settings',
		]);

	$o_form->add_input([
		'label'		=> 'Скидка на все изделия <small>(% или 0)</small>',
		'name'		=> 'DISCOUNT',
		'value'		=> itSettings::get('DISCOUNT'),
		'compact'	=> true,
		'more'		=> false,
		]);

	$o_form->add_input([
		'label'		=> 'Курс USD в Евро',
		'name'		=> 'EUR',
		'value'		=> itSettings::get('EUR'),
		'compact'	=> true,
		'more'		=> false,
		]);

	$o_form->add_input([
		'label'		=> 'Курс USD в Гривне',
		'name'		=> 'UAH',
		'value'		=> itSettings::get('UAH'),
		'compact'	=> true,
		'more'		=> false,
		]);

	$o_form->add_input([
		'label'		=> 'Курс USD в Рублях',
		'name'		=> 'RUR',
		'value'		=> itSettings::get('RUR'),
		'compact'	=> true,
		'more'		=> false,
		]);
		
	$o_form->add_input([
		'label'		=> 'Стандартный налог, %',
		'name'		=> 'TAX',
		'value'		=> itSettings::get('TAX'),
		'compact'	=> true,
		'more'		=> false,
		]);

	$o_form->add_button([
		'title' => 'Сохранить',
		'type'	=> 'submit',
		]);

	$o_form->compile();
	$result =
		TAB."<div class='calculator'>".
		$o_form->_view().
		TAB."</div>";		
	unset($o_form);
	return $result;
	}

//..............................................................................
// настройки потовых сообщений
//..............................................................................
function get_smtp_settings_panel()
	{
	$o_form = new itForm2([
		'class'	=> '',
		]);
		
	$o_form->add_data([
		'table_name'	=> DEFAULT_SETTING_TABLE,
		'op'		=> 'settings',
		]);

	$o_form->add_input([
		'label'		=> 'Адрес администратора',
		'name'		=> 'SITE_ADMIN_EMAIL',
		'value'		=> itSettings::get('SITE_ADMIN_EMAIL'),
		'compact'	=> true,
		'more'		=> false,
		]);		


	$o_form->add_input([
		'label'		=> 'Ящик отправки SMTP',
		'name'		=> 'SITE_SMTP_USER',
		'value'		=> itSettings::get('SITE_SMTP_USER'),
		'compact'	=> true,
		'more'		=> false,
		]);		

	$o_form->add_input([
		'label'		=> 'Пароль входа SMTP',
		'name'		=> 'SITE_SMTP_PASSWORD',
		'value'		=> itSettings::get('SITE_SMTP_PASSWORD'),
		'compact'	=> true,
		'more'		=> false,
		]);		

	
	$o_form->add_button([
		'title' => 'Сохранить',
		'type'	=> 'submit',
		]);

	$o_form->compile();
	$result =
		TAB."<div class='calculator big'>".
		$o_form->_view().
		TAB."</div>";		
	unset($o_form);
	return $result;
	}

//..............................................................................
// настройки сайта
//..............................................................................
function get_password_panel()
	{
	$o_form = new itForm2([
		'form_id'	=> 'adminpass',
		'reCaptcha'	=> false,
		'class'		=> '',
		]);
		
	$o_form->add_data([
		'table_name'	=> DEFAULT_USER_TABLE,
		'rec_id'	=> 1,
		'form_id'	=> 'adminpass',
		'op'		=> 'password',
		]);

	$o_form->add_input([
		'label'		=> 'введите пароль',
		'type'		=> 'input',
		'name'		=> 'new_password',
		'compact'	=> true,
		'required'	=> true,
		'more'		=> false,
		'value'		=> ready_val($_REQUEST['new_password']),
		]);

	$o_form->add_input([
		'label'		=> 'повторите пароль',
		'type'		=> 'input',
		'name'		=> 'new_password2',
		'compact'	=> true,
		'required'	=> true,
		'more'		=> false,
		'value'		=> ready_val($_REQUEST['new_password2']),
		]);


	$o_form->add_button([
		'title' => 'Сохранить',
		'type'	=> 'submit',
		]);

	$o_form->compile();
	$result =
		TAB."<div class='calculator big'>".
		$o_form->_view().
		TAB."</div>";		
	unset($o_form);
	return $result;
	}

function get_lang_panel() {
	global $lang_cat;
	$rows = NULL;

	foreach($lang_cat as $l_key=>$l_row) {
		$enabled = $l_row['allowed'] ? 'bg_blue' : 'bg_gray';
		$en_text = $l_row['allowed'] ? 'on' : 'off';

		$rows[] = ($l_key == DEFAULT_LANG) ?
			"<a href='#/' class='itButton bg_gray' >{$l_row['name_orig']}</a>" :
			"<a href='#/' onclick='onoff_land(this);' rel='{$l_key}' class='itButton $enabled'>{$l_row['name_orig']}</a>";
		}
	return TAB."<div class='modal_row'><div class='buttons_div'>".
		implode ($rows).
	TAB."</div></div>";
	}
?>