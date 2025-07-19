<?
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