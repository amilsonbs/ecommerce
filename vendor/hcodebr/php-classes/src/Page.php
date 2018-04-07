<?php

namespace Hcode;

use Rain\Tpl;

/**
* Controla os Templates
*/
class Page
{
	
	private $tpl;
	private $options = [];
	private $defaults = array(
		'data'=>[]
	);

	function __construct($opts = array())
	{
		$this->options = array_merge($this->defaults, $opts);

		$config = array(
			"tpl_dir"       => $_SERVER["DOCUMENT_ROOT"] . "/views/",
			"cache_dir"     => $_SERVER["DOCUMENT_ROOT"] . "/views-cache/",
			"debug"         => false // set to false to improve the speed
		);

		Tpl::configure( $config );

		$this->tpl = new Tpl;

		$this->setData($this->options['data']);

		$this->tpl->draw("header");
	}

	private function setData($data = array())
	{
		foreach ($data as $key => $value) {
			$this->tpl->assign($key, $value);
		}
	}

	function setTpl($name, $data = array(), $returnHTML = false)
	{
		$this->setData($data);

		return $this->tpl->draw($name, $returnHTML);


	}

	function __destruct()
	{
		$this->tpl->draw("footer");
	}
}

?>