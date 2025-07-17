<?php 

require_once 'Connection.php';


try {
    $conn = Connection::getConn();

    echo "Conexão realizada com sucesso!";
} catch (PDOException $e) {
    echo "Erro ao se conectar com o banco de dados: " . $e->getMessage();
}

?>