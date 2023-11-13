<?php   

    require 'Connection.php';

    class PostData extends Connection {
       private $user_id;
        public function __construct() {
            parent::__construct();
        }

        public function insertData($data) {
            $this->con->beginTransaction();
        
            try {
                $user = $this->getUserByEmail($data['info']["email"]);
               
                if (!$user) {
                    $new_user = $this->insertNewUser($data['info']);
                }
                
                $orcamento = $this->insertOrcamento($this->user_id, $data['list']);
                $this->con->commit();
                return $orcamento;
            } catch (PDOException $e) {
                $this->con->rollBack();
                throw new Exception($e->getMessage());
            }
        }
        
        private function getUserByEmail($email) {
            $stmt = $this->con->prepare("SELECT * FROM usuario_orc WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);       
            $this->user_id = $user['id'];
            return $user;
        }
        
        private function insertNewUser($userData) {
            $sql_insert_user = "INSERT INTO usuario_orc (aceite, solicitante_nome, email, cnpj, orgao_nome, telefone) VALUES (:aceite, :solicitante_nome, :email, :cnpj, :orgao_nome, :telefone)";
            $stmt_user = $this->con->prepare($sql_insert_user);
            $stmt_user->bindParam(':aceite', $userData['aceite']);
            $stmt_user->bindParam(':solicitante_nome', $userData['solicitante_nome']);
            $stmt_user->bindParam(':email', $userData["email"]);
            $stmt_user->bindParam(':cnpj', $userData['cnpj']);
            $stmt_user->bindParam(':orgao_nome', $userData['orgao_nome']);
            $stmt_user->bindParam(':telefone', $userData['telefone']);
            $stmt_user->execute();
            $user_id = $this->con->lastInsertId();
            $this->user_id = $user_id;
            return $stmt_user->fetch(PDO::FETCH_ASSOC);
        }
        
        private function insertOrcamento($usuario_id, $list) {
            $id = $usuario_id ;
            $sql_insert_orc = "INSERT INTO orcamento_orc (usuario_id, list) VALUES (:usuario_id, :list)";
            $stmt_orc = $this->con->prepare($sql_insert_orc);
            $stmt_orc->bindParam(':usuario_id', $id);
            $stmt_orc->bindParam(':list', json_encode($list, true));
            $stmt_orc->execute();
            $orcamento_id = $this->con->lastInsertId();
        
            $stmt_orc = $this->con->prepare("SELECT * FROM orcamento_orc WHERE id = :id");
            $stmt_orc->bindParam(':id', $orcamento_id);
            $stmt_orc->execute();
            $orcamento = $stmt_orc->fetch(PDO::FETCH_ASSOC);
            $orcamento['list'] = json_decode($orcamento['list'], true);
        
            return $orcamento;
        }
    }
?>