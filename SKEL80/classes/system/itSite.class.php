<?php
global $plug_js, $plug_css, $glog_og, $_CONTENT;

class itSite
	{
	public $controller, $view, $table_name, $rec_id, $lang;
	private $ed_rec, $header;

	private function request_value($key, $default=NULL)
		{
		return isset($_REQUEST[$key]) ? $_REQUEST[$key] : $default;
		}

	public function __construct()
		{
		global $plug_js, $plug_css, $plug_og, $plug_media, $header;

		$this->controller	= $this->request_value('controller', DEFAULT_ROUTER_CONTROLLER);
		$this->view		= $this->request_value('view', DEFAULT_ROUTER_VIEW);
		$this->table_name	= $this->request_value('table_name');
		$this->rec_id		= $this->request_value('rec_id');
		$this->lang		= $this->request_value('lang', defined('CMS_LANG') ? CMS_LANG : get_const('DEFAULT_LANG'));
		}

	public function compile()
		{
		global $_USER, $plug_js, $plug_css, $plug_og, $plug_media, $_CONTENT, $_MARKUP;
		$contoller_file = !file_exists($controller_file = CONTROLLER_DIR."controller.{$this->controller}.{$this->view}.php") ? CONTROLLER_DIR."controller.{$this->controller}.php" : $controller_file;
		$view_file	= !file_exists($view_file = VIEW_DIR."view.{$this->view}.php") ? VIEW_DIR."view.{$this->controller}.{$this->view}.php" : $view_file;
		$view_file	= !file_exists($view_file) ? VIEW_DIR."view.{$this->controller}.php" : $view_file;
                $error = false;

		if (!file_exists($contoller_file))
			{
			$error = true;
			}

		if (!file_exists($view_file))
			{
			$error = true;
			}

		if ($error)
			{
			$contoller_file = CONTROLLER_DIR."controller.404.php";
			$view_file	= VIEW_DIR."view.404.php";
			$this->controller	= '404';
			$this->view 		= '404';
			}

		if (!file_exists($contoller_file) AND (get_const('DEBUG_ON')==1))
			{
			echo "<br> cant find <b>".str_replace(".php", '', basename($contoller_file))."</b>";
			} else
				{
				@include $contoller_file;
				}

		$o_error = new itErrorMsg();
		if ($o_error->code!=NULL)
			{
			$_CONTENT['error'] = $o_error->code;
			}
		unset($o_error);

		$o_focus = new itFocus();
		if ($o_focus->code!=NULL)
			{
			$_CONTENT['focus'] = $o_focus->code;
			}
		unset($o_focus);

		$this->header = new itHeader();
		$this->header->prepare();
		$this->header->compile();
                $_CONTENT['header'] = $this->header->code();

		if ((get_const('SKIP_MARKUP')!==1) AND is_array($_MARKUP))
			{
			$o_markup =  new itMarkUp();
			unset($o_markup);
			}

		$o_counter = new itCounter();
		$_CONTENT['analytics'] = $o_counter->code();
		unset($o_counter);

		if (!file_exists("themes/".CMS_THEME."/tpl.header.php") AND (get_const('DEBUG_ON')==1))
			{
			echo "<br> cant find <b>tpl.header</b>";
			} else
				{
				@include "themes/".CMS_THEME."/tpl.header.php";
				}

		if (!file_exists($view_file) AND (get_const('DEBUG_ON')==1))
			{
			echo "<br> cant find <b>".str_replace(".php", '', basename($view_file))."</b>";
			} else
				{
				@include $view_file;
				}

		if (!file_exists("themes/".CMS_THEME."/tpl.footer.php") AND (get_const('DEBUG_ON')==1))
			{
			echo "<br> cant find <b>tpl.footer</b>";
			} else
				{
				@include "themes/".CMS_THEME."/tpl.footer.php";
				}

		}

	} // class

?>
