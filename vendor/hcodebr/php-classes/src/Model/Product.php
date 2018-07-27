<?php

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;
use \Hcode\Mailer;

class Product extends Model
{

	static function listAll()
	{
		$sql = new Sql();

		return $sql->select("SELECT * FROM tb_products ORDER BY desproduct");
	}

	static function checkList($list)
	{

		foreach ($list as $row) {
			
			$p = new Product();
			$p->setData($row);
			$row = $p->getValues();

		}

		return $list;
	}

	function save()
	{
		$sql = new Sql();
		
		$results = $sql->select("CALL sp_products_save(:idproduct, :desproduct, :vlprice, :vlwidth, :vlheight, :vllength, :vlweight, :desurl)", 
			array(
			":idproduct"=>$this->getidproduct(),
			":desproduct"=>$this->getdesproduct(),
			":vlprice"=>$this->getvlprice(),
			":vlwidth"=>$this->getvlwidth(),
			":vlheight"=>$this->getvlheight(),
			":vllength"=>$this->getvllength(),
			":vlweight"=>$this->getvlweight(),
			":desurl"=>$this->getdesurl()			
		));

		if (count($results) === 0)
		{
			throw new \Exception("Algum erro na procedure do banco!");			
		}

		$this->setData($results[0]);

	}

	function get($idproduct)
	{
		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_products WHERE idproduct = :idproduct", [
			':idproduct' => $idproduct
		]);

		$this->setData($results[0]);
	}

	function delete()
	{
		$sql = new Sql();

		$sql->query("DELETE FROM tb_products WHERE idproduct = :idproduct", [
			':idproduct' => $this->getidproduct()
		]);

	}

	function checkPhoto()
	{
		if (file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR .
						"resources" . DIRECTORY_SEPARATOR .
						"site" . DIRECTORY_SEPARATOR .
						"img" . DIRECTORY_SEPARATOR .
						"products" . DIRECTORY_SEPARATOR .
						$this->getidproduct() . ".jpg"
					))
		{
			$url = "/resources/site/img/products/" . $this->getidproduct() . ".jpg";
		} else 
		{
			$url = "/resources/site/img/product.jpg";
		}

		return $this->setdesphoto($url);

	}

	function getValues()
	{
		$this->checkPhoto();

		$values = parent::getValues();

		return $values;

	}

	function setPhoto($file)
	{
		$extension = explode('.', $file['name']);
		$extension = end($extension);

		switch ($extension) {
			case 'jpg':
			case 'jpeg':
				$image = imagecreatefromjpeg($file["tmp_name"]);
				break;
			case 'gif':
				$image = imagecreatefromgif($file["tmp_name"]);
				break;
			case 'png';
				$image = imagecreatefrompng($file["tmp_name"]);
				break;
		}

		$dest = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR .
						"resources" . DIRECTORY_SEPARATOR .
						"site" . DIRECTORY_SEPARATOR .
						"img" . DIRECTORY_SEPARATOR .
						"products" . DIRECTORY_SEPARATOR .
						$this->getidproduct() . ".jpg";

		imagejpeg($image, $dest);

		imagedestroy($image);

		$this->checkPhoto();

	}

}


?>