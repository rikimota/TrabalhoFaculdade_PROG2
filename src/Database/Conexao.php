<?php
  
  namespace App\Database;

  use PDO;

  class Conexao {
    public static function getConexao(): PDO {
        return new PDO("mysql:host=localhost;dbname=estoque_db;charset:utf8", "root", "");
    }
  }

?>