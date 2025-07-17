<?php 

require_once 'Connection.php';


class Login {
    private $email;
    private $senha;



    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }


    public function getSenha() {
        return $this->senha;
    }

    public function setSenha($senha) {
        $this->senha = $senha;
    }

}


class Autenticar extends Login {
        public function Entrar(Login $l) {
            $pdo = Connection::getConn();

            $stmt = $pdo->prepare("SELECT id, email, senha FROM usuarios WHERE email = :email");
            $stmt->bindParam(':email', $l->getEmail(), PDO::PARAM_STR);

            try {
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($result && password_verify($l->getSenha(), $result['senha'])) {
                        $_SESSION['id'] = $result['id'];
                        header('Location: painel.php');
                        exit();
                    } else {
                        echo "<h1>Email ou senha incorretos!</h1>";
                    }
            } catch (PDOException $e) {
                echo "Erro no banco de dados: " . $e->getMessage();
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $login = new Login();
        $email = isset($_POST['email']) ? trim($_POST['email']) : null;
        $senha = isset($_POST['senha']) ? trim($_POST['senha']) : null;

        $login->setEmail($email);
        $login->setSenha($senha);

        $aut = new Autenticar();
        $aut->Entrar($login);
    } else {
        echo "<h4>Método de requisição inválido!</h4>";
    }




?>