<?php
// ================ CRC ================
// version: 1.15.14
// hash: 26e7607520a3ee436e1055a723dc3b5b8dd20d1d4a047168cd38839a3a386ebb
// date: 21 May 2021 10:57
// ================ CRC ================
/*
//-------------------------------------------------------------------------------------
// Стандартная стуктура поля для блока материала
//-------------------------------------------------------------------------------------

	'type'		=> тип матрериала
	'value'		=> данные блока, зависят от типа
	'zoom'		=> значение размера для выбранного типа
	'avatar'	=> картинка аватарки для блока
	
*/

//-------------------------------------------------------------------------------------
// itEditor : класс для создания линейного редактора в любом месте сайта
//-------------------------------------------------------------------------------------
class itEditor
	{
	public $table_name, $rec_id, $name, $data, $code, $txt, $title, $description, $options; 
	public $no_cache, $no_title, $no_date, $no_lang, $no_moderate, $no_avatar, $no_related;
	public $field, $column;
	
	//..............................................................................
	// конструктор класса - создает привязку редактора к записи в базе данных
	//..............................................................................
	public function __construct($table_name=DEFAULT_CONTENT_TABLE, $rec_id=NULL, $no_date=NULL, $no_lang=NULL, $no_moderate=false, $no_avatar=NULL, $field=DEFAULT_CONTENT_FIELD)
		{
		if (!is_array($table_name))
			{
			$this->table_name 	= $table_name;
			$this->rec_id 		= $rec_id;
			$this->field 		= $field;
			$this->column 		= $field;
			$this->no_date 		= $no_date;
			$this->no_lang 		= $no_lang;
			$this->no_moderate 	= $no_moderate;
			$this->no_avatar 	= $no_avatar;
			$this->viewed 		= false;
			$this->title_class	= NULL;
			$this->no_title		= DEFAULT_NOTITLE;
			$this->data = itMySQL::_get_rec_from_db($this->table_name, $this->rec_id);			
			$this->rel_field	= DEFAULT_RELATED_FIELD;
			$this->no_related	= DEFAULT_NORELATED;
			$this->async		= DEFAULT_EDASYNC;
			} else	{
				// передали массив нужно разыменовать все
				$data = $table_name;
				$this->table_name 	= isset($data['table_name']) 	? $data['table_name'] 	: DEFAULT_CONTENT_TABLE;
				$this->rec_id 		= isset($data['rec_id']) 	? $data['rec_id'] 	: NULL;
				$this->field 		= isset($data['field']) 	? $data['field'] 	: DEFAULT_CONTENT_FIELD;
				$this->column 		= isset($data['column']) 	? $data['column'] 	: DEFAULT_CONTENT_COLUMN;
				$this->no_date 		= isset($data['no_date']) 	? $data['no_date'] 	: NULL;
				$this->no_lang 		= isset($data['no_lang']) 	? $data['no_lang'] 	: DEFAULT_NODATE;
				$this->no_moderate 	= isset($data['no_moderate']) 	? $data['no_moderate'] 	: DEFAULT_NOMODERATE;
				$this->no_avatar 	= isset($data['no_avatar']) 	? $data['no_avatar'] 	: NULL;
				$this->viewed 		= isset($data['viewed']) 	? $data['viewed'] 	: false;
				$this->title_class	= isset($data['title_class']) 	? $data['title_class'] 	: NULL;
				$this->no_title		= isset($data['no_title']) 	? $data['no_title'] 	: DEFAULT_NOTITLE;
				$this->rel_field	= isset($data['rel_field']) 	? $data['rel_field'] 	: DEFAULT_RELATED_FIELD;
				$this->no_related	= isset($data['no_related']) 	? $data['no_related'] 	: DEFAULT_NORELATED;
				$this->async		= isset($data['async']) 	? $data['async'] 	: DEFAULT_EDASYNC;				
				$this->data		= isset($data['data']) 		? $data['data'] 	: itMySQL::_get_rec_from_db($this->table_name, $this->rec_id);
				}

		$this->no_cache 	= isset($data['no_cache']) 	? $data['no_cache'] 	: DEFAULT_NOCACHE;
		$this->edclass 		= isset($data['edclass']) 	? $data['edclass'] 	: DEFAULT_EDCLASS;

		$this->root 		= isset($data['root']) 		? $data['root'] 	: NULL;


		if (!is_null($this->root))
			{
			$this->record = &$this->data[$this->column][$this->root];
			$this->storage = &$this->data[$this->column][$this->root][$this->field];
			
			$this->record['status'] 	= isset($this->record['status']) 	? $this->record['status'] 	: DEFAULT_EDSTORAGE_STATUS;
			$this->record['lang'] 		= isset($this->record['lang']) 		? $this->record['lang'] 	: 'ALL';		
			} else  {
				$this->record = &$this->data;
				$this->storage = &$this->data[$this->column];
				}

		// проверка на то, что это работа по разному для всех
		if ( (($this->selector = ready_val($this->record['lang'], 'ALL'))=='ALL') AND !isset($this->storage['ALL']) AND isset($this->storage[CMS_LANG]))
			{
			$this->storage['ALL'] = itEditor::_consolidate($this->storage);
			$this->store();
			}


		$this->name = 
			"editor-".crc32(
			(isset($data['table_name']) 	? "-{$data['table_name']}" 	: NULL).
			(isset($data['rec_id']) 	? "-{$data['rec_id']}" 		: NULL).
			(isset($data['column']) 	? "-{$data['column']}" 		: NULL).
			(isset($data['root']) 		? "-{$data['root']}" 		: NULL).
			"");
		// определимся с селектором данных их общего массива
		if (is_array($this->storage))
			{
			$this->title = itEditor::title($this->record);
			
			// проверим поле URL
			if (isset($this->storage['url']))
				{
				$url = function_exists('translit_url') ? translit_url($this->title)."-{$this->rec_id}" : "material-{$this->rec_id}";
					
				if (!isset($this->storage['url'][CMS_LANG]) OR ($this->storage['url'][CMS_LANG]!=$url))
					{
					$this->storage['url'][CMS_LANG] = $url;
					itMySQL::_update_value_db($this->table_name, $this->rec_id, $this->storage['url'], 'url');
					}
				}
			}
		}

	//..............................................................................
	// возвращает код заколовка материала
	//..............................................................................	
	public function get_title($no_date=NULL, $no_lang=NULL, $no_moderate=NULL, $no_avatar=NULL, $no_related=NULL)
		{
		$data = $this->record;
		$data['table_name'] 	= $this->table_name;
		$data['rec_id'] 	= $this->rec_id;
		$data['name'] 		= $this->name;
		$data['no_date']	= !is_null($this->no_date) 	? $this->no_date 	: (!is_null($no_date) 		? $no_date 	: DEFAULT_NODATE);
		$data['no_lang']	= !is_null($this->no_lang) 	? $this->no_lang 	: (!is_null($no_lang) 		? $no_lang 	: DEFAULT_NOLANG);
		$data['no_moderate']	= !is_null($this->no_moderate)	? $this->no_moderate 	: (!is_null($no_moderate) 	? $no_moderate 	: DEFAULT_NOMODERATE);
		$data['no_avatar'] 	= !is_null($this->no_avatar)	? $this->no_avatar 	: (!is_null($no_avatar) 	? $no_avatar 	: DEFAULT_NOAVATAR);
		$data['no_title'] 	= !is_null($this->no_title)	? $this->no_title 	: DEFAULT_NOTITLE;
		$data['class']		= !is_null($this->title_class)	? $this->title_class 	: NULL;

		$o_title = new itEdTitle($data);
		$o_title->compile();		
		$result = $o_title->code();
		unset($o_title);
		return $result;
		}

	//..............................................................................
	// возвращает флаг пустого редактора
	//..............................................................................	
	public function is_empty()
		{
		return (!is_array($this->root) OR is_null($this->root) OR !count($this->root) OR (count($this->root)==1 AND empty($this->root[0]['value'])));
		}
		
	//..............................................................................
	// возвращает ЧПУ для материала
	//..............................................................................	
	public function url()
		{
		if (is_array($this->record))
			{
			return translit_url($this->title)."-".$this->rec_id;
			} else return NULL;
		}

	//..............................................................................
	// возвращает значение названия материала
	//..............................................................................	
	static function title($row)
		{
		$row['title_xml'] = isset($row['title_xml']) ? $row['title_xml'] : NULL;
		return (trim($result = get_field_by_lang($row['title_xml']))=='') ? get_const('NO_TITLE') : $result;
		}

	//..............................................................................
	// возвращает текст новости для создания её описания
	//..............................................................................
	public function txt()
		{
		$result = '';
		if ($this->is_loaded())
			if (isset($this->storage[$this->selector]))
			foreach($this->storage[$this->selector] as $key=>$row)
				{
				if (isset($row['type']) and ($row['type']=='text'))
					{
					$result .= isset($row['value']) ? get_field_by_lang($row['value'], CMS_LANG, '')." " : '';
					}
				}

		$this->txt = trim(str_replace('  ',' ',html2txt($result)));

		$this->txt = mstr_replace([
			"\n"	=> '',
			"\r"	=> '',
			"\'"	=> "&apos;",
			"\\\'"	=> "&apos;",			
			"\""	=> "&quot;",
			"\\\""	=> "&quot;",
			"\\"	=> ''],
			$this->txt);
		
		return $this->txt;
		}

	//..............................................................................
	// возвращает подготовленное описание новости
	//..............................................................................
	public function description($len=DEFAULT_STR_CUT)
		{
		$this->description = get_str_cut($this->txt(), $len);
		return $this->description;
		}

	//..............................................................................
	// возвращает сохраненные данные редактора
	//..............................................................................
	public function xml()
		{
		$result = $this->storage;
		$result['table_name'] 	= $this->table_name;
		$result['rec_id'] 	= $this->rec_id;

		return $result;
		}


	//..............................................................................
	// возвращает ссылку на изображение, для построения аватарки материала
	//..............................................................................
	static function get_avatar($data, $og=false, $selector=NULL, $first=false, $field=DEFAULT_CONTENT_FIELD)
		{
		$result = NULL;
		if (!empty($data['avatar']))
			{
			$result = $data['avatar'];
			} else	{
				$selector = is_null($selector) ? (($data['lang']=='ALL') ? 'ALL' : CMS_LANG) : $selector;
				$ava_arr = [];
				// попробуем найти изображения в теле материала
			        if (isset($data[$field][$selector]) and is_array($data[$field][$selector]))
					{
					foreach ($data[$field][$selector] as $key=>$row)
						{
						switch ($row['type'])
							{
							case 'image' : {
								if ($first) return $row['value'];
									else $ava_arr[] = $row['value'];
								break;
								}
							case 'gallery' : {
								if (isset($row['value']) and is_array($row['value']))
									{
	                		                        	foreach ($row['value'] as $line=>$img)
										{
										if ($first) return $img;
											else $ava_arr[] = $img;
										}
									}
								break;
								}

							case 'text' : {
								if (isset($row['avatar']))
									{
									if ($first) return $row['avatar'];
										else $ava_arr[] = $row['avatar'];
									}
								break;
								}

							case 'media' :
								{
								if (!isset($row['avatar']) OR !file_exists(UPLOADS_ROOT.$row['avatar']))
									{
									$row['avatar'] = itEdMedia::get_media_preview($row['value']);
									}
								
								if (file_exists(UPLOADS_ROOT.$row['avatar']))
									{
									if ($first) return $row['avatar'];
										else $ava_arr[] = $row['avatar'];
									}
								break;
								}
							}

						}
					// выбираем из результатов поиска изображения
					if (is_array($ava_arr) AND count($ava_arr))
						{
						if ($og==false)
							{
							$index = rand(0, count($ava_arr)-1);
							} else $index = 0;
						$result = $ava_arr[$index];
						}
					}
			}
		return $result;
		}

	//..............................................................................
	// возвращает ссылку на изображение, для записи в потоке
	//..............................................................................
	public function avatar($og=false)
		{
		return itEditor::get_avatar($this->record, $this->selector, $og, $this->column);
		}

	//..............................................................................
	// создает и возвращает изображение, подготовленное для поста в соц-сетях
	//..............................................................................
	public function og_image()
		{
		$thum_og = 'OG_AVATAR';
		$og_img = DEFAULT_OG_IMAGE;
		
		if (isset($this->storage))
			{
			$og_img = !empty($res_img = itEditor::get_avatar($this->record, true)) ? $res_img : $og_img;
			switch ($media = itEdMedia::is_ed_media($this->record))
				{
				case 'media' :
					{
					eval('$thum_og'."='OG_AVATAR_".strtoupper($media)."';");
					break;
					}
				default :
					{
					break;
					}
				}
			}
		$result = get_thumbnail($og_img, $thum_og); 
		return $result;
		}

	//..............................................................................
	// доступ к флагу данных, указывающих, что они загружены
	//..............................................................................	
	public function is_loaded()
		{
		return isset($this->record['id']);
//		return (isset($this->storage[$this->selector]) and is_array($this->storage[$this->selector]));
		}


	//..............................................................................
	// сортирует массив полей по ключу
	//..............................................................................
	public function sort($selector=NULL)
		{
		if (is_array($this->storage) and isset($this->storage[$selector]) and is_array($this->storage[$selector]))
			{
			$this->storage[$selector] = array_values($this->storage[$selector]);
			return true;
			}
		return false;
		}


	//..............................................................................
	// добавляет поле в массив редактора за указанным полем и сортирует его
	//..............................................................................
	public function insert_field($selector=NULL, $ed_key=NULL, $type='text', $value='')
		{
		switch ($type)
			{
			case 'text' : {
	       			$ed_new_field =  array(
					'ed_inserted' => array (
						'type' => 'text',
						'value'=> [get_const('CMS_LANG') => $value ],
						)
					);
				break;
				}
			default : {
		       		$ed_new_field =  array(
					'ed_inserted' => array (
						'type' => $type,
						'value'=> $value
						)
					);
				if ($type=='media')
					{
					$ed_new_field['ed_inserted']['avatar'] =  itEdMedia::get_media_preview($value);
					}
				break;
				}

			}

		// проверим есть ли больше одного поля
		if (isset($this->storage[$selector]) and is_array($this->storage[$selector]) and count($this->storage[$selector])>1)
			{
			// да? - раздвигаем массив
			$res = array_slice($this->storage[$selector], 0, $ed_key+1, true) +
				$ed_new_field +
				array_slice($this->storage[$selector], $ed_key+1, NULL, true);

			// переименовываем ключи массива, чтобы было все по порядку
			$this->storage[$selector] = $res;
			unset($res);
        		} else	{
				// есть только одно поле! - тупо добавим поле после первого
				if (!isset($this->storage[$selector]) or !is_array($this->storage[$selector]) or count($this->storage[$selector])==0)
					{
					$this->storage[$selector][] = [
						'type' => 'text',
						'value'=> [get_const('CMS_LANG') => '' ],
						];
					}
				$this->storage[$selector][] = $ed_new_field['ed_inserted'];
				}
		if ($this->sort($selector))
			{
			$this->store();
			}
		}


	//..............................................................................
	// поднимает поле вверх на одну позицию, если это возможно
	//..............................................................................
	public function up_field($selector, $ed_key)
		{
		if ($ed_key>0)
			{
			$this->storage[$selector]['tmp']	= $this->storage[$selector][$ed_key];
			$this->storage[$selector][$ed_key]	= $this->storage[$selector][$ed_key-1];
			$this->storage[$selector][$ed_key-1] = $this->storage[$selector]['tmp'];
			unset ($this->storage[$selector]['tmp']);
			if ($this->sort($selector))
				{
				$this->store();
				};
			$ed_key--;
			}
		return $ed_key;
		}


	//..............................................................................
	// поднимает поле вниз на одну позицию, если это возможно
	//..............................................................................
	public function down_field($selector, $ed_key)
		{
		if ($ed_key<count($this->storage[$selector]))
			{
			$this->storage[$selector]['tmp'] 	= $this->storage[$selector][$ed_key];
			$this->storage[$selector][$ed_key]	= $this->storage[$selector][$ed_key+1];
			$this->storage[$selector][$ed_key+1] = $this->storage[$selector]['tmp'];
			unset ($this->storage[$selector]['tmp']);
			if ($this->sort($selector))
				{
				$this->store();
				};
			$ed_key++;
			}
	
		return $ed_key;
		}


	//------------------------------------------------------------------------------
	// функции для работы полем галереи НАЧАЛО
	//------------------------------------------------------------------------------
	//
	//..............................................................................
	// перемещает запись изображения в галлереее
	//..............................................................................
	public function gal_replace_image($selector, $ed_key, $gal_id, $place_id)
		{
		if ($gal_id>0)
			{
			$this->storage[$selector][$ed_key]['value']['tmp'] 		= $this->storage[$selector][$ed_key]['value'][$gal_id];
			$this->storage[$selector]['value'][$ed_key][$gal_id]		= $this->storage[$selector][$ed_key]['value'][$place_id];
			$this->storage[$selector]['value'][$ed_key][$place_id] 	= $this->storage[$selector][$ed_key]['value']['tmp'];
			unset($this->storage[$selector][$ed_key]['value']['tmp']);
			$this->sort_gallery($selector, $ed_key);
			}
		}

	//..............................................................................
	// удаляет запись изображения в галлереее
	//..............................................................................
	public function gal_x_image($selector, $ed_key, $gal_id)
		{
		if (isset($this->storage[$selector][$ed_key]['value'][$gal_id]))
			{
			unset($this->storage[$selector][$ed_key]['value'][$gal_id]);
			// нужно добавить перемещение ссылки !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!			
			$this->sort_gallery($selector, $ed_key);
			}
		}

	//..............................................................................
	// поднимает запись изображения в галлерее вверх на одну позицию
	//..............................................................................
	public function gal_up($selector, $ed_key, $gal_id)
		{
		if ($gal_id>0)
			{
			$this->storage[$selector][$ed_key]['value']['tmp'] 		= $this->storage[$selector][$ed_key]['value'][$gal_id];
			$this->storage[$selector][$ed_key]['value'][$gal_id]		= $this->storage[$selector][$ed_key]['value'][($gal_id-1)];
			$this->storage[$selector][$ed_key]['value'][($gal_id-1)] 	= $this->storage[$selector][$ed_key]['value']['tmp'];
			unset($this->storage[$selector][$ed_key]['value']['tmp']);
			
			// то же самое для текста
			if (isset($this->storage[$selector][$ed_key]['text']))
				{
				$this->storage[$selector][$ed_key]['text']['tmp'] 		= $this->storage[$selector][$ed_key]['text'][$gal_id];
				$this->storage[$selector][$ed_key]['text'][$gal_id]		= $this->storage[$selector][$ed_key]['text'][($gal_id-1)];
				$this->storage[$selector][$ed_key]['text'][($gal_id-1)] 	= $this->storage[$selector][$ed_key]['text']['tmp'];
				unset($this->storage[$selector][$ed_key]['text']['tmp']);
				}

			// то же самое для ссылки
			if (isset($this->storage[$selector][$ed_key]['link']))
				{
				$this->storage[$selector][$ed_key]['link']['tmp'] 		= $this->storage[$selector][$ed_key]['link'][$gal_id];
				$this->storage[$selector][$ed_key]['link'][$gal_id]		= $this->storage[$selector][$ed_key]['link'][($gal_id-1)];
				$this->storage[$selector][$ed_key]['link'][($gal_id-1)] 	= $this->storage[$selector][$ed_key]['link']['tmp'];
				unset($this->storage[$selector][$ed_key]['link']['tmp']);
				}

			$this->sort_gallery($selector, $ed_key);
			}
		}

	//..............................................................................
	// перемещает запись изображения в галлереее
	//..............................................................................
	public function gal_move($selector, $ed_key, $gal_id, $new_id)	
		{
		if ($gal_id!=$new_id)
			{
			$this->storage[$selector][$ed_key]['value']['tmp'] 		= $this->storage[$selector][$ed_key]['value'][$gal_id];
			$this->storage[$selector][$ed_key]['value']['tmp2'] 		= $this->storage[$selector][$ed_key]['value'][$new_id];
			$this->storage[$selector][$ed_key]['value'][$new_id] 	= $this->storage[$selector][$ed_key]['value']['tmp'];
			$this->storage[$selector][$ed_key]['value'][$gal_id] 	= $this->storage[$selector][$ed_key]['value']['tmp2'];
			unset($this->storage[$selector][$ed_key]['value']['tmp']);
			unset($this->storage[$selector][$ed_key]['value']['tmp2']);

			// то же самое для текста
			if (isset($this->storage[$selector][$ed_key]['text']))
				{			
				$this->storage[$selector][$ed_key]['text']['tmp'] 		= $this->storage[$selector][$ed_key]['text'][$gal_id];
				$this->storage[$selector][$ed_key]['text']['tmp2'] 		= $this->storage[$selector][$ed_key]['text'][$new_id];
				$this->storage[$selector][$ed_key]['text'][$new_id] 	= $this->storage[$selector][$ed_key]['text']['tmp'];
				$this->storage[$selector][$ed_key]['text'][$gal_id] 	= $this->storage[$selector][$ed_key]['text']['tmp2'];
				unset($this->storage[$selector][$ed_key]['text']['tmp']);
				unset($this->storage[$selector][$ed_key]['text']['tmp2']);
				}

			// то же самое для ссылки
			if (isset($this->storage[$selector][$ed_key]['link']))
				{
				$this->storage[$selector][$ed_key]['link']['tmp'] 		= $this->storage[$selector][$ed_key]['link'][$gal_id];
				$this->storage[$selector][$ed_key]['link']['tmp2'] 		= $this->storage[$selector][$ed_key]['link'][$new_id];
				$this->storage[$selector][$ed_key]['link'][$new_id] 	= $this->storage[$selector][$ed_key]['link']['tmp'];
				$this->storage[$selector][$ed_key]['link'][$gal_id] 	= $this->storage[$selector][$ed_key]['link']['tmp2'];
				unset($this->storage[$selector][$ed_key]['link']['tmp']);
				unset($this->storage[$selector][$ed_key]['link']['tmp2']);
				}

			$this->sort_gallery($selector, $ed_key);
			}
		}


	//..............................................................................
	// опускает запись изображения в галлерее вниз на одну позицию
	//..............................................................................
	public function gal_down($selector, $ed_key, $gal_id)
		{
		if ($gal_id<=count($this->storage[$selector][$ed_key]['value']))
			{
			$this->storage[$selector][$ed_key]['value']['tmp'] 		= $this->storage[$selector][$ed_key]['value'][$gal_id];
			$this->storage[$selector][$ed_key]['value'][$gal_id]		= $this->storage[$selector][$ed_key]['value'][($gal_id+1)];
			$this->storage[$selector][$ed_key]['value'][($gal_id+1)] 	= $this->storage[$selector][$ed_key]['value']['tmp'];
			unset($this->storage[$selector][$ed_key]['value']['tmp']);


			// то же самое для текста
			if (isset($this->storage[$selector][$ed_key]['text']))
				{
				$this->storage[$selector][$ed_key]['text']['tmp'] 		= $this->storage[$selector][$ed_key]['text'][$gal_id];
				$this->storage[$selector][$ed_key]['text'][$gal_id]		= $this->storage[$selector][$ed_key]['text'][($gal_id+1)];
				$this->storage[$selector][$ed_key]['text'][($gal_id+1)] 	= $this->storage[$selector][$ed_key]['text']['tmp'];
				unset($this->storage[$selector][$ed_key]['text']['tmp']);
				}

			// то же самое для ссылки
			if (isset($this->storage[$selector][$ed_key]['link']))
				{
				$this->storage[$selector][$ed_key]['link']['tmp'] 		= $this->storage[$selector][$ed_key]['link'][$gal_id];
				$this->storage[$selector][$ed_key]['link'][$gal_id]		= $this->storage[$selector][$ed_key]['link'][($gal_id+1)];
				$this->storage[$selector][$ed_key]['link'][($gal_id+1)] 	= $this->storage[$selector][$ed_key]['link']['tmp'];
				unset($this->storage[$selector][$ed_key]['link']['tmp']);
				}

			$this->sort_gallery($selector, $ed_key);			
			}
		}

	//..............................................................................
	// вводит ссылку в галлерее изображений
	//..............................................................................
	public function gal_link($selector, $ed_key, $gal_id, $value=NULL)
		{
		if (isset($this->storage[$selector][$ed_key]['link'][$gal_id]) and !is_array($this->storage[$selector][$ed_key]['link'][$gal_id]))
			{
			unset($this->storage[$selector][$ed_key]['link'][$gal_id]);
			}
		$this->storage[$selector][$ed_key]['link'][$gal_id][CMS_LANG] = $value;
		}


	//..............................................................................
	// вводит надпись в галлерее изображений
	//..............................................................................
	public function gal_text($selector, $ed_key, $gal_id, $value=NULL)
		{
		if (isset($this->storage[$selector][$ed_key]['text'][$gal_id]) and !is_array($this->storage[$selector][$ed_key]['text'][$gal_id]))
			{
			unset($this->storage[$selector][$ed_key]['text'][$gal_id]);
			}
		$this->storage[$selector][$ed_key]['text'][$gal_id][CMS_LANG] = $value;
		}


	//..............................................................................
	// сортирует индексы изображений в галлерее
	//..............................................................................
	public function sort_gallery($selector, $ed_key)
		{
		$res_arr_value = NULL;
		$res_arr_text = NULL;
		$res_arr_link = NULL;
		
		if (isset($this->storage[$selector][$ed_key]['value'])  and (count($this->storage[$selector][$ed_key]['value'])))
			{
			$i=0;
			foreach ($this->storage[$selector][$ed_key]['value'] as $key=>$row)
				{
				$res_arr_value[$i] = $row;
				// пренесем текст
				if (isset($this->storage[$selector][$ed_key]['text'][$key]))
					{
					$res_arr_text[$i] = $this->storage[$selector][$ed_key]['text'][$key];
					}
				// пренесем ccылку
				if (isset($this->storage[$selector][$ed_key]['link'][$key]))
					{
					$res_arr_link[$i] = $this->storage[$selector][$ed_key]['link'][$key];
					}

				$i++;
				}
			$this->storage[$selector][$ed_key]['value'] = $res_arr_value;
			$this->storage[$selector][$ed_key]['text'] = $res_arr_text;
			$this->storage[$selector][$ed_key]['link'] = $res_arr_link;
			unset($res_arr_value, $res_arr_text, $res_arr_link);
			}
		}
	//
	//
       	//------------------------------------------------------------------------------
	// функции для работы полем галереи КОНЕЦ
	//------------------------------------------------------------------------------



	//..............................................................................
	// переключает размер блока
	//..............................................................................
	public function switch_zoom($selector, $ed_key)
		{
		switch ($this->storage[$selector][$ed_key]['type'])
			{
			case 'gallery' : {
				$zoom =  @$this->storage[$selector][$ed_key]['zoom'];
				// если одно изображение - то ведем себя как ed_image
				if (count($this->storage[$selector][$ed_key]['value'])==1)
					{
					$zoom = ($zoom=='SMALL') ? 'FULL' : 'SMALL';
					} else	{
						// два и более - галлерея
						$zoom_arr = unserialize(get_const('GALLERY_ZOOMS'));
						$size_id = array_search($zoom, $zoom_arr);
						if ($size_id == count($zoom_arr)-1)
							{
							$zoom = $zoom_arr[0];
							} else $zoom = $zoom_arr[$size_id+1];
						}
					break;
					}

			case 'media' : {
				$this->storage[$selector][$ed_key]['avatar'] =  itEdMedia::get_media_preview($this->storage[$selector][$ed_key]['value']);
				}

			default : {
//				$link	= $this->storage[$selector][$ed_key]['value'];
				$zoom	= itEdMedia::get_embed_zoom($this->storage[$selector][$ed_key]);

				switch ($zoom)
					{
					case 'FULL' : {
						$zoom = 'SMALL';
						break;
					}

					case 'SMALL' : 
						{
					$zoom = 'FULL';
						break;
						}
					}
				break;
				}
			}

		$this->storage[$selector][$ed_key]['zoom'] = $zoom;
		}



	//..............................................................................
	// просмотр панели редактора
	//..............................................................................	
	public function _view()
		{
		global $editor_blocks;
		
		$result = NULL;
		// обработаем все поля редактора (компиляторы должны иметь события!)
		if (isset($this->storage[$this->selector]) and is_array($this->storage[$this->selector]))
			{
			$last_field = count($this->storage[$this->selector])-1;
			foreach ($this->storage[$this->selector] as $key=>$row)
				{
				if (!isset($row['type']) or ($row['type']==NULL))
					{
					if (function_exists('add_error_message'))
						add_error_message("<b>itEditor.class</b> Class (<b>$class</b>) not found in field #{$key} material #{$this->rec_id}.<br/><b>Converted to 'text'</b>");
					$row['type'] = 'text';
					}

				$class = $editor_blocks[$row['type']]['class'];
				if (class_exists($class))
					{ 
					// дополним все данные, чтобы передать в обработчик класса
					$row['table_name'] 	= $this->table_name;
					$row['rec_id'] 		= $this->rec_id;
					$row['name'] 		= $this->name;
					$row['field'] 		= $this->field;
					$row['column'] 		= $this->column;
					$row['root'] 		= $this->root;
					$row['lang'] 		= CMS_LANG;
					$row['selector']	= $this->selector;
					$row['last_field']	= $last_field;
					$row['ed_key'] 		= $key;
					$row['alt']		= CMS_NAME." | {$this->title}";

					// скомпилируем блок методом класса и вернем код
					$o_class = new $class($row);
					$result .= $o_class->_view();
					unset($o_class);
					}
				}
			}
		return $result;				
		}
		
	//..............................................................................
	// редактирование панели редактора
	//..............................................................................	
	public function _edit()
		{
		global $editor_blocks;
		$result = NULL;

		// проверка на существование первого поля
		if (!isset($this->storage[$this->selector][0]))
			{
			$this->storage[$this->selector][0] = [
				'type' 	=> 'text',
				'value'	=> [
					$this->selector => '',
					],
				];
			}
			
		// обработаем все поля редактора (компиляторы должны иметь события!)
		if (isset($this->storage[$this->selector]) AND is_array($this->storage[$this->selector]))
			{
			$last_field = count($this->storage[$this->selector])-1;
			foreach ($this->storage[$this->selector] as $key=>$row)
				{
				if (!isset($row['type']) or ($row['type']==NULL))
					{
					if (function_exists('add_error_message'))
						add_error_message("<b>itEditor.class</b> Class (<b>$class</b>) not found in field #{$key} material #{$this->rec_id}.<br/><b>Converted to 'text'</b>");
					$row['type'] = 'text';
					}

				$class = $editor_blocks[$row['type']]['class'];
				if (class_exists($class))
					{ 
					// дополним все данные, чтобы передать в обработчик класса
					$row['table_name'] 	= $this->table_name;
					$row['rec_id'] 		= $this->rec_id;
					$row['name'] 		= $this->name;
					$row['field'] 		= $this->field;
					$row['column'] 		= $this->column;					
					$row['root'] 		= $this->root;					
					$row['lang'] 		= CMS_LANG;
					$row['selector']	= $this->selector;
					$row['last_field']	= $last_field;
					$row['ed_key'] 		= $key;
					$row['alt']		= CMS_NAME." | {$this->title}";

					// скомпилируем блок методом класса и вернем код
					$o_class = new $class($row);
					$result .= $o_class->_edit();
					unset($o_class);
					}
				}
			}
		return $result;								
		}		
		
	//..............................................................................
	// по сути - это коомпилятор всех полей и связь с классами обработчиков
	//..............................................................................	
	public function go()
		{
		global $_USER;
		$this->code = $_USER->is_logged(itEditor::moderators())
			? $this->_edit()
			: $this->_view();			
		}


	//..............................................................................
	// проверяет массив количества медиа данных в теле редактора по селектору
	//..............................................................................
	static function count_media($row=NULL, $selector=NULL, $field=DEFAULT_CONTENT_FIELD)
		{
		$selector = is_null($selector) ? (($row['lang']=='ALL') ? 'ALL' : CMS_LANG) : $selector;			
		$result = [ 'gallery' => 0, 'media' => 0, 'video' => 0, 'audio' => 0, 'photo' => 0 ];

		if (isset($row[$field][$selector]) AND is_array($row[$field][$selector]))
		foreach ($row[$field][$selector] as $key=>$value)
			{
			if (isset($value['type']))
				{
				switch ($value['type'])
					{
					case 'media' :	{
						$result['media']++;
						switch (itEdMedia::get_embed_source($value['value']))
							{
							case 'TUBE' :
							case 'VIMEO' : {
								$result['video']++;
								break;
								}
							case 'SOUNDCLOUD' :
							case 'MIXCLOUD' : {
								$result['audio']++;
								break;
								}
							}
						break;
						}
					case 'gallery' : {
						$result['gallery']++;
						$result['photo'] += count($value['value']);						
						break;
						}
					}
				}
			}
		return $result;
		}

	//..............................................................................
	// разыменовывает поля редактора и возвращает html код для вставки поля
	//..............................................................................
	static function moderators()
		{
		global $_RIGHTS;
		return ready_val($_RIGHTS['EDIT'], NULL);
		}

	//..............................................................................
	// разыменовывает поля редактора и возвращает html код для вставки поля
	//..............................................................................	
	public function compile()
		{
		global $editor_blocks;
                $this->code = '';

//		if (!$this->is_loaded()) return;

		if (!isset($this->storage[$this->selector][0]))
			{
			$this->storage[$this->selector][0] = array (
				'type' 	=> 'text',
				'value'	=> [ $this->selector => ''],
				);
			}
		$this->code = $this->cache();
		
		// обработаем связанные новости
		if (!$this->no_related AND isset($this->storage[$this->rel_field]) AND is_array($this->storage[$this->rel_field]))
			{
			$func = "related_{$this->table_name}_row";
			if (function_exists($func))
				{
				$rows = NULL;
				foreach ($this->storage[$this->rel_field] as $key=>$row)
					{
					if (is_array($related_row = itMySQL::_get_rec_from_db($this->table_name, $row)))
						{
						$related_row['rec_id'] = $this->rec_id;
						$rows[] = 
							TAB."<div class='related'>".
							$func($related_row).
							(function_exists('get_related_x_event') ? get_related_x_event($related_row) :"").
							TAB."</div>";
						}
					}
				if (is_array($rows))
					$this->code .= 
						TAB."<div class='related_div'>".
						TAB."<div class='related_title'>".get_const('RELATED_CONTENTS_TITLE').TAB."</div>".
						implode('', $rows).
						TAB."</div>";
				} else add_error_message(get_const('FUNCTION_NOT_EXISTS')." <b>{$func}()</b>");
			}			
		// увеличим просмотр если флаг установлен
		if ($this->viewed)
			{
			$this->addview();
			}
		}

	//..............................................................................
	// увеличивает счетчик просмотра материала
	//..............................................................................
	public function addview()
		{
		if (isset($this->storage[DEFAULT_VIEW_FIELD]))
			{
			itMySQL::_update_value_db($this->table_name, $this->rec_id, $this->storage[DEFAULT_VIEW_FIELD]+1, DEFAULT_VIEW_FIELD);
			}
		}
	//..............................................................................
	// пакует данные для событий
	//..............................................................................	
	static function event_data($row)
		{
		$row['lang'] = get_const('CMS_LANG'); 
		return simple_encrypt(serialize($row));
		}

	//..............................................................................
	// пакует данные для событий
	//..............................................................................	
	static function _data($row)
		{
		return itEditor::event_data($row);
		}

	//..............................................................................
	// распаковывает данные для событий
	//..............................................................................	
	static function _redata($replace = false)
		{
		$data = isset($_REQUEST['data'])
			? @unserialize(simple_decrypt($_REQUEST['data'])) 
			: NULL;
		
		if (is_array($data)) {
			if ($replace) {
				$_REQUEST['data'] = $data;
				} else	{
					foreach ($data as $key=>$row) {
						if (!isset($_REQUEST[$key])) {
							$_REQUEST[$key] = $row;
							}
						}
					}
			}

		// пропишем команду, если она установлена
		if (!empty($op = isset($data['op'])
			? $data['op']
			: ( isset($_REQUEST['op'])
				? $_REQUEST['op']
				: NULL))) {
			$_REQUEST['op'] = $op;
			}

		return $data;
		}

	//..............................................................................
	// пакует данные для событий
	//..............................................................................	
	public function cache()
		{
		global $_USER;
		
		if ($this->async) return $this->container();
	
		if ($_USER->is_logged(itEditor::moderators()))
			{
//			$this->storage['html_xml'][CMS_LANG] = $this->_view();
			itMySQL::_update_value_db($this->table_name, $this->rec_id, NULL, 'html_xml');
						
			return $this->_edit();
			} else	if ($this->storage['status']=='PUBLISHED')
				{
				if (!isset($this->storage['html_xml'][CMS_LANG]) OR $this->no_cache)
					{
					$this->storage['html_xml'][CMS_LANG] = $this->_view();
					if (!$this->no_cache) $this->store();
					}
				return $this->storage['html_xml'][CMS_LANG];
				}
		}


	//..............................................................................
	// сохраняет данные редактора в поле записи таблицы
	//..............................................................................	
	public function store($rec_id=NULL)
		{
		if (($rec_id == NULL) or (intval($rec_id)<1))
			{
			$rec_id = $this->rec_id;
			}
		
		// сбросим кєш
		if (isset($this->record['html_xml']))
			{
			if (isset($this->record['html_xml'][CMS_LANG]))
				{
				unset($this->record['html_xml'][CMS_LANG]);
				}
			if (is_array($this->record['html_xml']) AND (count($this->record['html_xml'])==0))
				{
				$this->record['html_xml'] = NULL;
				}
			}

		// возвращаемся к основой записи!	
//		$values_arr = $this->data;
		
		if (isset($this->data['id']))
			unset($this->data['id']);
		itMySQL::_update_db_rec($this->table_name, $rec_id, $this->data);
		}

	//..............................................................................
	// возвращает код редактора
	//..............................................................................
	public function code()
		{
		return $this->code;
		}

	//..............................................................................
	// обработка стандартных событий в обработчике
	//..............................................................................
	static function events($url='/', $path=UPLOADS_ROOT)
		{
		return editor_events($url, $path);
		}

	//..............................................................................
	// добавляет id связанной новости в поле редактора
	//..............................................................................
	static function _related($options)
		{
		$row = itMySQL::_get_rec_from_db($options['table_name'], $options['rec_id']);
		if (isset($row[$options['field']]))
			{
			if (is_string($row[$options['field']]))
				{
				$row[$options['field']]=[];
				}
			$row[$options['field']][] = $options['value'];
			itMySQL::_update_value_db($options['table_name'], $options['rec_id'], array_values(array_unique($row[$options['field']],SORT_NUMERIC)), $options['field']);
			} else add_error_message('NO_RELATED_FIELD_DETECTED');
		}
	//..............................................................................
	// удаляет id связанной новости в поле редактора
	//..............................................................................
	static function _related_x($options)
		{
		$row = itMySQL::_get_rec_from_db($options['table_name'], $options['rec_id']);
		if (isset($row[$options['field']]))
			{
			if (($key = array_search($options['content_id'], $row[$options['field']])) !== false)
				{
				unset($row[$options['field']][$key]);
				}
			
			itMySQL::_update_value_db($options['table_name'], $options['rec_id'], array_values(array_unique($row[$options['field']],SORT_NUMERIC)), $options['field']);
			} else add_error_message('NO_RELATED_FIELD_DETECTED');
		}


	//..............................................................................
	// создает редактируемый контейнер для загрузки по ajax
	//..............................................................................
	public function container($options=NULL)
		{
		global $_USER;
		
		$this->data['state'] 		= isset($options['state']) ? $options['state'] : DEFAULT_EDSTATE;
		$this->data['container_id']	= itEditor::_container_id((array)$this);
		
		$data = itEditor::event_data([
			'table_name'	=> $this->table_name,
			'rec_id'	=> $this->rec_id,
			'field'		=> $this->field,
			'column'	=> $this->column,
			'root' 		=> $this->root,
			'no_date'	=> $this->no_date,
			'no_lang'	=> $this->no_lang,
			'no_moderate'	=> $this->no_moderate,
			'no_avatar'	=> $this->no_avatar,
			'viewed'	=> $this->viewed,
			'title_class'	=> $this->title_class,										
			'rel_field'	=> $this->rel_field,
			'no_related'	=> $this->no_related,
			'async'		=> $this->async,
			'no_cache'	=> $this->no_cache,
			'edclass'	=> $this->edclass,
			'container_id'	=> $this->data['container_id'],
			'state'		=> $this->data['state'],
			]);
			

		return $_USER->is_logged(itEditor::moderators())
			? 
				TAB."<div class='ed_container {$this->edclass}' id='".itEditor::_container_id((array)$this)."' rel='{$data}'>".
				( ($this->data['state']=='view')
					? $this->_view()
					: $this->_edit() ).
				get_ed_async_event($this->data).
				TAB."</div>".
				""
			: 	(($this->record['status']=='PUBLISHED')  ? $this->_view() : NULL );
		}

	//..............................................................................
	// функция выборки данных из сохраненных полей редактора
	//..............................................................................
	static function _repack_data($data)
		{
		return [
			'table_name'	=> $data['table_name'],
			'rec_id'	=> $data['rec_id'],
			'field'		=> $data['field'],
			'column'	=> $data['column'],
			'root'		=> $data['root'],
			];		
		}

	//..............................................................................
	// возвращает имя контейнера
	//..............................................................................
	static function _container_id($data)
		{
		return
			crc32(
			(isset($data['table_name']) 	? "-{$data['table_name']}" 	: NULL).
			(isset($data['rec_id']) 	? "-{$data['rec_id']}" 		: NULL).
			(isset($data['column']) 	? "-{$data['column']}" 		: NULL).
			(isset($data['root']) 		? "-{$data['root']}" 		: NULL).
			"")."-container";
		}
	
	//..............................................................................
	// собирает данные со всех языко в один
	//..............................................................................
	static function _consolidate($storage, $sel_lang=CMS_LANG)
		{
		global $lang_cat;
		$result = NULL;
		
		if (isset($storage[$sel_lang]) AND is_array($storage[$sel_lang]))
		foreach($storage[$sel_lang] as $key=>$row)
			{
			switch (ready_val($row['type']))
				{
				case NULL : {} break;
				case 'text' :	{
					$res_text = $row;
					foreach ($lang_cat as $lang_key=>$lang_row)
						{
						if (ready_val($lang_row['allowed']) AND ($lang_key!=$sel_lang) AND @isset($storage[$lang_key][$key]['value'][$lang_key]))
							{
							$res_text['value'][$lang_key] = $storage[$lang_key][$key]['value'][$lang_key];
							}
						}
					$result[] = $res_text;
					break;
					}
				default : {
					$result[] = $row;
					break;
					}
				}
			}
		return array_values($result);
		}
	}
?>