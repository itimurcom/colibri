<?php
// ================ CRC ================
// version: 1.35.02
// hash: b59f8c62a2ffb97d3b0cb5303327d4397b212b75de0ac0f5b638c852da39b512
// date: 09 September 2019  7:09
// ================ CRC ================
//..............................................................................
// возвращает массовую замену в строке по ключам
//..............................................................................
function mstr_replace($options=NULL, $var=NULL)
	{
	return is_array($options) ? str_replace(array_keys($options), array_values($options), $var) : NULL;
	}
?>