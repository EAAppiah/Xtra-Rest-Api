<?php

// Including the database connection from a two levels up
require_once __DIR__ . '/../config/database.php';

class Company
{
  // db-connection
  private $conn;
  // Table Name
  private $db_table = 'Company';
  //Cols
  public $id;
  public $name;
  public $address;
  public $telephone;
  public $email;

  public function __construct($pdo) {
    $this->conn = $pdo;
  }

  // GET ALL COMPANIES
  public function getAllCompanies() {
    try {
      $query = "SELECT * FROM " . $this->db_table . "";
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      return $stmt;
    } catch (PDOException $e) {
      echo "Database Connection Error" . $e->getMessage();
    } catch (Exception $e) {
      echo "Error" . $e->getMessage();
    }
  }

  // CREATE COMPANIES
  public function createCompany() {
    try {
      $query = "INSERT INTO " . $this->db_table . "
        SET 
        name = :name, 
          email = :email, 
          address = :address, 
          telephone = :telephone";

      // prepare sql statement
      $stmt = $this->conn->prepare($query);

      // sanitize
      $this->name = htmlspecialchars(strip_tags($this->name));
      $this->email = htmlspecialchars(strip_tags($this->email));
      $this->address = htmlspecialchars(strip_tags($this->address));
      $this->telephone = htmlspecialchars(strip_tags($this->telephone));

      // bind data
      $stmt->bindParam(":name", $this->name);
      $stmt->bindParam(":email", $this->email);
      $stmt->bindParam(":address", $this->address);
      $stmt->bindParam(":telephone", $this->telephone);

      if ($stmt->execute()) {
        return true;
      }
      return false;
    } catch (PDOException $e) {
      echo "Database Connection Error" . $e->getMessage();
    } catch (Exception $e) {
      echo "Error" . $e->getMessage();
    }
  }

  // CREATE COMPANIES
  public function updateCompany() {
    try {
      $query = "UPDATE " . $this->db_table . "
          SET 
            name = :name, 
            email = :email, 
            address = :address, 
            telephone = :telephone
          WHERE 
            id = :id";

      // prepare sql statement
      $stmt = $this->conn->prepare($query);

      // sanitize
      $this->name = htmlspecialchars(strip_tags($this->name));
      $this->email = htmlspecialchars(strip_tags($this->email));
      $this->address = htmlspecialchars(strip_tags($this->address));
      $this->telephone = htmlspecialchars(strip_tags($this->telephone));

      // bind data
      $stmt->bindParam(":name", $this->name);
      $stmt->bindParam(":email", $this->email);
      $stmt->bindParam(":address", $this->address);
      $stmt->bindParam(":telephone", $this->telephone);

      if ($stmt->execute()) {
        return true;
      }
      return false;
    } catch (PDOException $e) {
      echo "Database Connection Error" . $e->getMessage();
    } catch (Exception $e) {
      echo "Error" . $e->getMessage();
    }
  }

  // DELETE COMPANIES
  function deleteCompany() {
    try {
      $query = "DELETE FROM " . $this->db_table . " WHERE id = ?";
      $stmt = $this->conn->prepare($query);

      // Sanitize
      $this->id = htmlspecialchars(strip_tags($this->id));

      $stmt->bindParam(1, $this->id);

      if ($stmt->execute()) {
        return true;
      }
      return false;
    } catch (PDOException $e) {
      echo "Database Connection Error" . $e->getMessage();
    } catch (Exception $e) {
      echo "Error" . $e->getMessage();
    }
  }

  // SEARCH COMPANIES
  public function searchCompanies($searchTerm) {
    try {
      // Sanitize the search term
      $searchTerm = htmlspecialchars(strip_tags($searchTerm));

      // Prepare the SQL query
      $query = "SELECT * FROM " . $this->db_table . "
          WHERE name LIKE :searchTerm OR address LIKE :searchTerm OR telephone LIKE :searchTerm OR email LIKE :searchTerm";
      $stmt = $this->conn->prepare($query);

      // Bind the search term parameter
      $searchPattern = "%$searchTerm%";
      $stmt->bindParam(":searchTerm", $searchPattern);

      // Execute the query
      $stmt->execute();

      // Return the result set
      return $stmt;
    } catch (PDOException $e) {
      echo "Database Connection Error: " . $e->getMessage();
      return false;
    } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
      return false;
    }
  }
}
