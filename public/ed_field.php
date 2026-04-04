<?php
include ("engine/kernel.php");
define('DEBUG_ON', 0);
$path = UPLOADS_ROOT;

// распакуем данные - новый вид работы
$orig_REQUEST = $_REQUEST;
$data = itEditor::_redata();

if (isset($_SERVER['HTTP_REFERER']))
	{
	$url = $_SERVER['HTTP_REFERER'];
	} else $url = '/';

if (DEBUG_ON==1)
	{
//	write_log(NULL,'--request.log'); 
	write_log("request:\n\n".print_r($_REQUEST,true), '--request.log');
	write_log("FILES:\n\n".print_r($_FILES,true), '--request.log');
	write_log("refered:\n\n".$url, '--request.log');
	}

		// обработка без логина

if (isset($_REQUEST['op']))
	{
	// попробуем обработать события загрузки изображений
	if (itUpGal::events($url, $path)) return;
	// попробуем обработать события редактора	
	if (itEditor::events($url, $path)) return;

	switch ($_REQUEST['op'])
		{
		case 'wish' : {
			wish($data);
			return print json_encode(['result' => 1, 'value' => print_r($_SESSION['wishlist'], 1)], JSON_ALLOWED);
			break;
			}
		case 'wishlist' : {
			return print json_encode(['result' => 1, 'value' => wishlist(true) ], JSON_ALLOWED);
			break;
			}

		case 'clearwishlist' : {
			clear_wishlist();
			return print json_encode(['result' => 1], JSON_ALLOWED);
			break;
			}
			
		case 'ajaxpin' : {
			$form = customer_ajaxpin_event($pinned);
			
			if ($pinned)
				{
// 				$form = "<script>alert('{$_REQUEST['pin']}');</script>";
				}
			$code = "$('#ajaxpin').replaceWith($(obj['form']));";
			return print json_encode(['result' => 1, 'show'=> true, 'type' => 'ajax', 'value' => $code, 'form' => $form], JSON_ALLOWED);

			}
		case 'ajaxenter' : {
			$email = ready_val($_REQUEST['logemail']);
			$form = customer_ajaxlogin_event($login);
			$code = "$('#ajaxlogin').replaceWith($(obj['form']));";
						
			if (!isset($_REQUEST['reload']) AND $login AND is_array($customer = customer_by_email($email)))
				{
				create_pin($customer);
				$form = customer_ajaxpin_event($pin);
				} else {
					$form .=  minify_js("<script>
				$(function (){
					var element = 'cus_enter-logemail'; 
					$(\"<div id ='error-\" + element + \"' class='modal_row error_msg f2_row focus'>".get_const('NOT_REGISTERED')."</div>\").insertBefore('#container-' + element);
					$('#container-' + element).addClass('focus');
					$('#error-' + element).ScrollTo({duration:800, offsetTop:64, callback:function(){}});
					});
				</script>");
					}
			return print json_encode(['result' => 1, 'show'=> true, 'type' => 'ajax', 'value' => $code, 'form' => $form], JSON_ALLOWED);
			break;
			}

		case 'itemsort'	: {
			$_SESSION['filter']['sort'] = $_REQUEST['sort'];
			if (ready_val($_REQUEST['min']))
				{
				$_SESSION['filter']['min'] = $_REQUEST['min'];	
				} else $_SESSION['filter']['min'] = NULL;	
			if (ready_val($_REQUEST['max']))
				{
				$_SESSION['filter']['max'] = $_REQUEST['max'];	
				} else $_SESSION['filter']['max'] = NULL;	
			return print json_encode(['result' => 1], JSON_ALLOWED);
			break;
			}
		
		case 'clearlastseen' : {
			if (isset($_SESSION[SESSION_PREFIX.LASTSEEN_ARR])) unset($_SESSION[SESSION_PREFIX.LASTSEEN_ARR]);
			return print json_encode(['result' => 1, 'value' => NULL], JSON_ALLOWED);
			break;
			}


		case 'filter' :
			{
			set_color_filter($_REQUEST['value']);
			return print json_encode(['result' => 1, 'url' => '/'.CMS_LANG.'/items/']);
			break;
			}		

		case 'openclose' : {
			$data = unserialize(simple_decrypt($_REQUEST['data']));
			itSettings::set($data['set'], $_REQUEST['value'], $data['user_id']);
			return print json_encode(['result' => 1, 'value' => NULL], JSON_ALLOWED);
			break;
			}
			
		case 'item_calc' : {
			$rate_sym = "$";
			$rate_val = 1;
			$quantity	= 1;
			
			$form = new itForm2([
				'table_name' 	=> $data['form_name'],
				'rec_id'	=> $data['form_id'],
				]);


			$result = $data['price'];
			foreach ($form->fields_xml as $row)
				{
				if (isset($row['name']))
				switch ($row['name'])
					{
					case 'quantity' : {
						$quantity = isset($_REQUEST[$row['name']]) ? $_REQUEST[$row['name']] : 1;
						break;
						}
					case 'rate' : {
						$rates = [
							'USD'	=> '$',
							'EUR'	=> '€',
							'UAH'	=> 'грн',
							'RUR'	=> 'руб',
							];
						
						if (isset($_REQUEST[$row['name']]))
							{
							$rate_val = isset($_SETTINGS[$_REQUEST[$row['name']]]['value']) ? $_SETTINGS[$_REQUEST[$row['name']]]['value'] : 1;
							$rate_sym = $rates[$_REQUEST[$row['name']]];							
							}
						break;
						}
					default : {
						if (isset($_REQUEST[$row['name']]))
							{
							$multi = isset($_REQUEST[$row['name']."-multi"]) ? $_REQUEST[$row['name']."-multi"] : 1;
							$result += doubleval($_REQUEST[$row['name']])*$multi;
							}
						break;
						}
					}
				}

			// умножим на количество
			$result *= $quantity;


			// определим курс и установим единицы
			$result_str = round($result*$rate_val, 2)." ".$rate_sym.
				( ($rate_sym!="$" ) ? "&nbsp;<small class=\"green\">( {$result} $ )</small>" : NULL);

			unset($form);
			return print json_encode(['result' => 1, 'show'=>false, 'type'=>'ajax', 'value' => "$('#calculator-result-{$data['form_id']}').html('{$result_str}');"], JSON_ALLOWED);
			break;
			}
						

		case '_lang' : {
			toggleLanguageAllowed($_REQUEST['rel']);
			return print json_encode(['result' => 1, 'value' => $_REQUEST['rel']], JSON_ALLOWED);
			break;
			}
		}
	}


// для пользователей
if ($_USER->is_logged('ANY'))
	{
	if (isset($_REQUEST['op']))
	switch ($_REQUEST['op'])
		{
		// простые формы, требующие редирект
		case 'user_edit' : {
// 			print_r($_REQUEST); die;
			update_customer();
			cms_redirect_page("$url");			
			break;
			}
		case 'password' : {
			if ($_REQUEST['new_password']=='')
				{
				$_SESSION['focus']['element'] = "{$data['form_id']}-new_password";
				$_SESSION['focus']['color'] = 'red';
				add_error_message(get_const('ERROR_PASSWORD_EMPTY'));
				} else
			if ($_REQUEST['new_password2']=='')
				{
				$_SESSION['focus']['element'] = "{$data['form_id']}-new_password2";
				$_SESSION['focus']['color'] = 'red';
				add_error_message(get_const('ERROR_PASSWORD2_EMPTY'));
				} else
			if ($_REQUEST['new_password']!=$_REQUEST['new_password2'])
				{
				add_error_message(get_const('ERROR_PASSWORD_EQUAL'));
				} else	{
					itMySQL::_update_value_db($_REQUEST['table_name'], $_REQUEST['rec_id'], sqlPassword($_REQUEST['new_password']), 'password');
					add_service_message(get_const('MESSAGE_PASSWORD_DONE'));
					}
			cms_redirect_page("$url");
			break;
			}

		case 'add_user' : {
			if (itUser::get_user_id_from_login($_REQUEST['value'], $_REQUEST['table_name'])==NULL)
				{
				$rec_id = itUser::register_user($_REQUEST['value'], '', 'new_user_script');
				add_service_message(str_replace('[VALUE]', $_REQUEST['value'], get_const('USER_ADD_DONE')));
				$url = "/{$_REQUEST['lang']}/user/{$rec_id}/";
				} else	{
					add_error_message(str_replace('[VALUE]', $_REQUEST['value'], get_const('USER_LOGIN_BUSY')));
					}
			cms_redirect_page("$url");
			break;
			}

		case 'user_name' : 
			{
			itMySQL::_update_value_db($_REQUEST['table_name'], $_REQUEST['rec_id'], html2txt($_REQUEST['value']), 'name');
			cms_redirect_page("$url");
			break;
			}

		case 'user_sex' : {
			itMySQL::_update_value_db($_REQUEST['table_name'], $_REQUEST['rec_id'], $_REQUEST['sex'], 'sex');
			cms_redirect_page("$url");
			break;
			}

		case 'user_description' : {
			itMySQL::_update_value_db($_REQUEST['table_name'], $_REQUEST['rec_id'], strip_tags(br2nl($_REQUEST['description'], true), ALLOWED_TAGS), 'description');
			cms_redirect_page("$url");
			break;
			}

		case 'user_bdate' : {
			itMySQL::_update_value_db($_REQUEST['table_name'], $_REQUEST['rec_id'], $_REQUEST['b_date'], 'b_date');
			cms_redirect_page("$url");
			break;
			}

		case 'user_phone' : 
			{
			itMySQL::_update_value_db($_REQUEST['table_name'], $_REQUEST['rec_id'], html2txt($_REQUEST['value']), 'phone');
			cms_redirect_page("$url");
			break;
			}

		case 'user_email' : 
			{
			itMySQL::_update_value_db($_REQUEST['table_name'], $_REQUEST['rec_id'], html2txt($_REQUEST['value']), 'email');
			cms_redirect_page("$url");
			break;
			}

		case 'tab' : {
			$data = unserialize(simple_decrypt($_REQUEST['data']));			
			itSettings::set($data['set'], $data['value'], $data['user_id']);
			return print json_encode(['result' => 1, 'value' => NULL], JSON_ALLOWED);
			break;
			}
		}
	}

// обработка для администратора
if ($_USER->is_logged())
	{
	if (isset($_REQUEST['op']))
	{
	// попробуем обработать события редактируемых форм (2.1)
	if (itForm2::events($url, $path)) return;

	switch ($_REQUEST['op'])
		{
		case 'spam' :	{
			if ($mail = itMySQL::_get_rec_from_db('mails', $data['mail_id']))
				{
				itMySQL::_request("UPDATE `".DB_PREFIX."mails` SET `status`='SPAM' WHERE `reply` = '{$mail['reply']}'");
				}
			cms_redirect_page("$url");
			break;
			}
		case 'spam_x' :	{
			if ($mail = itMySQL::_get_rec_from_db('mails', $data['mail_id']))
				{
				itMySQL::_request("UPDATE `".DB_PREFIX."mails` SET `status`='NOSPAM' WHERE `reply` = '{$mail['reply']}'");
				}
			cms_redirect_page("$url");
			break;
			}

		case 'mail_x' :	{
			if ($mail = itMySQL::_get_rec_from_db('mails', $data['mail_id']))
				{
				itMySQL::_request("UPDATE `".DB_PREFIX."mails` SET `status`='DELETED' WHERE `id` = '{$mail['id']}'");
				}
			cms_redirect_page("$url");
			break;
			}

		case 'mail_not_x' :	{
			if ($mail = itMySQL::_get_rec_from_db('mails', $data['mail_id']))
				{
				itMySQL::_request("UPDATE `".DB_PREFIX."mails` SET `status`='NOSPAM' WHERE `id` = '{$mail['id']}'");
				}
			cms_redirect_page("$url");
			break;
			}

		case 'banner' : {
			foreach ($_FILES[DEFAULT_FILES_NAME]['name'] as $key => $name)
				{
				$clear_name = clear_file_name($name);
				$clear_name = check_uploaded_file($clear_name, $_FILES[DEFAULT_FILES_NAME]["tmp_name"][$key]); 
	                        $count=0;
				if(move_uploaded_file($_FILES[DEFAULT_FILES_NAME]["tmp_name"][$key], UPLOADS_ROOT.$clear_name))
					{ 	
					$row = itMySQL::_get_rec_from_db($data['table_name'], $data['rec_id']);
					if (!is_array($row['images_xml'])) $row['images_xml']=NULL;
					$row['images_xml'][CMS_LANG] = $clear_name;
					itMySQL::_update_value_db($data['table_name'], $data['rec_id'], $row['images_xml'], 'images_xml');
					}
				}
			return print json_encode(['result' => 1, 'value' => $url]);				
			break;
			}
		case 'bannerx' : {
			if ($row = itMySQL::_get_rec_from_db($data['table_name'], $data['rec_id']))
			if (isset($row['images_xml'][CMS_LANG])) unset ($row['images_xml'][CMS_LANG]);
			itMySQL::_update_value_db($data['table_name'], $data['rec_id'], $row['images_xml'], 'images_xml');
			cms_redirect_page("$url");			
			break;
			}
		case 'add_item' : {
			$values_arr = [
				'title_xml'		=> [
					$_REQUEST['lang'] => $_REQUEST['value']
					],
				'category_id'	=> $_REQUEST['category_id'],
				'is_replicant'	=> ready_val($_REQUEST['is_replicant']) == 'on',
				'is_shop'		=> ready_val($_REQUEST['is_shop']) == 'on',
				'serie'			=> $_REQUEST['serie'],
				'version'		=> $_REQUEST['version'],
				'datetime'		=> mysql_now(),
				'url_xml'	=> NULL,
				];
			$rec_id = itMySQL::_insert_rec($_REQUEST['table_name'], $values_arr);
			cms_redirect_page("/".CMS_LANG."/items/{$rec_id}/");
			break;
			}

		case 'item_articul' : {
			$values_arr = [
				'category_id'	=> $_REQUEST['category_id'],
				'serie'			=> $_REQUEST['serie'],
				'version'		=> $_REQUEST['version'],
				];
			itMySQL::_update_db_rec($data['table_name'], $data['rec_id'], $values_arr);
			cms_redirect_page("/".CMS_LANG."/items/{$data['rec_id']}/");
			break;
			}

		case 'item_price' : {
			itMySQL::_update_value_db($data['table_name'], $data['rec_id'], str_replace(",", ".",$_REQUEST['value']), 'price');
			cms_redirect_page("$url");
			break;
			}

		
		// AJAX
		case 'item_color' :
			{
			set_item_color($data['id'],$data['value']);
			return print json_encode(['result' => 1, 'value' => $url]);
			break;
			}
				
		case 'lang' : 	{
			itMySQL::_update_value_db($_REQUEST['table_name'], $_REQUEST['rec_id'], $_REQUEST['lang_short'], 'lang');
			cms_redirect_page("$url");
			break;
			}
		case 'settings' : {
			foreach(explode(",", "DISCOUNT,EUR,UAH,RUR,TAX,SITE_ADMIN_EMAIL,SITE_SMTP_USER,SITE_SMTP_PASSWORD,FB_PAGE,IG_PAGE,TW_PAGE,VK_PAGE,OK_PAGE") as $VAR)
				if (isset($_REQUEST[$VAR]))
					itSettings::set($VAR, str_replace(",",".",$_REQUEST[$VAR]));
			cms_redirect_page("$url");
			break;
			}			

		}
			
	}
	}
	
// обработка для модератора
if ($_USER->is_logged($_RIGHTS['EDIT']))
	{
	if (isset($_REQUEST['op']))
	{
	// сначала попробуем обработать галлерею поля
	if (itImages::events($url, $path)) return;
	// попробуем обработать события редактора
	if (itEditor::events($url, $path)) return;
	// попробуем обработать события категорий
	if (itCategory::events($url, $path)) return;
	// попробуем обработать события визарда	
	if (itWizard::events($url, $path)) return;
	// попробуем обработать события объекта
	if (itObject::events($url, $path)) return;
	// попробуем обработать события редактируемых форм (2.1)
	if (itForm2::events($url, $path)) return;
	

	switch ($_REQUEST['op'])
		{
		case 'is_new' :
		case 'is_econom' :		
		case 'is_shop' :
		case 'is_replicant' : {
			if ($row = itMySQL::_get_rec_from_db($data['table_name'], $data['rec_id']))
				{
				itMySQL::_update_value_db($data['table_name'], $data['rec_id'], !$row[$_REQUEST['op']], $_REQUEST['op']);
				}
			cms_redirect_page("$url");
			break;
			}
		case 'item_x' : 
			{
			itMySQL::_update_value_db($data['table_name'], $data['rec_id'], 'DELETED', 'status');
			cms_redirect_page("/".CMS_LANG."/items/");	
			}
			
		case 'add_slider' : {
			foreach ($_FILES[DEFAULT_FILES_NAME]['name'] as $key => $name)
				{
				$clear_name = clear_file_name($name);
				$clear_name = check_uploaded_file($clear_name, $_FILES[DEFAULT_FILES_NAME]["tmp_name"][$key]); 
	                        $count=0;
				if(move_uploaded_file($_FILES[DEFAULT_FILES_NAME]["tmp_name"][$key], UPLOADS_ROOT.$clear_name))
					{ 	
					itSlider::add($clear_name);
					}
				}
			return print json_encode(['result' => 1, 'value' => $url]);
			break;
			}

		case 'slider_x' : {
			$db = new itMySQL();
			$db->remove_rec_from_db($_REQUEST['table_name'], $_REQUEST['rec_id']);
			unset($db);
			cms_redirect_page("$url");
			break;
			}

		case 'slider_title' : {
			itSlider::set_title($_REQUEST['table_name'], $_REQUEST['rec_id'], $_REQUEST['value'], $_REQUEST['lang']);
			$split = explode('?', $url);
			$url = "{$split[0]}?slide={$_REQUEST['rec_id']}";
			cms_redirect_page("$url");
			break;
			}

		case 'slider_href' : {
			itSlider::set_href($_REQUEST['table_name'], $_REQUEST['rec_id'], $_REQUEST['value'], $_REQUEST['lang']);
			$split = explode('?', $url);
			$url = "{$split[0]}?slide={$_REQUEST['rec_id']}";
			cms_redirect_page("$url");
			break;
			}

		case 'add_content' : {
			$db = new itMySQL();
			$rec_id = $db->insert_rec($_REQUEST['table_name']);
			$db->update_value_db($_REQUEST['table_name'], $rec_id, [$_REQUEST['lang'] => $_REQUEST['value']], 'title_xml');
			$db->update_value_db($_REQUEST['table_name'], $rec_id, $_REQUEST['category_id'], 'category_id');
			unset($db);
			$url = "/".CMS_LANG."/material/{$rec_id}/"; die;
			cms_redirect_page("$url");
			break;
			}

			
		case 'datetime' : {
			$db = new itMySQL();
			$db->update_value_db($_REQUEST['table_name'], $_REQUEST['rec_id'], $_REQUEST['datetime'], 'datetime');
			unset($db);
			cms_redirect_page("$url");
			break;
			}

		case 'killall' : {
			$db = new itMySQL();
			$db->request("DELETE from {$db->db_prefix}{$_REQUEST['table_name']} where `status` = '{$_REQUEST['status']}'");
			//сбросим счетчик на последний элемент + 1
			$db->reset_autoinc($_REQUEST['table_name']);
			unset($db);
			cms_redirect_page("$url");
			break;
			}
			
		case 'background' : {
			$dest = "themes/".CMS_THEME."/images/bg_{$data['controller']}.jpg";
			if (!move_uploaded_file($_FILES[DEFAULT_FILES_NAME]['tmp_name'][0], $dest))
				{
				add_error_message("error loading background {$_FILES[DEFAULT_FILES_NAME]['name']} ...");
				} else 	{
				magic_resizer($dest, $dest,
					$pic_tech['BACKGROUND']['sx'],
					$pic_tech['BACKGROUND']['sy'],
					$pic_tech['BACKGROUND']['crop'],
					$pic_tech['BACKGROUND']['logo'],
					$pic_tech['BACKGROUND']['quality'],
					ready_val($pic_tech['BACKGROUND']['place']));
					}
			return print json_encode(['result' => 1, 'url' => $url], JSON_ALLOWED);
			break;
			}
		}
	}
	}

if (DEBUG_ON!=1)
	{
	cms_redirect_page("$url");	
	} else	{
		echo "<font color='lightgray'>_REQUEST:".print_rr($_REQUEST).
		"_FILES:\n\n".print_rr($_FILES).
		"_REFERRER:".$url."</font>";
		}

//return print json_encode(['result' => 0]);
?>