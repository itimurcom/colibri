<?
//..............................................................................
// рекурсивная проверка массива данных
//..............................................................................
function array_diff_assoc_recursive($array1, $array2)
{
    foreach($array1 as $key => $value){

        if(is_array($value)){
            if(!isset($array2[$key]))
            {
                $difference[$key] = $value;
            }
            elseif(!is_array($array2[$key]))
            {
                $difference[$key] = $value;
            }
            else
            {
                $new_diff = array_diff_assoc_recursive($value, $array2[$key]);
                if($new_diff != FALSE)
                {
                    $difference[$key] = $new_diff;
                }
            }
        }
        elseif((!isset($array2[$key]) || $array2[$key] != $value) && !($array2[$key]===null && $value===null))
        {
            $difference[$key] = $value;
        }
    }
    return !isset($difference) ? NULL : $difference;
}
?>