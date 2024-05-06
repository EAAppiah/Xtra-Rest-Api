<?php
// Set headers for CORS and content type
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Include the database configuration and company class
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../class/company.php';

// Create a new Company object
$company = new Company($pdo);

// Get the raw POST data
$data = json_decode(file_get_contents("php://input"));

// Check if the data is not empty and has the required properties
if (!empty($data) && isset($data->name, $data->email, $data->address, $data->telephone)) {
    // Assign the data to the company object
    $company->name = $data->name;
    $company->email = $data->email;
    $company->address = $data->address;
    $company->telephone = $data->telephone;

    //create the company in the database
    if ($company->createCompany()) {
        // Return a 201 Created success response
        http_response_code(201);
        echo json_encode(array("success" => true, "message" => "Company created successfully"));
    } else {
        // Return a 500 Internal Server Error
        http_response_code(500);
        echo json_encode(array("success" => false, "message" => "Error creating company"));
    }
} else {
    // Return a 400 Bad Request error
    http_response_code(400);
    echo json_encode(array("success" => false, "message" => "Incomplete or invalid data provided"));
}

