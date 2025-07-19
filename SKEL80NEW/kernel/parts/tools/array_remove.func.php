<?
//..............................................................................
// возвращает масив без указаынных элментов
//..............................................................................
function array_remove($array,$value,$key=NULL)
	{
	if (is_null($key))
		{
		foreach (array_keys($array, $value) as $key)
			{
			unset($array[$key]);
			}  
		} else	{
			foreach($array as $subKey => $subArray)
				{
				if ($subArray[$key] == $value)
					{
					unset($array[$subKey]);
          				}
     				}
     			}
	return $array;
	}
    ?>