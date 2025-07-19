<?
/* общие для работы функции */



//..............................................................................
// определяет кодировку строки
//..............................................................................
function detect_encoding($string)
	{ 
	foreach (['UTF-8', 'CP1251', 'CP1252'] as $item)
		{
		@$sample = iconv($item, $item, $string);
		if (md5($sample) == md5($string))
			return $item;
  		}
  	return NULL;
	}

//..............................................................................
// первый символ предложения загалвный остальные маленькие (multi ucifirst)
//..............................................................................
if (!function_exists('mb_ucfirst'))
	{
	function mb_ucfirst($str, $encoding = "UTF-8", $lower_str_end = false)
		{
		$first_letter = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding);
		$str_end = "";
		if ($lower_str_end)
			{
			$str_end = mb_strtolower(mb_substr($str, 1, mb_strlen($str, $encoding), $encoding), $encoding);
      			} else	{
      				$str_end = mb_substr($str, 1, mb_strlen($str, $encoding), $encoding);
				}
		$str = $first_letter . $str_end;
		return $str;
   		}
   	}



//..............................................................................
// восстановим переменные запроса из сессии для пары логин:пароль
//..............................................................................
function restore_login_reqest()
	{
	if (isset($_SESSION['user_login']))
		{
		$_REQUEST['user_login'] = $_SESSION['user_login'];
		unset($_SESSION['user_login']);
		}

	if (isset($_SESSION['user_password']))
		{
		$_REQUEST['user_password'] = $_SESSION['user_password'];
		unset($_SESSION['user_password']);
		}
	}

//..............................................................................
// возвращает правильное локальное написание даты для данных полей
//..............................................................................
function get_mysql_time_str($time)
	{
	return strftime("%Y-%m-%d %H:%M:%S",$time);
	}



//..............................................................................
// определения ip адресса пользователя
//..............................................................................
function get_user_ip()
	{
    	if ( getenv ('REMOTE_ADDR')) {$ip_o_fuser = getenv ('REMOTE_ADDR');}
    elseif ( getenv ('HTTP_FORWARDED_FOR')) {$ip_o_fuser = getenv ('HTTP_FORWARDED_FOR');} 
    elseif ( getenv ('HTTP_X_FORWARDED_FOR')) {$ip_o_fuser = getenv ('HTTP_X_FORWARDED_FOR');} 
    elseif ( getenv ('HTTP_X_COMING_FROM')) {$ip_o_fuser = getenv ('HTTP_X_COMING_FROM');} 
    elseif ( getenv ('HTTP_VIA')) {$ip_o_fuser = getenv ('HTTP_VIA');} 
    elseif ( getenv ('HTTP_XROXY_CONNECTION')) {$ip_o_fuser = getenv ('HTTP_XROXY_CONNECTION');} 
    elseif ( getenv ('HTTP_CLIENT_IP')) {$ip_o_fuser = getenv ('HTTP_CLIENT_IP');} 
    else {$ip_o_fuser='unknown';}
    	if (15 < strlen ($ip_o_fuser))
		{
       	 	$ar = explode (', ', $ip_o_fuser);
        	for ($i= sizeof ($ar)-1; $i> 0; $i--)
		{
            	if ($ar[$i]!='' and !preg_match ('/[a-zA-Zа-яА-Я]/', $ar[$i]))
			{
                	$ip_o_fuser = $ar[$i]; 
                	break; 
                	}
            	if ($i== sizeof ($ar)-1)
			{
			$ip_o_fuser = 'unknown';
			}
         	}
        	}
    	if ( preg_match ('/[a-zA-Zа-яА-Я]/', $ip_o_fuser))
		{
		$ip_o_fuser = 'unknown';
		}
    	return $ip_o_fuser;
	}

//..............................................................................
// генерирует новый пароль из допустимых символов
//..............................................................................
function generate_new_password($number=8)
	{
	$arr = array(
		'a','b','c','d','e','f',  
                'g','h','i','j','k','l',  
                'm','n','o','p','r','s',  
                't','u','v','x','y','z',  
                'A','B','C','D','E','F',  
                'G','H','I','J','K','L',  
                'M','N','O','P','R','S',  
                'T','U','V','X','Y','Z',  
                '1','2','3','4','5','6',  
                '7','8','9','0');  
	// Генерируем пароль  
	$pass = "";  
	for($i = 0; $i < $number; $i++)  
		{  
		// Вычисляем случайный индекс массива  
		$index = rand(0, count($arr) - 1);  
		$pass .= $arr[$index];  
		}  
		return $pass;  
	}  

//..............................................................................
// возвращает первое несуществующее имя для загруженной картинки
//..............................................................................
function get_new_picture_filename($filename='', $dir=PICTURE_ROOT) {
	if ($filename=='') {
		// создадим случайное имя для файла
		return  $dirname.time().'.'.'jpg';
		}

	$filename 	= remove_dashed_number($filename);
	$result		= $filename;
	
	$number		= 1;

	while (file_exists($dir.$result)) {
		$number++;
		$result = add_dashed_number($filename,$number);
		}

	return $result;
	}

//..............................................................................
// возвращает имя файла, добавляя скобки и номер
//..............................................................................
function add_dashed_number($str='', $number=-1)
	{
	$extention	= get_file_extension($str);
	$start_place	= strpos($str,'.');
	$name		= substr($str,0,$start_place);
	$result		= $name."(".$number.")".$extention;
	return $result;
	}

//..............................................................................
// возвращает имя файла без скобок и номера для загруженной картинки
//..............................................................................
function remove_dashed_number($str='')
	{
	$extention	= get_file_extension($str);
	$start_place	= strpos($str,'(');
	$finish_place	= strpos($str,')',$start_place);

	if ($finish_place==FALSE)
		{
		return $str;
		} else return substr($str,0,$start_place).$extention;
	}

//..............................................................................
// крутой парсер пути
//..............................................................................
function parse_path($url=NULL)
	{
	if ($url==NULL) 
		{
		$url = $_SERVER['REQUEST_URI'];
		}
	$path = array();
	if ($url!=NULL)
		{
		$request_path = explode('?', $url);

		$path['base'] = rtrim(dirname($_SERVER['SCRIPT_NAME']), '\/');
		$path['call_utf8'] = substr(urldecode($request_path[0]), strlen($path['base']) + 1);
		$path['call'] = utf8_decode($path['call_utf8']);
		if ($path['call'] == basename($_SERVER['PHP_SELF']))
			{
			$path['call'] = '';
			}
		$path['call_parts'] = explode('/', $path['call']);
		foreach ($path['call_parts'] as $key=>$row)
			{
			if ($row==NULL) unset($path['call_parts'][$key]);
			}

		@$path['query_utf8'] = urldecode($request_path[1]);
		@$path['query'] = utf8_decode(urldecode($request_path[1]));
		$vars = explode('&', $path['query']);
		foreach ($vars as $var)
			{
			$t = explode('=', $var);
			@$path['query_vars'][$t[0]] = $t[1];
			}
		}

	return $path;
	}

//..............................................................................
// возвращает массив, в котором все элементы декодированы из JSON в array
//..............................................................................
function decode_json_values(&$array)
	{
	if (!is_array($array)) return;
	if (!count($array)) return;

	foreach ($array as $key=>$row)
		{
		$array[$key] = isJson($row) ? json_decode($row, true, 512, JSON_BIGINT_AS_STRING ) : $row;
		}
	}

//..............................................................................
// возвращает правильное локальное написание даты для данных полей
//..............................................................................
function get_local_date_str($data, $s_month=false, $s_dname=false, $s_year=true)
	{
	//%a
	$time = strtotime($data);

	$year  = ($s_year) ? ' %Y' : '';

	$month = ($s_month) ? '%b' : '%B';

	$weekday =  ($s_dname) ? "%a, " : '';


	if (strftime("%d %b %Y",$time) == strftime("%d %b %Y",strtotime('today'))) 
		{
		return get_const('LOCAL_DATE_TODAY');
		} else
	if (strftime("%d %b %Y",$time) == strftime("%d %b %Y",strtotime('yesterday'))) 
		{
		return get_const('LOCAL_DATE_YESTERDAY');
		} 

	return strftime("{$weekday}%d {$month}{$year}",$time);
	}

//..............................................................................
// возвращает правильное локальное написание даты для данных полей
//..............................................................................
function get_local_datetime_str($data, $s_month=false, $s_dname=false, $s_year=true, $no_midnight=true)
	{
	//%a
	$time = strtotime($data);

	$year  = ($s_year) ? ' %Y' : '';

	$month = ($s_month) ? '%b' : '%B';

	$weekday =  ($s_dname) ? "%a, " : '';
	
	if (( ($time_str=get_time_str($data))==='00:00') AND $no_midnight)
		{
		$time_str = '';
		} else $time_str = "<small class='time'>{$time_str}</small>";


	if (strftime("%d %b %Y",$time) == strftime("%d %b %Y",strtotime('today'))) 
		{
		return get_const('LOCAL_DATE_TODAY')." ".$time_str;
		} else
	if (strftime("%d %b %Y",$time) == strftime("%d %b %Y",strtotime('yesterday'))) 
		{
		return get_const('LOCAL_DATE_YESTERDAY')." ".$time_str;
		} 

	return strftime("{$weekday}%d {$month}{$year} {$time_str}",$time);
	}

//..............................................................................
// возвращает правильное локальное время
//..............................................................................
function get_time_str($data, $sec=false)
	{
	$time = strtotime($data);
	return strftime("%H:%M".(($sec) ? ":%S" : ""), $time);
	}

//..............................................................................
// убирает ненужные символы в начале и в конце строки, включая код перевода
//..............................................................................
function br_trim($string)
	{
	return preg_replace('/^(<br>){0,}|(<br>){0,}$/m', '', $string);	
	}

//..............................................................................
// возвращает возраст исходя из дня рождения
//..............................................................................
function get_age($birthdayDate=NULL)
	{
	if ($birthdayDate==NULL) return get_const('NO_SET');
	$datetime = new DateTime($birthdayDate);
        $interval = $datetime->diff(new DateTime(date("Y-m-d")));
        return $interval->format("%Y");
	}

//..............................................................................
// возвращает разницу между двух дней
//..............................................................................
function dateDiff ($d1=NULL, $d2=NULL)
	{
	$d1 = is_null($d1) ? 'now' : $d1;	
	$d2 = is_null($d2) ? 'now' : $d2;
	return round(abs(strtotime($d1)-strtotime($d2))/86400);
	}

	//..............................................................................
// возвращает правильное написание даты для данных полей
//..............................................................................
function get_mysql_date($time)
	{
	return strftime("%Y-%m-%d",$time);
	}

//..............................................................................
// возвращает правильное написание даты и времени для данных полей
//..............................................................................
function get_mysql_datetime($time)
	{
	return strftime("%Y-%m-%d %H:%M:%S",$time);
	}

//..............................................................................
// возвращает значение зашифрованного пороля для MySQL
//..............................................................................
function sqlPassword($input)
	{
	$pass = strtoupper(sha1(sha1($input, true)));
	$pass = '*' . $pass;
	return $pass;
	}

//..............................................................................
// возвращает значение зашифрованного пороля для MySQL
//..............................................................................
function print_json($res_arr, $stop=true)
	{
	header("Content-Type: application/json", true);
	return print json_encode($res_arr, JSON_ALLOWED);
	if ($stop) exit;
	}

//..............................................................................
// возвращает правильную ссылку с http://
//..............................................................................
function addhttp($url)
	{
	if (!preg_match("~^(?:f|ht)tps?://~i", $url))
		{
		$url = "http://" . $url;
		}
	return $url;
	}
	
//..............................................................................
// возвращает правильную ссылку с http://
//..............................................................................
function striphttp($url)
	{
	return preg_replace( '/^https?:\/\//', '', $url );
	}
	
//..............................................................................
// обратная nl2br
//..............................................................................
function br2nl($str)
	{
	$search = ["\n<br>", "\n<br/>", "\n<br />", "<br>", "<br/>", "<br />"];
	$replace = "\n";
	return str_replace($search, $replace, $str);
	}

//..............................................................................
// возвращает код клика по элементо по его id после загрузки страницы
//..............................................................................
function javascript_click($element)
	{
	return "<script>$(document).ready(function (){ $('#{$element}').click(); });</script>";
	}

//..............................................................................
// перевод кириллицы в транслит
//..............................................................................
function translit($s) {
  $s = (string) $s; // преобразуем в строковое значение
  $s = strip_tags($s); // убираем HTML-теги
  $s = str_replace(array("\n", "\r"), " ", $s); // убираем перевод каретки
  $s = preg_replace("/\s+/", ' ', $s); // удаляем повторяющие пробелы
  $s = trim($s); // убираем пробелы в начале и конце строки
  $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
  $s = strtr($s, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
  $s = preg_replace("/[^0-9a-z-_ ]/i", "", $s); // очищаем строку от недопустимых символов
  $s = str_replace(" ", "-", $s); // заменяем пробелы знаком минус
  return $s; // возвращаем результат
}


//..............................................................................
// возвращает ЧПУ из текста названия
//..............................................................................
function translit_url($title=NULL)
	{
	return preg_replace('/\-+/', '-', get_str_cut(strtolower(translit(str_replace(' ','-', trim(strip_tags($title))))), TRANSLIT_URL_LEN));
	}
	
//..............................................................................
// возвращает дату и время для datetime MySQL
//..............................................................................
function mysql_now()
	{
	return get_mysql_time_str(strtotime('now'));
	}
	
?>