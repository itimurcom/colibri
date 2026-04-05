<?
$_CONTENT['admin'] = get_admin_button_set();
$data = itEditor::_redata();
global $_SETTINGS;

if ($_USER->is_logged(['ANY']))
	{
	cms_redirect_page("/".CMS_LANG.'/cabinet/');
	}


$_CONTENT['widgets'] = get_widgets_set();
$_CONTENT['widgets-cell'] = get_widgets_set();
	
$_CONTENT['content'] = customer_ajaxlogin_event($login);

// opengraph
$plug_og['subtitle'] 	= get_const('CMS_NAME');
$plug_og['title'] 	= get_const('NODE_AJAX');
// echo print_rr(get_item_from_articul('S_WS_001_1'));
/*
$_CONTENT['content'] = 
"<style>.l-load{}</style>".
TAB."<div class='l-load' data-loader='youtube' data-src='https://www.youtube.com/watch?v=tYC5_iJjEPc'></div>".
"<script>

$(document).ready(function(){
	$('.l-load').lazy({
	afterLoad: function(element){
		alert(1);
		$(element).removeClass('l-load');
		},

	delay: 500,
//	effect: 'fadeIn',
  //      effectTime: 600,
//	threshold: 0,
	});
    });</script>";
*/
//$_CONTENT['menu'] = get_menus_block();
/*
global $_MARKUP;
global $_LDJSON, $_RDFA, $_SCHEMA;

$_MARKUP = [
	'name'		=> 'test',
	'description'	=> 'simpletest escripto',
	'image'	=> [
		0	=> 'https://www.atelier-colibri.com/img/itemshot_r_0348_1aa.jpg',
		1	=> 'https://www.atelier-colibri.com/img/itemshot_r_0348_1b.jpg',
		2	=> 'https://www.atelier-colibri.com/img/itemshot_r_0348_1bb.jpg',
		],
	'price'		=> '123.4',
	'currency'	=> 'UAH',
	'sku'		=> 'R_2098_01',
	'url'		=> 'https://atelier-colibri.iti.com/itmes/123-test',
];

$o_markup =  new itMarkUp();
unset($o_markup);
*/

/*
$o_form = new itForm2([
	'rec_id'	=> 7777,
	'reCaptcha'	=> get_const('USE_CAPTCHA', true),
	'class'		=> 'yellow',
	'action'	=> "/".CMS_LANG.'/order/',
//	'debug'		=> true,
	]);

$o_form->hiddens_xml = NULL;
$o_form->add_data([
	'op'	=> 'order',
	]);

$o_form->buttons_xml = NULL;	
*/
//$o_form->add_button(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], 'blue big' );	
//$o_form->add_button(get_const('BUTTON_CLEAR'), 'a', ['ajax'=>"f2_reset('".$o_form->form_id()."');"], 'green big' );	

//if ($_USER->is_logged()) $o_form->store();
	

/*
$_CONTENT['content'] =  
TAB."<center  style='font-size:24px;'>".
TAB."<a class='blue' href='/ru/test/'>РУССКАЯ</a>&nbsp;&nbsp;&nbsp;&nbsp;".
TAB."<a class='green' href='/en/test/'>АНГЛИЙСКАЯ</a>".
TAB."</center><hr>".
$o_form->container();
*/


/*
$options = [
	'prepared'	=> 'this is test code',
	'subject'	=> 'test',
	];


itMailTemplate::_code($options);

$_CONTENT['content'] = 
	TAB."<div class=''>".
	$options['result'].
	TAB."</div>";
*/
/*

$mail = new PHPMailer;
$mail->SMTPDebug = 1;
$mail->isSMTP(); 
$mail->Host = "mail.{$_SERVER['SERVER_NAME']}";
$mail->SMTPAuth = true; 
$mail->Username = 'robot@atelie-colibri.com'; // Ваш логин в Яндексе. Именно логин, без @yandex.ru
$mail->Password = 'robotcolibri'; // Ваш пароль
//$mail->SMTPSecure = 'ssl'; 
$mail->Port = 25;
$mail->setFrom('robot@atelie-colibri.com', CMS_NAME); // Ваш Email
$mail->addAddress("itimurweb@gmail.com"); // Email получателя

// Письмо
$mail->isHTML(true); 
$mail->Subject = "TEST";
$mail->Body = "<h1> test message</h1>"; // Текст письма		
		
//		$error_arr = error_get_last();
		if (!$mail->send())
			{
			echo $mail->ErrorInfo;
			die;
			}

*/


/*
	НЕ УДАЛЯТЬ ТУТ ПОИСК ПО ВСЕМ ТАБЛИЦАМ!!!!
$files_arr = glob('BAK/*.jpg');

foreach ($files_arr as $row)
	{
	$finename = str_replace('BAK/', '', $row);
	
	$f_serie = substr ( $finename, 2, 4);
	$f_category_id = 1;
	$f_version = substr ( $finename, 7, 1);
	
	$index = substr ( $finename, 0, 8);
	
	$items[$index]['gallery_xml'][] = ['img' => "/var/www/admin/data/www/atelier-colibri.com/uploads/{$finename}"];
	$items[$index]['title_xml'] = '{"en":""}';
	$items[$index]['serie'] = $f_serie;
	$items[$index]['version'] = $f_version;
	$items[$index]['is_replicant'] =  (substr ( $finename, 0, 1) == "r") ? 1 : 0;	
//echo "<br/>";
	}
	
foreach($items as $key=>$item)
	{
//	itMySQL::_insert_rec('items',$item);
	}
	
$_CONTENT['content'] = print_r($items,1);


$_CONTENT['menu'] = get_menus_block();
$_CONTENT['widgets'] = get_widgets_set();

$o_form = new itForm2([
	'name'	=> 'test',
	]);

$o_editor = new itEditor();
$o_editor->compile();

$o_form->add_input([
	'name'		=> 'value',
	'label'		=> 'test',
	'compact'	=> true,
	'editor'	=> $o_editor->code(),
	]);
$o_form->compile();
$_CONTENT['content'] = $o_form->code();
unset($o_form);	


$o_editor = new itEditor([
	'table_name' 	=> 'contents',
	'rec_id'	=> '2',
	'field_id'	=> 0,
	]);
//$o_editor->compile();
$_CONTENT['content'] = $o_editor->container();
unset($o_editor); */
?>