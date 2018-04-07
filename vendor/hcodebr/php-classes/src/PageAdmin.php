<?php

namespace Hcode;

use Rain\Tpl;

/**
* Controla os Templates
*/
class PageAdmin extends Page
{
	
	function __construct($opts = array(), $tpl_dir = "/views/admin/")
	{
		parent::__construct($opts, $tpl_dir);
	}

}

?>