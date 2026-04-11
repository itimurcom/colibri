<?php

global $modal_count;
$modal_count = (function_exists('rand_id')) ? rand_id() : time();

class itModal
	{
	public $code, $fields, $ajax;
	private $size, $form_id, $animation;

	public function __construct($form_id=NULL)
		{
		global $modal_count;
		$modal_count++;

		if ($form_id==NULL)
			{
			$form_id = "modal-$modal_count";
			}
		$this->form_id 	= $form_id;
		$this->size		= DEFAULT_MODAL_SIZE;
		$this->animation        = DEFAULT_MODAL_ANIMATION;
		$this->fields		= [];
		$this->ajax		= NULL;
		}

	public function set_ajax($code=NULL)
		{
		$this->ajax = $code;
		}

	public function set_size($size=DEFAULT_MODAL_SIZE)
		{
		$this->size = $size;
		}

	public function set_animation($animation=DEFAULT_MODAL_SIZE)
		{
		$this->animation = $animation;
		}

	public function compile()
		{
		$rows_code = '';
		if (count($this->fields))
			foreach ($this->fields as $key=>$row)
				{
				$rows_code .= $row;
				}

		$this->code = html_comment("начало кода модального окна: {$this->form_id}").
			TAB."<div class=\"reveal-modal {$this->size}\" id=\"{$this->form_id}\" data-animation=\"{$this->animation}\">".
			$rows_code.
			TAB."<div class=\"close-reveal-modal corner\" ".(($this->ajax!=NULL) ? " onclick=\"{$this->ajax}\" " : '')."></div>".
			TAB."</div>".html_comment("конец кода модального окна: {$this->form_id}");
		}

	public function add_title($title='')
		{
		$this->fields[] = TAB."<div class=\"modal_row title\">$title".TAB."</div>";
		}

	public function add_description($title='')
		{
		$this->fields[] = TAB."<div class=\"modal_row description\">$title".TAB."</div>";
		}

	public function add_field($f_code = '')
		{
		$this->fields[] = $f_code;
		}

	public function add_buttons($b_code = '')
		{
		$this->fields[] =
			TAB."<div class=\"buttons_div\">".
			$b_code.
			TAB."</div>";
		}

	public function form_id()
		{
		return $this->form_id;
		}

	public function code()
		{
		return $this->code;
		}

	} // class

?>
