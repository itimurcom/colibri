<?php

class itForm extends itForm2
	{
	public function __construct($var=NULL)
		{
		global $_CONTENT;
		if (!isset($_CONTENT['log'])) $_CONTENT['log'] = NULL;
		$_CONTENT['log'].= "<script> console.log('form2', '".json_encode($var)."');</script>";
		parent::__construct($var);
		}
	}

?>
