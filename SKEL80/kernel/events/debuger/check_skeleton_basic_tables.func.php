<?php
// ================ CRC ================
// version: 1.38.02
// hash: fd5b7687eed8f7ad1afeb2485d76e9b521ca3dcd69f1dab8e35be3d8c68f72da
// date: 17 September 2019 17:56
// ================ CRC ================
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