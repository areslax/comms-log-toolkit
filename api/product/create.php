<?php
/* ##################################
 * create.php
 * ARESLAX API Create Product Methods
 * ################################## */

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

//get posted data
$data = json_decode(file_get_contents("php://input"));
//make sure data is not empty
if
(
	!empty($data->name) &&
	!empty($data->price) &&
	!empty($data->description) &&
	!empty($data->category_id)
) {
	//set product property values
	$product->name = $data->name;
	$product->price = $data->price;
	$product->description = $data->description;
	$product->category_id = $data->category_id;
	$product->created = date('Y-m-d H:i:s');
	//create product
	if ($product->create())
	{
		http_response_code(200);
		echo json_encode(array("message"=>"Product was created"));
	}
	else {
		http_response_code(400);
		echo json_encode(array("message"=>"Unable to create product"));
	}
}
else
{
	//data was incomplete
	http_response_code(400);
	echo json_encode(array("message"=>"Unable to create product. Data is incomplete."));
}

