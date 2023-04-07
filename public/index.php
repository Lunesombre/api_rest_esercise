<?php

require_once __DIR__ . '/../vendor/autoload.php';
header('Content-Type: application/json; charset=utf-8');

use App\Config\DbInitializer;
use App\Config\ExceptionHandlerInitializer;
use Symfony\Component\Dotenv\Dotenv;


//Charge les variables d'environnement
$dotenv = new Dotenv();
$dotenv->loadEnv('.env');

// Initialisation BDD
ExceptionHandlerInitializer::registerGlobalExceptionHandler();
$pdo = DbInitializer::getPdoInstance();


$uri = $_SERVER['REQUEST_URI'];
$httpMethod = $_SERVER['REQUEST_METHOD'];


// var_dump($uri);
// var_dump($httpMethod);


// Collection de produits
if ($uri === '/products' && $httpMethod === 'GET') {
    $stmt = $pdo->query("SELECT * FROM products");
    $products = $stmt->fetchAll();
    echo json_encode($products);
    exit;
}

// Création de produits
if ($uri === '/products' && $httpMethod === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $query = "INSERT INTO products VALUES (null, :product_name, :product_base_price, :product_description)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'product_name' => $data['name'],
        'product_base_price' => $data['basePrice'],
        'product_description' => $data['description']
    ]);
    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        exit;
    }
    http_response_code(201);
    $productID = $pdo->lastInsertId();
    echo json_encode(["uri" => "$uri/$productID"]);
    exit;
}

if ($uri !== '/products') {
    $explodedUri = explode('/', $uri);
    // var_dump($explodedUri);
    $id = $explodedUri[2];
    //     // var_dump($id);
    //     $getId = "SELECT COUNT(*) AS NbProduct FROM products WHERE id=:id";
    //     $stmt = $pdo->prepare($getId);
    //     $stmt->execute([
    //         'id' => $id
    //     ]);
    //     $result = $stmt->fetch();
    //     $productsExistsInDB = (bool)$result['NbProduct'];
    // }

    // if ($productsExistsInDB === false) {
    //     http_response_code(404);
    //     exit;
}

// GET un seul produit
if ($uri === "/products/$id" && $httpMethod === 'GET') {
    $query = "SELECT * FROM products WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'id' => $id
    ]);
    $data = $stmt->fetch();
    if ($data === false) {
        http_response_code(404);
        exit;
    }
    echo json_encode($data);
    exit;
}

// DELETE
if ($uri === "/products/$id" && $httpMethod === 'DELETE') {
    $query = "DELETE FROM products WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'id' => $id
    ]);
    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        exit;
    }
    http_response_code(204);
    exit;
}

// UPDATE with PUT
if ($uri === "/products/$id" && $httpMethod === 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);
    $query = "UPDATE products SET name = :name, basePrice = :base_price, description = :description WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'name' => $data['name'],
        'base_price' => $data['basePrice'],
        'description' => $data['description'],
        'id' => $id
    ]);
    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        exit;
    }
    http_response_code(204);
    exit;
}
