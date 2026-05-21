<?php

require_once './app/models/categoriasModel.php';
require_once './app/models/productosModel.php';
require_once './app/views/productosView.php';
require_once './app/helpers/authHelper.php';


class productosController{

    private $model;
    private $view;
    private $modelCategoria;

    function __construct(){
        $this->model = new productosModel();
        $this->view = new productosView();
        $this->modelCategoria = new categoriasModel();
    }

    
    function showHome(){
        $list = $this->model->getProducts();
        $categoria = $this->modelCategoria->getCategorias();

        if(AuthHelper::checkLogin()){
            $this->view->showAdminProductsList($list, $categoria);
        }
        else{
            $this->view->showProductsList($list, $categoria);
        }

    }

    function showProduct($id){
        AuthHelper::init();

        $producto = $this->model->showProduct($id);
        $categoria = $this->modelCategoria->showCategoria($id);
        $this->view->showProduct($producto, $categoria);

    }

    function addProduct(){
        AuthHelper::verify();
        $producto = $_POST['producto'];
        $categoria = $_POST['categoria'];
        $precio = $_POST['precio'];
        $talle = $_POST['talle'];
        $color = $_POST['color'];
        $marca = $_POST['marca'];

        if (empty($producto)||empty($categoria)||empty($precio)||empty($talle)||empty($color)||empty($marca)){
            $this->view->showError("Debe completar todos los campos");
        }
        if (empty($this->modelCategoria->showCategoria($categoria))) {
            $this->view->showError("Debe ingresar una categoria existente");
            return;
        }
        $idProducto = $this->model->insertProduct($producto, $categoria, $precio, $talle, $color, $marca);
        if($idProducto){
            header('Location: '. BASE_URL . 'home');
        }
        else {
            $this->view->showError("Error al insertar producto");
        }


    }

    function removeProduct($id){
        AuthHelper::verify();
        $this->model->removeProduct($id);
        header('Location: ' . BASE_URL . 'home');
    }

    function filtrarProducto(){
        AuthHelper::init();
        $products = $this->model->getProducts();

        $list = $this->model->filtrarProducto($_POST['filtroCategoria']);
        if(empty($_POST["filtroCategoria"])){
            $this->view->showError("Seleccione una categoria");
            return;
        }
        if(empty ($list)){
            $this->view->showError("NO existen productos con esta categoria");
            return;
        }
        if(authHelper::checkLogin()){
            $this->view->showAdminProductsList($list, $products);
        }
        else{
            $this->view->showProductsList($list, $products);
        }
    }

    function editProduct($id){
        AuthHelper::verify();
        $categoria = $this->modelCategoria->getCategorias();
        $producto = $this->model->getProducts($id);
        $this->view->editProduct($id, $categoria, $producto);

    }

    function actualizarProducto($id){
        AuthHelper::verify();
        $nuevoNombre = $_POST['nombre'];
        $categoria = $_POST['categoria'];
        $precio = $_POST['precio'];
        $talle = $_POST['talle'];
        $color = $_POST['color'];
        $marca = $_POST['marca'];

        if (empty($nuevoNombre)||empty($categoria)||empty($precio)||empty($talle)||empty($color)||empty($marca)) {
             $this->view->showError("complete todos los campos");
             return;
        }
         if (empty($this->modelCategoria->showCategoria($categoria))) {
            $this->view->showError("Debe ingresar una categoria existente");
            return;
        }
        $this->model->updateProduct($id,$nuevoNombre, $categoria, $precio, $talle, $color, $marca);
        header('Location: ' . BASE_URL .'home');
    }




    

    

}