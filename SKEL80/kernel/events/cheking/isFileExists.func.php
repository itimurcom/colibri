<?php
// ================ CRC ================
// version: 1.35.02
// hash: aa546b77c96944393d543d1ae13a4bd6810443cedabed645f5bae262212a3faf
// date: 09 September 2019  7:09
// ================ CRC ================
//..............................................................................
// проверяет наличие файла на любом указанном месте
//..............................................................................
function isFileExists($filePath)
	{
		if (@file_exists(realpath($filePath)))
			return true;

		$headers = @get_headers($filePath);
		if(preg_match("|200|", $headers[0]))
			return true;

		if (@fopen($filePath, "r"))
			return true;

		if(!preg_match("|^http(s)?|", $filePath))
			return false;

		return false;
	}
?>