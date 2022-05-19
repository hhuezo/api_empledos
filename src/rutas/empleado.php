<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');


$app = new \Slim\App;

// GET
$app->get('/empleado/', function (Request $request, Response $response) {

  $sql = "SELECT Id,Nombre,Apellido,concat('http://localhost:81/api_empleados/public/images/',Foto) as Foto FROM persona";
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

// POST Crear nuevo 
$app->post('/empleado/upload', function (Request $request, Response $response) {

  $fechaActual = date('dmYHis');
  $nombre_archivo = $fechaActual.' '.$_FILES['Foto']['name'];
  $temp = $_FILES['Foto']['tmp_name'];
 

  move_uploaded_file($temp, 'images/' . $nombre_archivo);

  $Nombre = $request->getParam('Nombre');
  $Apellido = $request->getParam('Apellido');

  $sql = "INSERT INTO persona (Nombre, Apellido,Foto) VALUES 
           (:Nombre, :Apellido,:Foto)";
  try {
    $db = new db();
    $db = $db->conexion();
    $resultado = $db->prepare($sql);
    //echo json_encode($sql);
    $resultado->bindParam(':Nombre', $Nombre);
    $resultado->bindParam(':Apellido', $Apellido);
    $resultado->bindParam(':Foto', $nombre_archivo);

    $resultado->execute();


    $resultado = null;
    $db = null;
  } catch (PDOException $e) {
    echo '{"error" : {"text":' . $e->getMessage() . '}';
  }
});





// GET Recueperar  por ID 
$app->get('/empleado/{id}', function (Request $request, Response $response) {
  $id = $request->getAttribute('id');
  $sql = "SELECT Id,Nombre,Apellido,concat('http://localhost:81/api_empleados/public/images/',Foto) as Foto FROM persona WHERE Id = $id";
  try {
    $db = new db();
    $db = $db->conexion();
    $resultado = $db->query($sql);

    if ($resultado->rowCount() > 0) {
      $empleado = $resultado->fetchAll(PDO::FETCH_OBJ);
      echo json_encode($empleado);
    } else {
      echo json_encode("No existen Empleado en la BBDD con este ID.");
    }
    $resultado = null;
    $db = null;
  } catch (PDOException $e) {
    echo '{"error" : {"text":' . $e->getMessage() . '}';
  }
});


// PUT Modificar cliente 
$app->put('/empleado/edit/{id}', function (Request $request, Response $response) {

  $Id = $request->getAttribute('id');
  $Nombre = $request->getParam('Nombre');
  $Apellido = $request->getParam('Apellido');



  $sql = "UPDATE persona SET
           Nombre = :Nombre,
           Apellido = :Apellido
         WHERE id = $Id";

  try {
    $db = new db();
    $db = $db->conexion();
    $resultado = $db->prepare($sql);

    $resultado->bindParam(':Nombre', $Nombre);
    $resultado->bindParam(':Apellido', $Apellido);

    $resultado->execute();
    echo json_encode("Cliente modificado.");

    $resultado = null;
    $db = null;
  } catch (PDOException $e) {
    echo '{"error" : {"text":' . $e->getMessage() . '}';
  }
});

// DELETE borar Empleado 
$app->delete('/empleado/delete/{id}', function (Request $request, Response $response) {
  $id = $request->getAttribute('id');
  $sql = "DELETE FROM persona WHERE Id = $id";

  try {
    $db = new db();
    $db = $db->conexion();
    $resultado = $db->prepare($sql);
    $resultado->execute();

    if ($resultado->rowCount() > 0) {
      echo json_encode("Empleado eliminado.");
    } else {
      echo json_encode("No existe Empleado con este ID.");
    }

    $resultado = null;
    //$resultado = $sql;
  } catch (PDOException $e) {
    echo '{"error" : {"text":' . $e->getMessage() . '}';
  }
});
