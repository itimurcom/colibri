<?php
// ================ CRC ================
// version: 1.15.08
// hash: 864379dea1a13b46d8a43569d92f3b0bab6650301d3503659d316fd414c178ac
// date: 30 September 2019  5:36
// ================ CRC ================
//-------------------------------------------------------------------------------------
// itEdGallery : класс обработки галереи изобрадений
//-------------------------------------------------------------------------------------
class itEdGallery
	{
	public $data, $code;
	//..............................................................................
	// конструктор класса - создает привязку редактора к записи в базе данных
	//..............................................................................
	//
	//	options:
	//
	//	'value'		=> данные блока, зависят от типа
	//	'zoom'		=> значение размера для выбранного типа
	//	'avatar'	=> картинка аватарки для блока
	//
	//..............................................................................
	public function __construct($row=NULL)
		{
		$this->data = $row;
		$this->table_name 	= $this->data['table_name'];
		$this->rec_id	 	= $this->data['rec_id'];
		$this->ed_key	 	= $this->data['ed_key'];
		$this->field	 	= $this->data['field'];
		$this->column	 	= $this->data['column'];		
		$this->selector	 	= $this->data['selector'];
		$this->lang	 	= $this->data['lang'];
		$this->root	 	= $this->data['root'];
		$this->alt 		= ready_val($this->data['alt'], CMS_NAME);		
		$this->name 		= "{$this->data['name']}-gallery-{$this->data['ed_key']}";
		if (!isset($this->data['zoom'])) $this->data['zoom'] = get_const('DEFAULT_GALLERY_ZOOM');
		$this->f_caption	= DEFAULT_F_CAPTION_FUNC;
		}


	//..............................................................................
	// просмотр изображения из данных
	//..............................................................................	
	public function _view_image($gallery_id=0)
		{
		//  есть надпись над изображением
		if (isset($this->data['text'][$gallery_id]) AND isset($this->data['text'][$gallery_id][CMS_LANG]) AND !empty($this->data['text'][$gallery_id][CMS_LANG]))
			{
			$lettering_code = TAB."<div class='lettering boxed'>".$this->data['text'][$gallery_id][CMS_LANG].TAB."</div>";
			$caption_str	= stripQuotas(function_exists($f_caption = $this->f_caption) ? $f_caption($this->data, $gallery_id) : NULL);
			$title_str	= $this->data['text'][$gallery_id][CMS_LANG];
			} else	{
				$lettering_code = NULL;
				$title_str 	= $this->alt;
				$caption_str	= NULL;
				}
//		if (isset($this->data['text'][$gallery_id])) echo print_rr($this->data['text'][$gallery_id]);
		$type = 
			(count($this->data['value']) == 1)
				? (($this->data['zoom']=='SMALL') ? 'EDIMAGE_SMALL' : 'EDIMAGE')
				: $this->_zoomdata()['type'];

		return ( (!isset($this->data['link'][$gallery_id]) OR !isset($this->data['link'][$gallery_id][CMS_LANG]) OR is_null(ready_val($this->data['link'][$gallery_id][CMS_LANG]))) 
			? TAB.get_big_thumbnail([
				'src'		=> $this->data['value'][$gallery_id], 
				'class'		=> 'ed_image',
				'gallery'	=> $this->name,
				'title'		=> $this->alt,
				'type'		=> $type,
				'caption'	=> $caption_str, ])
//				: TAB."<img class='gallery_avatar' src='".get_thumbnail($this->data['value'][$gallery_id], $type)."' title='{$this->alt}' alt='{$this->alt}'/>")
			: TAB."<a href='{$this->data['link'][$gallery_id][CMS_LANG]}' target='_blank'><img class='gallery_avatar' src='".get_thumbnail($this->data['value'][$gallery_id], $type)."' title='{$this->alt}' alt='{$this->alt}'/></a>").$lettering_code;
		}
		
	//..............................................................................
	// редактирование изображения из данных
	//..............................................................................	
	public function _edit_image($gallery_id=0)
		{
		return
			TAB."\t<div class='edit'>".
					TAB."<div class='slide'>".
					$this->_view_image($gallery_id).
					TAB."</div>".				

					TAB."<div class='admin'>".				
					get_gal_link_event($this->data, $gallery_id).
					get_gal_text_event($this->data, $gallery_id).				
					TAB."</div>".
			TAB."</div>".
			TAB."<div class='gal_add_container'>".
				get_ed_image_event($this->data).
			TAB."</div>";
		}
		
		
	//..............................................................................
	// простая ленточная галлерея
	//..............................................................................	
	public function _zoomdata()
		{
		switch ($this->data['zoom'])
			{
			case 6 : {
				$result = [
					'type'		=> 'GALLINE_ULTRA',
					'class'		=> 'row_6',
					];
				break;
				}

			case 5 : {
				$result = [
					'type'		=> 'GALLINE_SMALL',
					'class'		=> 'row_5',
					];				
				break;
				}
			case 4 : {
				$result = [
					'type'		=> 'GALLINE_MIDDLE',
					'class'		=> 'row_4',
					];				
				break;
				}

			case 3 : {
				$result = [
					'type'		=> 'GALLINE_MIDDLE',
					'class'		=> 'row_3',
					];				
				break;
				}
			case '13' : {
				$result = [
					'type'		=> 'GALLINE_MIDDLE',
					'slider'	=> 'row_5',					
					'class'		=> 'DEFAULT_SLIDER_MIDDLE',
					];				
				break;
				}

			case '15' : {
				$result = [
					'type'		=> 'GALLINE_SMALL',
					'slider'	=> 'row_5',
					'class'		=> 'DEFAULT_SLIDER_SMALL',
					];				
				break;
				}
				
			default :{
				$result = ($this->data['zoom'] > 10)
					?	[
						'type'		=> 'SLIDER_COMMON',
						'slider'	=> 'row_1',
						'class'		=> 'DEFAULT_SLIDER_COMMON',
						]
					:	[
						'type'		=> 'GALLINE',
						'class'		=> 'row_2',
						];
				}
			}
			
		return $result;
		}		

	//..............................................................................
	// простая ленточная галлерея
	//..............................................................................	
	public function _view_gallery()
		{
		$result = NULL;
		$data = $this->_zoomdata();

		foreach ($this->data['value'] as $key=>$value)
			{
			$result .= 
				TAB."\t<div class='gallery_avatar_div {$data['class']}'>".
				$this->_view_image($key).
				TAB."\t</div>";
			}
		return 
			TAB."<div class='container' id='{$this->name}-slider'>".
			$result.
			TAB."</div>";
		}
		
	//..............................................................................
	// простая ленточная галлерея
	//..............................................................................	
	public function _edit_gallery()
		{
		$result = NULL;
		$data = $this->_zoomdata();

		foreach ($this->data['value'] as $key=>$value)
			{
			$result .=
				TAB."\t<div class='gallery_avatar_div edit {$data['class']}'>".
					TAB."<div class='slide'>".
					$this->_view_image($key).
					TAB."</div>".				

					TAB."<div class='admin'>".				
						TAB."\t<div class='gallery_n'>#".($key+1)."</div>".
						get_gal_x_event($this->data, $key).
						get_gal_down_event($this->data, $key).
						get_gal_up_event($this->data, $key).
						get_gal_n_event($this->data, $key).
						get_gal_link_event($this->data, $key).
						get_gal_text_event($this->data, $key).				
					TAB."</div>".
				TAB."</div>".
				"";
				
			}
		return 
			TAB."<div class='container' id='{$this->name}-slider'>".
			$result.
			TAB."</div>".
			TAB."<div class='gal_add_container'>".
				get_ed_image_event($this->data).
			TAB."</div>";
			
		}

	//..............................................................................
	// просмотр слайдера из данных
	//..............................................................................	
	public function _view_slider()
		{
		$result = NULL;
		$data = $this->_zoomdata();			

		foreach ($this->data['value'] as $key=>$value)
			{
			$result .=
				TAB."<div class='slide'>".
				$this->_view_image($key).						
				TAB."</div>";
			}
			
		return	TAB."<div class='slider {$data['slider']}' id='{$this->name}-slider'>".
			$result.
			TAB."</div>".
			TAB.minify_js("<script>
			$('#{$this->name}-slider').css({'visibility':'hidden','opacity':'0'});
			$(document).ready(function()
				{
				$('#{$this->name}-slider').bxSlider(
					{
					".get_const($data['class']).",
					auto : true,
					autoStart : true,
					touchEnabled : (navigator.maxTouchPoints > 0),
					pause : ".DEFAULT_SLIDER_PAUSE.",
					});
				$('#{$this->name}-slider').css('visibility','visible');
				$('#{$this->name}-slider').animate({'opacity':'1'});
				});
			</script>");			
		}

	//..............................................................................
	// редактирование слайдера из данных
	//..............................................................................	
	public function _edit_slider()
		{
		$result = NULL;
		$data = $this->_zoomdata();			

		foreach ($this->data['value'] as $key=>$value)
			{
			$result .=
				TAB."\t<div class='edit'>".
					TAB."<div class='slide'>".
					$this->_view_image($key).
					TAB."</div>".				

					TAB."<div class='admin'>".				
					get_gal_x_event($this->data, $key).
					get_gal_down_event($this->data, $key).
					get_gal_up_event($this->data, $key).
					get_gal_n_event($this->data, $key).
					get_gal_link_event($this->data, $key).
					get_gal_text_event($this->data, $key).				
					TAB."</div>".
				TAB."</div>".
				"";
			}
			
		return	TAB."<div class='slider {$data['slider']}' id='{$this->name}-slider'>".
			$result.
			TAB."</div>".
			TAB."<div class='gal_add_container'>".
				get_ed_image_event($this->data).
			TAB."</div>".
			TAB.minify_js("<script>
			$('#{$this->name}-slider').css({'visibility':'hidden','opacity':'0'});
			$(document).ready(function()
				{
				$('#{$this->name}-slider').bxSlider(
					{
					".get_const($data['class']).",
					touchEnabled : (navigator.maxTouchPoints > 0),					
					});
				$('#{$this->name}-slider').css('visibility','visible');
				$('#{$this->name}-slider').animate({'opacity':'1'});
				});
			</script>");			
		}
		


	//..............................................................................
	// просмотр галлереи 
	//..............................................................................	
	public function _view()
		{
		global $editor_blocks;
		$code = (count($this->data['value'])==1)
			? 	TAB."<div class='slide'>".
				$this->_view_image().
				TAB."</div>"
			: (($this->data['zoom']>10)
				? $this->_view_slider()
				: $this->_view_gallery());

		return mstr_replace([
			'[VALUE]'	=> $code,
			'[ID]'		=> $this->name,
			'[BUTTONS]'	=> '',
			'[EDIT]'	=> '',
			], TAB.$editor_blocks['gallery']['code']);		
		}

	//..............................................................................
	// редактирование галлереи 
	//..............................................................................	
	public function _edit()
		{
		global $editor_blocks;
		$code = (count($this->data['value'])==1)
			? $this->_edit_image()
			: (($this->data['zoom']>10)
				? $this->_edit_slider()
				: $this->_edit_gallery());

		return mstr_replace([
			'[VALUE]'	=> $code,
			'[ID]'		=> $this->name,
			'[BUTTONS]'	=> get_ed_buttons_set($this->data),
			'[EDIT]'	=> ' edit',
			], TAB.$editor_blocks['gallery']['code']);		
		}
				
	//..............................................................................
	// разыменовывает поле 
	//..............................................................................	
	public function compile()
		{
		global $_USER;
		$this->code = $_USER->is_logged(itEditor::moderators())
			? $this->_edit()
			: $this->_view();		
		}

	//..............................................................................
	// возвращает код текстового блока
	//..............................................................................
	public function code()
		{
		return $this->code;
		}

	}
?>