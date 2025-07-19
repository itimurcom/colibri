<?
global $_JS, $_CSS, $glog_og, $_CONTENT;

//..............................................................................
// itSite : класс, который строит сайт по запросам от itRouter
//..............................................................................
class itSite
	{
	public $controller, $view, $table_name, $rec_id, $lang;
	private $ed_rec, $header;


	//..............................................................................
	// конструктор класса - настраивает обратотчик запроса, данные из $_REQUEST
	//..............................................................................	
	public function __construct($data=NULL)
		{
		global $_JS, $_CSS, $_OG, $plug_media, $header;

		$this->controller	= $_REQUEST['controller'];
		$this->view			= $_REQUEST['view'];
		$this->table_name	= $_REQUEST['table_name'];
		$this->rec_id		= $_REQUEST['rec_id'];
		$this->lang			= $_REQUEST['lang'];

		$templ = ready_val($data['template']);
		$this->template		= file_exists($file ='themes/'.CMS_THEME."/tpl.{$templ}.php")
			? $file
			: $templ;
		}


	//..............................................................................
	// отрабатывает контроллер и создает результат сайта
	//..............................................................................	
	public function compile() {
		global $_USER, $_JS, $_CSS, $_OG, $plug_media, $_CONTENT, $_MARKUP;
		// проверим на наличие контроллер и вид

		if (empty($this->template)) {
			$contoller_file = !file_exists($controller_file = CONTROLLER_DIR."controller.{$this->controller}.{$this->view}.php") ? CONTROLLER_DIR."controller.{$this->controller}.php" : $controller_file;
			$view_file	= !file_exists($view_file = VIEW_DIR."view.{$this->view}.php") ? VIEW_DIR."view.{$this->controller}.{$this->view}.php" : $view_file;
			$view_file	= !file_exists($view_file) ? VIEW_DIR."view.{$this->controller}.php" : $view_file;
			$error = false;


			if (!file_exists($contoller_file)) {
				$error = true;
	// 			add_error_message("<b>Controller</b> not found : <b>{$this->controller}</b>!");
				}

			if (!file_exists($view_file)) {
				$error = true;
	// 			add_error_message("<b>View</b> not found : <b>{$this->view}</b>!");
				}

			if ($error) {
				// страница не найдена - отдаем 404
				$contoller_file = CONTROLLER_DIR."controller.404.php";
				$view_file	= VIEW_DIR."view.404.php";
				$this->controller	= '404';
				$this->view 		= '404';
				}

			// пакуем сайт
			if (!file_exists($contoller_file) AND (get_const('DEBUG_ON')==1)) {
				echo "<br> cant find <b>".str_replace(".php", '', basename($contoller_file))."</b>";
				} else  {
					@include $contoller_file;	
					}
			}


		// обработаем собщение об ошибке
		$o_error = new itErrorMsg();
		if ($o_error->code!=NULL)
			{
			$_CONTENT['error'] = $o_error->code;
			}
		unset($o_error);

		// обработаем фокус
		$o_focus = new itFocus();
		if ($o_focus->code!=NULL)
			{
			$_CONTENT['focus'] = $o_focus->code;
			}
		unset($o_focus);

		// подготовим полный заголовок <HEAD>
		$this->header = new itHeader();
		$this->header->prepare();
		$this->header->compile();
		$_CONTENT['header'] = $this->header->code();

		// подготовим разметку
		if ((get_const('SKIP_MARKUP')!==1) AND is_array($_MARKUP)) {
			$o_markup =  new itMarkUp();
			unset($o_markup);
			}

		// код счетчиков
		$o_counter = new itCounter();
		$_CONTENT['analytics'] = $o_counter->code();
		unset($o_counter);

		// загрузим шаблоны
		if (empty($this->template)) {
			// HEADER
			if (!file_exists("themes/".CMS_THEME."/tpl.header.php") AND (get_const('DEBUG_ON')==1)) {
				echo "<br> cant find <b>tpl.header</b>";
				} else  {
					@include "themes/".CMS_THEME."/tpl.header.php";	
					}

			// VIEW			
			if (!file_exists($view_file) AND (get_const('DEBUG_ON')==1)) {
				echo "<br> cant find <b>".str_replace(".php", '', basename($view_file))."</b>";
				} else {
					@include $view_file;	
					}			

			// FOOTER
			if (!file_exists("themes/".CMS_THEME."/tpl.footer.php") AND (get_const('DEBUG_ON')==1))	{
				echo "<br> cant find <b>tpl.footer</b>";
				} else 	{
					@include "themes/".CMS_THEME."/tpl.footer.php";	
					}			
			} else {
				// TEMPLATE
				if (!file_exists($this->template) AND (get_const('DEBUG_ON')==1) ) {
				 	echo "<br> cant find <b>".str_replace(".php", '', basename($this->template))."</b>";
					} else {
						include $this->template;	
						}			
				}
		}
	} // class

?>