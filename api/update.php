<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Including required files for database and the Company class.
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../class/company.php';

// Instantiate the Company class with the PDO connection.
$company = new Company($pdo);

// Read incoming JSON data.
$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data) && isset($data->id, $data->name, $data->email, $data->address, $data->telephone)) {

	// Assign data to the company object.
	$company->id = $data->id;
	$company->name = $data->name;
	$company->email = $data->email;
	$company->address = $data->address;
	$company->telephone = $data->telephone;


	// Attempt to create the company in the database.
	if ($company->updateCompany()) {
		http_response_code(200); // HTTP 201 Updated.
		echo json_encode(array("message" => "Company update successfully."));
	} else {
		http_response_code(500); // HTTP 500 Internal Server Error.
		echo json_encode(array("message" => "Error updating company."));
	}

} else {
	// Return a 400 Bad Request error
	http_response_code(400);
	echo json_encode(array("success" => false, "message" => "Incomplete or invalid data provided"));
}