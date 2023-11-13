<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Content-Type');
    
    require 'PostData.php';

    if ($_SERVER['REQUEST_METHOD'] == "OPTIONS") {
        http_response_code(204); 
        exit;
    }

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