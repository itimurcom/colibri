<?php
if (!isset($_SESSION['wishlist']) OR !is_array($_SESSION['wishlist']))
	{
	$_SESSION['wishlist'] = [];
	}

global $prepared_arr;

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

	if (isset($_USER) AND is_object($_USER) AND method_exists($_USER, 'is_logged') AND $_USER->is_logged(ready_value($_RIGHTS['EDIT'] ?? NULL, 'EDIT')))
		{
		$db = new itMySQL();

		$table_name = get_const('DEFAULT_CONTENT_TABLE');
		$request = $db->request("SELECT * FROM {$db->db_prefix}{$table_name} WHERE `category_id`='".get_const('GR_BLOCK')."' ORDER BY `id` ASC");
		$prepared_arr['contents'][-1] = array (
			'title' => get_const('HIDE_BLOCK'),
			'value' => NULL
			);

		if (is_array($request))
			foreach ($request as $key=>$row)
				{
				if (!is_array($row)) continue;
				$row_id = ready_value($row['id'] ?? NULL, 0);
				$prepared_arr['contents'][] = [
					'title' => "#".$row_id." ".get_field_by_lang(ready_value($row['title_xml'] ?? NULL, [])),
					'value' => $row_id
					];
				}

		if (is_array($cats_gr))
		foreach ($cats_gr as $gr_key=>$gr_row)
			{
			if (!is_array($gr_row) OR ready_value($gr_row['show'] ?? NULL, 0)!=1) continue;
			$gr_id = ready_value($gr_row['id'] ?? NULL, NULL);
			if (is_null($gr_id)) continue;
			$prepared_arr['cats'][$gr_id] = [
				'title' => get_const(ready_value($gr_row['title'] ?? NULL, '')),
				'value'	=> $gr_id,
				];
			}

		unset($db);
		}

	if (isset($_USER) AND is_object($_USER) AND method_exists($_USER, 'is_logged') AND $_USER->is_logged('ANY'))
		{
		$user_id = ready_value($_USER->data['id'] ?? NULL, method_exists($_USER, 'id') ? $_USER->id() : 0);
		$prepared_arr['wishlist'] = load_wishlist($user_id);
		if (is_array($lang_cat))
			{
			$prepared_arr['lang']['ALL'] = [
				'title' => get_const('ALL_LANG_TITLE'),
				'value'	=> 'ALL',
				];

			foreach ($lang_cat as $key=>$row)
				{
				if (!is_array($row) OR ready_value($row['allowed'] ?? NULL, 0)!=1) continue;
				$prepared_arr['lang'][$key] = [
					'title' => get_const(ready_value($row['name_orig'] ?? NULL, $key)),
					'value'	=> $key,
					];
				}
			$prepared_arr['lang'][NULL] = [
				'title' => get_const('SEPARETED_LANG_TITLE'),
				'value'	=> NULL,
				];

			}

		if (is_array($statuses))
		foreach ($statuses as $gr_key=>$gr_row)
			{
			if (!is_array($gr_row) OR ready_value($gr_row['show'] ?? NULL, 0)!=1) continue;
			$prepared_arr['statuses'][$gr_key] = [
				'title' => get_const(ready_value($gr_row['title'] ?? NULL, $gr_key)),
				'value'	=> $gr_key,
				];
			}

		$request_view = ready_value($_REQUEST['view'] ?? NULL, '');
		$request_rec_id = (int)ready_value($_REQUEST['rec_id'] ?? NULL, 0);
		if ($_USER->is_logged() and ($request_view=='user') and ($request_rec_id>0))
			{
			$id_of_user = $request_rec_id;
			} else $id_of_user = $_USER->id();

		if (is_array($groups_of_user))
		foreach ($groups_of_user as $key=>$row)
			{
			if (!is_array($row)) continue;
			$prepared_arr['group'][$key] = [
				'title' => get_const(ready_value($row['title'] ?? NULL, $key)),
				'value'	=> ready_value($row['value'] ?? NULL, $key),
				];
			}

		}

	global $preview_count;
	$preview_count=rand_id();

	if (is_array($sex_arr))
	foreach ($sex_arr as $key=>$row)
		{
		if (!is_array($row)) continue;
		$prepared_arr['sex'][$key] = [
			'title' => get_const(ready_value($row['title'] ?? NULL, $key)),
			'value'	=> ready_value($row['value'] ?? NULL, $key),
			];
		}

	if (is_array($cat_cat))
	foreach ($cat_cat as $key=>$row)
		{
		if (!is_array($row) OR !ready_value($row['show'] ?? NULL, false)) continue;
		$row_id = ready_value($row['id'] ?? NULL, $key);
		$prepared_arr['items'][$key] = [
			'title' => get_const(ready_value($row['title'] ?? NULL, $key))."( ".ready_value($row['letter'] ?? NULL, '')." )",
			'value'	=> $row_id,
			];
		}
	}
?>
