<?
function check_skeleton_basic_tables()
	{
	if (is_array($dir_arr = glob(SKELETON_CORE_PATH."sql/*.php")))
		{
		foreach ($dir_arr as $table)
			{
			if (!itMySQL::_exists($table_name = str_replace('.php', '', basename($table))))
				{
				echo "<br/> Creating table <b>{$table_name}</b>";
				include $table;
				} else	{
					echo "<br/>..table <b>{$table_name}</b> is preset...Ok";
					}
			}
		}	
	}
?>