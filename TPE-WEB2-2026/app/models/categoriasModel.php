<?php

require_once "./config.php";

class categoriasModel{

    private $db;

    function __construct(){
        $this->db = new PDO('mysql:host='.MYSQL_HOST.';port=3307;dbname='.MYSQL_DB.';charset=utf8', MYSQL_USER, MYSQL_PASS);
    }

    function existeCategoria($id_categoria){
        $query = $this->db->prepare('SELECT 1 FROM categorias WHERE id_categoria = ?');
        $query->execute([$id_categoria]);
        return $query->fetch() !== false;

    }

    function showCategoria($id_categoria){
        $query = $this->db->prepare('SELECT nombre_categoria FROM categorias WHERE id_categoria = ?');
        $query->execute(array($id_categoria));
        return  $query->fetch(PDO::FETCH_OBJ);
        
    }

    function getCategorias(){
        $query = $this->db->prepare('SELECT * FROM categorias');
        $query->execute();

        $list = $query->fetchAll(PDO::FETCH_OBJ);

        return $list;
    }

    function insertCategoria($nombre_categoria, $descripcion, $fecha_creacion){
        $query = $this->db->prepare('INSERT INTO categorias(nombre_categoria,descripcion,fecha_creacion) VALUES (?,?,?)');
        $query->execute(array($nombre_categoria, $descripcion, $fecha_creacion));
        return $this->db->lastInsertId();
    
    }

    function updateCategoria($id, $nombre){
        $query = $this->db->prepare('UPDATE categorias SET nombre_categoria= ? WHERE id_categoria=?');
        $query->execute([$nombre,$id]);
    }

    function removeCategoria($id){
        $query = $this->db->prepare('DELETE FROM categorias WHERE id_categoria = ?');
        $query->execute([$id]);
    }

}