<?php
/* подготовка массивов для работы событий */
if (!isset($_SESSION['wishlist']))
	{
	$_SESSION['wishlist'] = [];
	}

global $prepared_arr;

//..............................................................................
// готовит необходимые массивы один раз
//..............................................................................
function prepare_global_arrays()
	{
	global $_USER, $prepared_arr;
	global 
		$cats_gr,
		$statuses,
		$sex_arr,
		$lang_cat,
		$groups_of_user,
		$_RIGHTS,
		$cat_cat;

	//------------------------------------------------------------------------------
	// массивы для админимтратора
	//------------------------------------------------------------------------------

	if ($_USER->is_logged($_RIGHTS['EDIT']))
		{
		$db = new itMySQL();

		//..............................................................................
		// массив статей контента для блоков
		//..............................................................................
		$table_name = get_const('DEFAULT_CONTENT_TABLE');
		$request = $db->request("SELECT * FROM {$db->db_prefix}{$table_name} WHERE `category_id`='".get_const('GR_BLOCK')."' ORDER BY `id` ASC");
		$prepared_arr['contents'][-1] = array (
			'title' => get_const('HIDE_BLOCK'),
			'value' => NULL
			);

		if (is_array($request))
			foreach ($request as $key=>$row)
				{
				$prepared_arr['contents'][] = [
					'title' => "#".$row['id']." ".get_field_by_lang($row['title_xml']),
					'value' => $row['id']
					];
				}

		//..............................................................................
		// массив групп категорий
		//..............................................................................
		//..............................................................................
		// массив категорий для контента
		//..............................................................................
		foreach ($cats_gr as $gr_key=>$gr_row)
			{
			if ($gr_row['show']==1)
				{
				$prepared_arr['cats'][$gr_row['id']] = [
				'title' => get_const($cats_gr[$gr_key]['title']),
				'value'	=> $gr_row['id'],
				]; 
			}
		}

		unset($db);
		}


	//------------------------------------------------------------------------------
	// массивы для зарегистрированных пользователей
	//------------------------------------------------------------------------------

	if ($_USER->is_logged('ANY'))
		{
		$prepared_arr['wishlist'] = load_wishlist($_USER->data['id']);
		//..............................................................................
		// массив языков для выбора
		//..............................................................................
		if (is_array($lang_cat))
			{
			$prepared_arr['lang']['ALL'] = [
				'title' => get_const('ALL_LANG_TITLE'),
				'value'	=> 'ALL',
				]; 

			foreach ($lang_cat as $key=>$row)
				{
				if ($row['allowed']==1)
					{
					$prepared_arr['lang'][$key] = [
						'title' => get_const($row['name_orig']),
						'value'	=> $key,
						]; 
					}
				}
			$prepared_arr['lang'][NULL] = [
				'title' => get_const('SEPARETED_LANG_TITLE'),
				'value'	=> NULL,
				]; 

			}

		//..............................................................................
		// массив статусов
		//..............................................................................
		foreach ($statuses as $gr_key=>$gr_row)
			{
			if ($gr_row['show']==1)
				{
				$prepared_arr['statuses'][$gr_key] = [
					'title' => get_const($statuses[$gr_key]['title']),
					'value'	=> $gr_key,
					]; 
				}
			}

		//------------------------------------------------------------------------------
		// массивы для зарегистрированных пользователей
		//------------------------------------------------------------------------------
		if ($_USER->is_logged() and ($_REQUEST['view']=='user') and ($_REQUEST['rec_id']>0))
			{
			$id_of_user = $_REQUEST['rec_id'];			
			} else $id_of_user =$_USER->id();


		//..............................................................................
		// массив группы пользователя
		//..............................................................................
		if (is_array($groups_of_user))
		foreach ($groups_of_user as $key=>$row)
			{
			$prepared_arr['group'][$key] = [
				'title' => get_const($row['title']),
				'value'	=> $row['value'],
				]; 
			}

		}
	
	global $preview_count;
	$preview_count=rand_id();
	
	//..............................................................................
	// массив выбора пола пользователя
	//..............................................................................
	foreach ($sex_arr as $key=>$row)
		{
		$prepared_arr['sex'][$key] = [
			'title' => get_const($row['title']),
			'value'	=> $row['value'],
			]; 
		}

	//..............................................................................
	// массив выбора категории товара
	//..............................................................................
	foreach ($cat_cat as $key=>$row)
		{
		if ($row['show'])
		$prepared_arr['items'][$key] = [
			'title' => get_const($row['title'])."( {$row['letter']} )",
			'value'	=> $row['id'],
			]; 
		}
	}
?>