<?php
// ================ CRC ================
// version: 1.15.04
// hash: dd94b79f734c2d060448f23e1ef2dfeca3300e412bb9eea22bb9cccb3cde8028
// date: 30 April 2021 16:04
// ================ CRC ================
//-------------------------------------------------------------------------------------
// itEdMedia : класс обработки текстового блока редактора
//-------------------------------------------------------------------------------------
class itEdMedia
	{
	public $data, $code;
	//..............................................................................
	// конструктор класса - создает привязку к блоку медиа
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
		$this->name 		= "{$this->data['name']}-media-{$this->data['ed_key']}";
		}

	//..............................................................................
	// определеяет тип видео по ссылке на источник
	//..............................................................................
	static function get_embed_source($link=NULL)
		{
		if ((strpos($link,'youtube')!==false) or (strpos($link,'youtu.be')!==false))
			{
			return 'TUBE';
			}

		if (strpos($link,'vimeo')!==false)
			{
			return 'VIMEO';
			}

		if (strpos($link,'soundcloud')!==false)
			{
			return 'SOUNDCLOUD';
			}

		if (strpos($link,'mixcloud')!==false)
			{
			return 'MIXCLOUD';
			}

		return 'NONE';
		}

	//..............................................................................
	// возвращает текущее значение zoom или значение по умолчанию
	//..............................................................................
	static function get_embed_zoom($value_rec='')
		{
		if (!isset($value_rec['zoom']) or (($value_rec['zoom']!='FULL') and ($value_rec['zoom']!='SMALL')))
			{
			$media = itEdMedia::get_embed_source($value_rec['value']);
			eval ('$zoom = '.mb_strtoupper($media,'UTF-8').'_ZOOM;');
			return $zoom;	
			} else return $value_rec['zoom'];
		}

	//..............................................................................
	// возвращает линк на картикку изображения аватарки для медиа полей
	//..............................................................................
	static function get_media_preview($link=NULL)
		{
		switch (itEdMedia::get_embed_source($link))
			{
			case 'TUBE' : 
				{
	        		// чистим линк для YOUTUBE фрейма
				$query_str = itEdMedia::get_tube_id($link);
				$upload_file = clear_file_name("youtube{$query_str}.jpg");
				@curl_copy("http://img.youtube.com/vi/$query_str/0.jpg", $upload_file);
				$result = $upload_file;
				break;
				}
			case 'VIMEO' :
				{
				@$json = file_get_contents("http://vimeo.com/api/oembed.json?url=$link");
				$vimeo_data = json_decode($json, true);
				$result = $vimeo_data['thumbnail_url'];
				break;
					}
			case 'SOUNDCLOUD' :
				{
				$sound_rec = itEdMedia::get_soundcloud_rec($link);
				$result =  str_replace ('large', 'original', $sound_rec['artwork_url']);
				break;
				}
			case 'MIXCLOUD' : 
				{
                                
				@$json = file_get_contents("http://www.mixcloud.com/oembed/?url=$link&format=json");
				$mixcloud_data = json_decode($json, true);
				$result = "{$mixcloud_data['image']}";
				break;
				}
			default : $result ='';
			}
		return $result;
		}

	//..............................................................................
	// возвращает массив данных, получаемых от SOUNDCLOUD
	//..............................................................................
	static function get_soundcloud_rec($link=NULL)
		{
		if ($link==NULL) return;

		$query = http_get("http://api.soundcloud.com/resolve.json",['url'=>$link, 'client_id'=>SOUNCLOUD_CLIENT]);
		$ctx = stream_context_create(array('http' => array('method' => 'GET', 'timeout' => 1)));
		$json = @file_get_contents($query['location'],0, $ctx);
	 	$sound_rec = json_decode($json,TRUE);
		return $sound_rec;
		}

	//..............................................................................
	// проверяет есть ли в новости медиа
	//..............................................................................
	static function is_ed_media($row=NULL, $selector=CMS_LANG, $field=DEFAULT_CONTENT_FIELD)
		{
		$result = NULL;
		if (isset($row[$field][$selector]) and is_array($row[$field][$selector]))
		foreach ($row[$field][$selector] as $key=>$value)
			{
			if (isset($value['type']) and ($value['type']=='media'))
				{
				switch (itEdMedia::get_embed_source($value['value']))
					{
					case 'TUBE' :
					case 'VIMEO' : {
						$result = 'video';
						break;
						}
					case 'SOUNDCLOUD' :
					case 'MIXCLOUD' : {
						$result = 'audio';
						break;
						}
					}
				break;
				}
			}
		return $result;
		}

	//..............................................................................
	// чистим линк YouTube
	//..............................................................................
	static function get_tube_id($link)
		{
		$v_path = @parse_path($link);
		if ((isset($v_path['query_vars']['v'])) and ($v_path['query_vars']['v']!=''))
			{
			$link = $v_path['query_vars']['v'];
			} else @$link = $v_path['call_parts'][3];
		return $link;
		}
		
	//..............................................................................
	// код фрейма выбранного типа меди данных
	//..............................................................................
	public function _media()
		{
		$result = NULL;
	 	$link 	= $this->data['value'];
	 	$zoom	= itEdMedia::get_embed_zoom($this->data);

		switch (itEdMedia::get_embed_source(strtolower($link)))
			{
			case 'SOUNDCLOUD' : {
 	        	        $sound_rec = itEdMedia::get_soundcloud_rec($link);
				$link =  $sound_rec['uri'];
				if (strpos($link,'/users/')>0)
					{
					$page_of_user = true;
					} else $page_of_user = false;

				// готовим код для SOUNDCLOUD фрейма
				switch ($zoom)
					{
					case 'SMALL' : {
						$result = TAB."<iframe scrolling='no' width='100%' frameborder='0' src='https://w.soundcloud.com/player/?url={$link}&amp;auto_play=false&amp;hide_related=false&amp;show_artwork=true&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false'></iframe>";	
						break;
						}
					case 'FULL' : {					
						$result = TAB."<div class='adaptive_container'>".
							TAB."<iframe scrolling='no' width='100%' frameborder='0' src='https://w.soundcloud.com/player/?url={$link}&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true'></iframe>".
							TAB."</div>";
						break;
						}
					}

				break;
				}

			case 'MIXCLOUD' : {
				// готовим код для MIXCLOUD фрейма
				switch ($zoom)
					{
					case 'SMALL' : {
						$result = TAB."\t<iframe width='100%' height='60' src='//www.mixcloud.com/widget/iframe/?feed={$link}&amp;mini=1&amp;embed_uuid=3e9c1178-f21f-4703-85f1-3e260de8298c&amp;replace=0&amp;hide_cover=".get_const('DEFAULT_MIXCLOUD_LIGHT')."&amp;light=0&amp;embed_type=widget_standard&amp;hide_tracklist=1' frameborder='0'></iframe>";
						break;
						}
					case 'FULL' : {
						$result = TAB."\t<iframe width='100%' height='180' src='//www.mixcloud.com/widget/iframe/?feed={$link}&amp;embed_uuid=3e9c1178-f21f-4703-85f1-3e260de8298c&amp;replace=0&amp;hide_cover=1&amp;light=".get_const('DEFAULT_MIXCLOUD_LIGHT')."&amp;embed_type=widget_standard&amp;hide_tracklist=1' frameborder='0'></iframe>";
						break;
						}
					}
				break;
				}

			case 'TUBE' : {
        			// чистим линк для YOUTUBE фрейма
				$link = itEdMedia::get_tube_id($link);
				// готовим код для YOUTUBE фрейма
				switch ($zoom)
					{
					case 'SMALL' : {
						$result = TAB."<iframe src='//www.youtube.com/embed/{$link}?color=white&modestbranding=1&theme=light&hl=".CMS_LANG."' width='100%' height='240px' frameborder='0' allowfullscreen></iframe>";
						break;
						}
					case 'FULL' : 
					default : {					
						$result = TAB."<div class='adaptive_container'>".
							TAB."<iframe src='//www.youtube.com/embed/{$link}?color=white&modestbranding=1&theme=light&hl=".CMS_LANG."' frameborder='0' allowfullscreen></iframe>".
							TAB."</div>";
						break;
						}
					}
				break;
				}

			case 'VIMEO' : {
        			// чистим линк для VIMEO фрейма				
				@$json = file_get_contents("http://vimeo.com/api/oembed.json?url={$link}");
				$vimeo_data = json_decode($json, true);
				$link = $vimeo_data['video_id'];

				// готовим код для VIMEO фрейма
				switch ($zoom)
					{
					case 'SMALL' : {
						$result = TAB."<iframe src='//player.vimeo.com/video/{$link}?color=".VIMEO_COLOR."' width='100%' height='320px' frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
						break;
						}
					case 'FULL' : 
					default : {				
						$result =
							TAB."<div class='adaptive_container'>".
							TAB."<iframe src='//player.vimeo.com/video/{$link}?color=".VIMEO_COLOR."' frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>".
							TAB."</div>";
						break;
						}
					}
				break;
				}
			}
		return $result;			
		}

	//..............................................................................
	// разыменовывает поле 'text'
	//..............................................................................	
	public function compile()
		{
/*		
		global $editor_blocks, $_USER, $ed_count;
		$code = '';

	 	$link 	= $this->data['value'];
	 	$zoom	= itEdMedia::get_embed_zoom($this->data);

		switch (itEdMedia::get_embed_source(strtolower($link)))
			{
			case 'SOUNDCLOUD' : {
 	        	        $sound_rec = itEdMedia::get_soundcloud_rec($link);
				$link =  $sound_rec['uri'];
				if (strpos($link,'/users/')>0)
					{
					$page_of_user = true;
					} else $page_of_user = false;

				// готовим код для SOUNDCLOUD фрейма
				switch ($zoom)
					{
					case 'SMALL' : {
						$code = TAB."\t<iframe scrolling='no' width='100%' frameborder='0' src='https://w.soundcloud.com/player/?url=$link&amp;auto_play=false&amp;hide_related=false&amp;show_artwork=true&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false'></iframe>";	
						break;
						}
					case 'FULL' : {					
						$code = TAB."\t<div class='adaptive_container'><iframe scrolling='no' width='100%' frameborder='0' src='https://w.soundcloud.com/player/?url=$link&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true'></iframe></div>";
						break;
						}
					}

				break;
				}

			case 'MIXCLOUD' : {
				// готовим код для MIXCLOUD фрейма
				switch ($zoom)
					{
					case 'SMALL' : {
						$code .= TAB."\t<iframe width='100%' height='60' src='//www.mixcloud.com/widget/iframe/?feed=$link&amp;mini=1&amp;embed_uuid=3e9c1178-f21f-4703-85f1-3e260de8298c&amp;replace=0&amp;hide_cover=".get_const('DEFAULT_MIXCLOUD_LIGHT')."&amp;light=0&amp;embed_type=widget_standard&amp;hide_tracklist=1' frameborder='0'></iframe>";
						break;
						}
					case 'FULL' : {
						$code .= TAB."\t<iframe width='100%' height='180' src='//www.mixcloud.com/widget/iframe/?feed=$link&amp;embed_uuid=3e9c1178-f21f-4703-85f1-3e260de8298c&amp;replace=0&amp;hide_cover=1&amp;light=".get_const('DEFAULT_MIXCLOUD_LIGHT')."&amp;embed_type=widget_standard&amp;hide_tracklist=1' frameborder='0'></iframe>";
						break;
						}
					}
				break;
				}

			case 'TUBE' : {
        			// чистим линк для YOUTUBE фрейма
				$link = itEdMedia::get_tube_id($link);
				// готовим код для YOUTUBE фрейма
				switch ($zoom)
					{
					case 'SMALL' : {
						$code = TAB."\t<iframe src='//www.youtube.com/embed/$link?color=white&modestbranding=1&theme=light&hl=".CMS_LANG."' width='100%' height='240px' frameborder='0' allowfullscreen></iframe>";
						break;
						}
					case 'FULL' : 
					default : {					
						$code = TAB."\t<div class='adaptive_container'><iframe src='//www.youtube.com/embed/$link?color=white&modestbranding=1&theme=light&hl=".CMS_LANG."' frameborder='0' allowfullscreen></iframe></div>";
						break;
						}
					}
				break;
				}

			case 'VIMEO' : {
        			// чистим линк для VIMEO фрейма				
				@$json = file_get_contents("http://vimeo.com/api/oembed.json?url=$link");
				$vimeo_data = json_decode($json, true);
				$link = $vimeo_data['video_id'];

				// готовим код для VIMEO фрейма
				switch ($zoom)
					{
					case 'SMALL' : {
						$code = TAB."\t<iframe src='//player.vimeo.com/video/$link?color=".VIMEO_COLOR."' width='100%' height='320px' frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
						break;
						}
					case 'FULL' : {					
						$code = TAB."\t<iframe src='//player.vimeo.com/video/$link?color=".VIMEO_COLOR."' frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
						$code = TAB."\t<div class='adaptive_container'>$code";
						$code .= TAB."\t</div>";
						break;
						}
					}
				break;
				}
			}

//		$code = $this->_media();

		$result = str_replace('[VALUE]',$code, $editor_blocks['media']['code']);
		$result = str_replace('[ID]', $this->name, $result);

		if ($_USER->is_logged(itEditor::moderators()))
			{
			$media_butons = get_ed_buttons_set($this->data).get_ed_change_event($this->data);
			$result = str_replace('[BUTTONS]',$media_butons, $result);
			$result = str_replace('[EDIT]', ' edit', $result);
			} else	{
				$result = str_replace('[BUTTONS]','', $result);
				$result = str_replace('[EDIT]', '', $result);
				}
		$this->code = $result;
*/		
		
		global $_USER;
		$this->code = $_USER->is_logged(itEditor::moderators())
			? $this->_edit() 
			: $this->_view();
		}		

	//..............................................................................
	// просмотр медиа блока
	//..............................................................................
	public function _view()
		{
		global $editor_blocks;
		
		return mstr_replace([
			'[VALUE]'	=> $this->_media(),
			'[ID]'		=> $this->name,
			'[BUTTONS]'	=> '',
			'[EDIT]'	=> '',
			], TAB.$editor_blocks['media']['code']);
		}
		
	//..............................................................................
	// редактирование медиа блока
	//..............................................................................
	public function _edit()
		{
		global $editor_blocks;
		
		return mstr_replace([
			'[VALUE]'	=> $this->_media(),
			'[ID]'		=> $this->name,
			'[BUTTONS]'	=> 
				get_ed_buttons_set($this->data).
				get_ed_change_event($this->data),
			'[EDIT]'	=> ' edit',
			], TAB.$editor_blocks['media']['code']);
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