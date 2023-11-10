<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: X-AMZ-META-TOKEN-ID, X-AMZ-META-TOKEN-SECRET,Content-Type');
    header('Content-Type: application/json');
    
    require '../vendor/autoload.php';
    require 'postData.php';

    if($_SERVER['REQUEST_METHOD'] == "POST") {
        $data = json_decode(file_get_contents('php://input'), true);

        try {
            $postData = new PostData();
            $result = $postData->insertData($data);
            echo json_encode($result, JSON_PRETTY_PRINT);
        } catch(PDOException $e) {
            echo json_encode("Error: " . $e->getMessage());
        }

    } else {
        http_response_code(405);
        echo json_encode(['message' => 'Método inválido.']);
    }
?>