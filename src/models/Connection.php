<?php
namespace luca\dinner;

Use PDO;

class Connection{

    protected static $conn;

    private function __construct(){
        $conn_host = "localhost";
        $conn_nome = "lanchonete";
        $conn_usuario = "root";
        $conn_senha = "g0ml$5@9kS!";
        $conn_driver = "mysql";

        try{

            self::$conn = new PDO("$conn_driver:host=$conn_host; dbname=$conn_nome", $conn_usuario, $conn_senha);
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$conn->exec('SET NAMES utf8');


        }catch (PDOException $e){

            echo("Connection Error: " . $e->getMessage());


        }
    }

    public static function conexao(){

        if (!self::$conn){
            new Connection();
        }
        return self::$conn;

    }

}

/*
 public function __construct(){
        $ENV_HOST = 'localhost';
        $ENV_USERNAME = 'root';
        $ENV_PASSWORD = 'g0ml$5@9kS!';
        $ENV_DBNAME = 'lanchonete';
        $dsn = "mysql:host=$ENV_HOST;dbname=$ENV_DBNAME";
        $pdo = new PDO($dsn, $ENV_USERNAME, $ENV_PASSWORD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
 */
?>
