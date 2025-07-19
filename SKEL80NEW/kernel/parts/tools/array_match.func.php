<?
//..............................................................................
// фильтрует массив по строке поиска
//..............................................................................
function array_match($data, $term)
	{
	$term = strtolower($term);
	return array_filter($data, function($value, $key) use ($term) {
		return stristr(strtolower($value), $term) || stristr(strtolower($key), $term);
		}, ARRAY_FILTER_USE_BOTH);		
	}
?>