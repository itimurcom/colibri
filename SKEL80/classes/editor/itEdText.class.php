<?php
// ================ CRC ================
// version: 1.15.04
// hash: 0f4d181f159a9066d1390c19fa0f283e8dce90ef4d0ccac515dee58f30e817e2
// date: 30 April 2021 16:04
// ================ CRC ================
//-------------------------------------------------------------------------------------
// itEdText : класс обработки текстового блока редактора
//-------------------------------------------------------------------------------------
class itEdText
	{
	public $table_name, $rec_id, $field, $lang, $selector, $name, $data, $code, $alt;
	//..............................................................................
	// конструктор класса - создает привязку текстового блока
	//..............................................................................
	public function __construct($row=NULL)
		{
		$this->data = $row;
		$this->table_name 	= $this->data['table_name'];
		$this->rec_id	 	= $this->data['rec_id'];
		$this->ed_key	 	= $this->data['ed_key'];
		$this->field	 	= $this->data['field'];
		$this->column	 	= $this->data['column'];		
		$this->root	 	= $this->data['root'];
		$this->selector	 	= $this->data['selector'];
		$this->lang	 	= $this->data['lang'];
		$this->alt 		= ready_val($this->data['alt'], CMS_NAME);	
		$this->name 		= "{$this->data['name']}-text-{$this->data['ed_key']}";
		}


	//..............................................................................
	// просмотр аватарки
	//..............................................................................
	public function _avatar($button_set=NULL)
		{
		$avatar = NULL;
					
		if (isset($this->data['avatar']))
			{
			$avatar_img = get_const('TEXT_AVATAR_BIG')
				? get_big_thumbnail($this->data['avatar'],'ed_avatar', 'gallery_'.$this->data['table_name'].'_'.$this->data['rec_id'], $this->alt, 'ED_AVATAR')
				: "<img class='ed_avatar' title='{$this->alt}' alt='{$this->alt}' src='".get_thumbnail($this->data['avatar'], 'ED_AVATAR')."'/>";

			$zoom = (isset($this->data['zoom']) and ($this->data['zoom']=='FULL')) ? 'full' : 'small';
			$direction = (isset($this->data['position']) and ($this->data['position']=='RIGHT')) ? 'right' : 'left';
				
			return TAB."\t<div class='ed_avatar_div fancygall {$direction} {$zoom}'>".TAB."{$avatar_img}".TAB."{$button_set}".TAB."</div>";	
			}
		}

	//..............................................................................
	// просмотр текстового блока
	//..............................................................................
	public function _view()
		{
		global $editor_blocks;
		
		$code = isset($this->data['value'][CMS_LANG]) ? $this->data['value'][CMS_LANG] : NULL;
		$code = (in_array(trim($code), [' ','<br>','<br/>'])) ? NULL : $code;
		
		
		return mstr_replace([
			'[VALUE]'	=> $code,
			'[ID]'		=> $this->name,
			'[EDITABLE]'	=> '',
			'[EDCLASS]'	=> '',
			'[BUTTONS]'	=> '',
			'[EDIT]'	=> '',
			'[REL]'		=> '',
			'[PLACEHOLDER]'	=> '',
			'[AVATAR]'	=> itEdText::_avatar(),
			], TAB.$editor_blocks['text']['code']);
		}

	//..............................................................................
	// редактирование текстового блока
	//..............................................................................
	public function _edit()
		{
		global $editor_blocks;
		
		$data =[
			'table_name' 	=> $this->table_name,
			'rec_id' 	=> $this->rec_id,
			'ed_key' 	=> $this->ed_key,
			'field' 	=> $this->field,
			'column' 	=> $this->column,
			'root' 		=> $this->root,			
			'lang' 		=> get_const('CMS_LANG'),
			'selector' 	=> $this->selector,
			'name' 		=> $this->name,
			];
		
		$avatar_class = !is_null($avatar_str = itEdText::_avatar(
						get_ed_avatar_event($this->data).
						get_ed_switch_event($this->data).
						get_ed_zoom_event($this->data)))
				? ' avatared'
				: NULL;
		
		return mstr_replace([
			'[VALUE]'	=> ready_val($this->data['value'][CMS_LANG]),
			'[ID]'		=> $this->name,
			'[EDITABLE]'	=> "contenteditable='true'",
			'[EDCLASS]'	=> " ed_active{$avatar_class}",
			'[BUTTONS]'	=> get_ed_buttons_set($this->data),
			'[EDIT]'	=> ' edit',
			'[REL]'		=> simple_encrypt(serialize($data)),
			'[PLACEHOLDER]'	=> get_const('ED_TEXT_PLACEHOLDER'),
			'[AVATAR]'	=> $avatar_str,
			], TAB.$editor_blocks['text']['code']);
		}
	
	//..............................................................................
	// разыменовывает поле 'text'
	//..............................................................................	
	public function compile()
		{
		global $_USER;
		
		$this->code = $_USER->is_logged(itEditor::moderators())
			? $this->_edit()
			: $this->_view();

/*		if (isset($this->data['avatar']))
			{
			$avatar_img = get_const('TEXT_AVATAR_BIG')
				? get_big_thumbnail($this->data['avatar'],'ed_avatar', 'gallery_'.$this->data['table_name'].'_'.$this->data['rec_id'], $this->alt, 'ED_AVATAR')
				: "<img class='ed_avatar' title='{$this->alt}' alt='{$this->alt}' src='".get_thumbnail($this->data['avatar'], 'ED_AVATAR')."'/>";
			$ed_class = ' avatared';

			if (isset($this->data['zoom']) and ($this->data['zoom']=='FULL'))
				{
				$zoom = ' full';
				} else $zoom = ' small';


		if ($_USER->is_logged(itEditor::moderators()))
			{
			$button_set = 
				get_ed_avatar_event($this->data).
				get_ed_switch_event($this->data).
				get_ed_zoom_event($this->data);
			} else $button_set = '';

		if (isset($this->data['position']) and ($this->data['position']=='RIGHT'))
			{
			$avatar = TAB."\t<div class='ed_avatar_div fancygall right".$zoom."'>".TAB."\t$avatar_img\n$button_set".TAB."</div>";
			} else  {
				$avatar = TAB."\t<div class='ed_avatar_div fancygall left".$zoom."'>".TAB."\t$avatar_img\n$button_set".TAB."</div>";
				}
        	} else 	{
			$avatar='';
			$ed_class='';
			}


		$this->code = TAB.$editor_blocks['text']['code'];
		$this->code = str_replace('[VALUE]', ready_val($this->data['value'][CMS_LANG]), $this->code);
		$this->code = str_replace('[ID]', $this->name, $this->code);

		if ($_USER->is_logged(itEditor::moderators()))
			{
			$data = array (
				'table_name' 	=> $this->table_name,
				'rec_id' 	=> $this->rec_id,
				'ed_key' 	=> $this->ed_key,
				'field' 	=> $this->field,
				'lang' 		=> get_const('CMS_LANG'),
				'selector' 	=> $this->selector,
				'name' 		=> $this->name,
				);

			$ed_buttons = get_ed_buttons_set($this->data);
                	$this->code = str_replace('[EDITABLE]', "contenteditable='true'", $this->code);
			$this->code = str_replace('[EDCLASS]', " ed_active$ed_class", $this->code);
			$this->code = str_replace('[BUTTONS]',  $ed_buttons, $this->code);
			$this->code = str_replace('[EDIT]', ' edit', $this->code);
			$this->code = str_replace('[REL]', simple_encrypt(serialize($data)), $this->code);
			$this->code = str_replace('[PLACEHOLDER]', get_const('ED_TEXT_PLACEHOLDER'), $this->code);
			} else 	{
		                $this->code = str_replace('[EDITABLE]', '', $this->code);
				$this->code = str_replace('[EDCLASS]',  '', $this->code);
				$this->code = str_replace('[BUTTONS]',  '', $this->code);
				$this->code = str_replace('[EDIT]', '', $this->code);
				$this->code = str_replace('[REL]', '', $this->code);
				$this->code = str_replace('[PLACEHOLDER]', '', $this->code);
				}

		$this->code = str_replace('[AVATAR]', $avatar, $this->code);
*/
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