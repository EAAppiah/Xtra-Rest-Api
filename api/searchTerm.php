<?php
// Set headers for CORS and content type
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Include the database configuration and company class
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../class/company.php';

// Create a new Company object
$company = new Company($pdo);

// Get the search term from the URL parameter
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Check if the search term is provided
if (!empty($searchTerm)) {
    // Perform the search operation using the searchCompanies method
    $stmt = $company->searchCompanies($searchTerm);

    // Check if any results are found
    if ($stmt && $stmt->rowCount() > 0) {
        // Fetch all the matching companies
        $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return a 200 OK success response with the search results
        http_response_code(200);
        echo json_encode(array("success" => true, "message" => "Search results found", "data" => $companies));
    } else {
        // Return a 404 Not Found response if no results are found
        http_response_code(404);
        echo json_encode(array("success" => false, "message" => "No companies found matching your search"));
    }
} else {
    // Return a 400 Bad Request error if no search term is provided
    http_response_code(400);
    echo json_encode(array("success" => false, "message" => "Search term not provided"));
}