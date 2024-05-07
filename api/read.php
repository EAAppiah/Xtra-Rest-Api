<?php
// This will allow the client to access the API from different origins, which is useful during development.
// In a production environment, remember to restrict this to specific domains.
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../class/company.php';

// Instantiate the Company class with the PDO connection
$company = new Company($pdo);

try {
    // Fetch all companies
    $stmt = $company->getAllCompanies();
    $num = $stmt->rowCount();

    if ($num > 0) {
        $companyArr = array();
        $companyArr['companies'] = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $companyArr['companies'][] = array(
                "id" => $row['id'],
                "name" => $row['name'],
                "email" => $row['email'],
                "address" => $row['address'],
                "telephone" => $row['telephone'],
            );
        }

        // Return success response with data
        http_response_code(200);
        echo json_encode($companyArr);
    } else {
        // Return no content response
        http_response_code(404);
        echo json_encode(array("message" => "No record found."));
    }
} catch (Exception $e) {
    // Handle any errors
    http_response_code(500);
    echo json_encode(array("message" => "Error retrieving companies.", "error" => $e->getMessage()));
}