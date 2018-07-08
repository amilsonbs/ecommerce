<?php

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;
use \Hcode\Mailer;

class Category extends Model
{

	static function listAll()
	{
		$sql = new Sql();

		return $sql->select("SELECT * FROM tb_categories ORDER BY descategory");
	}

	function save()
	{
		$sql = new Sql();
		
		$results = $sql->select("CALL sp_categories_save(:idcategory, :descategory)",
			array(
			":idcategory"=>$this->getidcategory(),
			":descategory"=>$this->getdescategory()			
		));

		if (count($results) === 0)
		{
			throw new \Exception("Algum erro na procedure do banco!");			
		}

		$this->setData($results[0]);

		Category::updateFile();
	}

	function get($idcategory)
	{
		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_categories WHERE idcategory = :idcategory", [
			':idcategory' => $idcategory
		]);

		$this->setData($results[0]);
	}

	function delete()
	{
		$sql = new Sql();

		$sql->query("DELETE FROM tb_categories WHERE idcategory = :idcategory", [
			':idcategory' => $this->getidcategory()
		]);

		Category::updateFile();

	}

	static function updateFile()
	{
		$categories = Category::listAll();

		$html = [];

		foreach ($categories as $row) {
			array_push($html, '<li><a href="/categories/'.$row['idcategory'].'">'.$row['descategory'].'</a></li>');
		}

		file_put_contents($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR."categories-menu.html", implode('', $html));

	}


}


?>