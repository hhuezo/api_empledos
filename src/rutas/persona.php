<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app = new \Slim\App;

// GET
$app->get('/catalogo/personas', function (Request $request, Response $response) {

    $sql = "SELECT * FROM persona";
    try {
        $db = new db();
        $db = $db->conexion();
        $resultado = $db->query($sql);

        if ($resultado->rowCount() > 0) {
            $personas = $resultado->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($personas);
        } else {
            echo json_encode("No existen personas en la BBDD.");
        }
        $resultado = null;
        $db = null;
    } catch (PDOException $e) {
        echo '{"error" : {"text":' . $e->getMessage() . '}';
    }
});

// GET Recueperar  por ID 
$app->get('/catalogo/personas/{id}', function (Request $request, Response $response) {
    $id_cliente = $request->getAttribute('id');
    $sql = "SELECT * FROM persona WHERE id = $id_cliente";
    try {
        $db = new db();
        $db = $db->conexion();
        $resultado = $db->query($sql);

        if ($resultado->rowCount() > 0) {
            $cliente = $resultado->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($cliente);
        } else {
            echo json_encode("No existen cliente en la BBDD con este ID.");
        }
        $resultado = null;
        $db = null;
    } catch (PDOException $e) {
        echo '{"error" : {"text":' . $e->getMessage() . '}';
    }
});



// POST Crear nuevo 
$app->post('/catalogo/personas/nuevo', function (Request $request, Response $response) {
    $nombre = $request->getParam('nombre');
    $apellido = $request->getParam('apellido');
    $direccion = $request->getParam('direccion');

    $sql = "INSERT INTO persona (nombre, apellido, direccion) VALUES 
           (:nombre, :apellido, :direccion)";
    try {
        $db = new db();
        $db = $db->conexion();
        $resultado = $db->prepare($sql);

        $resultado->bindParam(':nombre', $nombre);
        $resultado->bindParam(':apellido', $apellido);
        $resultado->bindParam(':direccion', $direccion);

        $resultado->execute();
        echo json_encode("Nuevo cliente guardado.");

        $resultado = null;
        $db = null;
    } catch (PDOException $e) {
        echo '{"error" : {"text":' . $e->getMessage() . '}';
    }
});



// PUT Modificar cliente 
$app->put('/catalogo/personas/edit/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    $nombre = $request->getParam('nombre');
    $apellido = $request->getParam('apellido');
    $direccion = $request->getParam('direccion');

   
   $sql = "UPDATE persona SET
           nombre = :nombre,
           apellido = :apellido,
           direccion = :direccion
         WHERE id = $id";
      
   try{
     $db = new db();
     $db = $db->conexion();
     $resultado = $db->prepare($sql);
 
     $resultado->bindParam(':nombre', $nombre);
     $resultado->bindParam(':apellido', $apellido);
     $resultado->bindParam(':direccion', $direccion);
 
     $resultado->execute();
     echo json_encode("Cliente modificado.");  
 
     $resultado = null;
     $db = null;
   }catch(PDOException $e){
     echo '{"error" : {"text":'.$e->getMessage().'}';
   }
 }); 
 
 
 // DELETE borar cliente 
 $app->delete('/catalogo/personas/delete/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    $sql = "DELETE FROM persona WHERE id = $id";
      
   try{
     $db = new db();
     $db = $db->conexion();
     $resultado = $db->prepare($sql);
      $resultado->execute();
 
     if ($resultado->rowCount() > 0) {
       echo json_encode("Cliente eliminado.");  
     }else {
       echo json_encode("No existe cliente con este ID.");
     }
 
     $resultado = null;
     $db = null;
   }catch(PDOException $e){
     echo '{"error" : {"text":'.$e->getMessage().'}';
   }
 }); 
 
