<?php
// Set headers for CORS and content type
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Include the database configuration and company class
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../class/company.php';

// Create a new Company object
$company = new Company($pdo);

// Get the Company ID from the URL parameter
$companyId = isset($_GET['id']) ? $_GET['id'] : null;

// Check if the Company ID is provided
if ($companyId) {
    // Set the company ID
    $company->id = $companyId;

    // Attempt to delete the company from the database
    if ($company->deleteCompany()) {
        // Return a 200 OK success response
        http_response_code(200);
        echo json_encode(array("success" => true, "message" => "Company deleted successfully"));
    } else {
        // Return a 500 Internal Server Error
        http_response_code(500);
        echo json_encode(array("success" => false, "message" => "Error deleting company"));
    }
} else {
    // Return a 400 Bad Request error
    http_response_code(400);
    echo json_encode(array("success" => false, "message" => "Company ID not provided"));
}