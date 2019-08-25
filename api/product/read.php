<?php
/* ################################
 * read.php
 * ARESLAX API Read Product Methods
 * ################################ */

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
include_once "../config/database.php";
include_once "../objects/product.php";

//instantiate database and product object
$database = new Database();
$db = $database->getConnection();
//initialize product object
$product = new Product($db);

//query products
$stmt = $product->read();
$num = $stmt->rowCount();

if ($num>0)
{
	//products array
	$products_arr = array();
	$products_arr['records'] = array();
	//fetch is faster than fetchAll
	while ($row=$stmt->fetch(PDO::FETCH_ASSOC))
	{
		extract($row);
		$product_item = array
		(
			"id" => $id,
			"name" => $name,
			"description" => html_entity_decode($description),
			"price" => $price,
			"category_id" => $category_id,
			"category_name" => $category_name
		);
		array_push($products_arr['records'],$product_item);
	}
	//set response code 200 ok
	http_response_code(200);
	//showproduct data in json format
	echo json_encode($products_arr);
}
else
{
	http_response_code(400);
	echo json_encode
	(
		array("message"=>"No products found")
	);
}


