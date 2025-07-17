<?php
// ================ CRC ================
// version: 1.35.02
// hash: 2a0ae68af9c00f201ca0db82c6c6dfd5ca0f6afca03f17cbac318bf86fb561f8
// date: 09 September 2019  7:09
// ================ CRC ================
//..............................................................................
// возвращает capitalized (большие заглавные буквы)
//..............................................................................
function mb_capitalize($str, $encoding = 'UTF-8')
	{
    	return mb_convert_case($str, MB_CASE_TITLE, $encoding);
	}
?>