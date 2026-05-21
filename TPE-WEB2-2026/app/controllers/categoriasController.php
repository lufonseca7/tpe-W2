<?php

require_once './app/models/categoriasModel.php';
require_once './app/views/categoriasView.php';
require_once './app/helpers/authHelper.php';

class categoriasController{
    private $model;
    private $view;

    function __construct(){
        $this->model = new categoriasModel();
        $this->view = new categoriasView();
    }

    function showCategorias(){
        $list = $this->model->getCategorias();

        if(AuthHelper::checkLogin()){
            $this->view->showAdminCategoriasList($list);
        }
        else{
            $this->view->showCategoriasList($list);
        }
    }

    function addCategoria(){
        AuthHelper::verify();
        $nombre_categoria = $_POST['nombre_categoria'];
        $descripcion  = $_POST['descripcion'];
        $fecha_creacion = $_POST['fecha_creacion'];

        if (empty($nombre_categoria)||empty($descripcion)||empty($fecha_creacion)){
            $this->view->showError("Debe completar todos los campos");
            return;
        }

        $id_categoria = $this->model->insertCategoria($nombre_categoria, $descripcion, $fecha_creacion);
       
        if ($id_categoria) {
            header('Location: ' . BASE_URL . 'categorias');
        } else {
            $this->view->showError("Error al insertar la categoria");
        }
    }

    function removeCategoria($id_categoria){
        AuthHelper::verify();
        $this->model->removeCategoria($id_categoria);

    }

    function editCategoria($id_categoria){
        AuthHelper::verify();
        if (!$this->model->existeCategoria($id_categoria)) {
            $this->view->showError("Debe ingresar una categoria existente");
            return;
        }
        $categoria = $this->model->showCategoria($id_categoria);
        $this->view->categoriaEdit($id_categoria,$categoria);
    }

    function updateCategoria($id_categoria){
        AuthHelper::verify();
        $nuevoNombre = $_POST['nombre'];
        if (empty($this->model->showCategoria($id_categoria))) {
            $this->view->showError("Debe ingresar una categoria existente");
            return;
        }
        $this->model->updateCategoria($id_categoria,$nuevoNombre);
        header('Location: ' . BASE_URL .'categorias');

    }
}