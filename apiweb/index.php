<?php

//conexion bd

$host="localhost:3307";
$usuario="root";
$password="";
$bd="myapi";

$conexion= new mysqli($host, $usuario, $password, $bd);
if($conexion->connect_error){
    die ("error conexion no establecida". $conexion -> connect_error);
}

//
header("Content-Type: application/json");
$metodo= $_SERVER['REQUEST_METHOD'];
//print_r($metodo);
//ruta
$path= isset($_SERVER['PATH_INFO'])?$_SERVER['PATH_INFO']:'/';
$buscarId = explode('/', $path);
$id= ($path!=='/') ? end($buscarId):null;

//validar metodo
switch ($metodo){
    //CONSULTAR    
    case 'GET':
        echo"consulta de registro(GET)";
        consulta($conexion);
        break;
    //INSERTAR DATOS
    case 'POST':
        echo"insertar registro(POST)";
        insertar($conexion);
        break;
    //ACTUALIZAR    
    case 'PUT':
        echo"actualizar registro(PUT)";
        actualizar($conexion, $id);
        break;
    //ELIMINAR    
    case 'DELETE':
        echo"eliminar registro(DELETE)";
        eliminar($conexion, $id);
        break;
    default:
        echo"metodo no permitido o identificado";
        break;        
}
//FUNCION CONSULTAR
function consulta($conexion){
    $sql="SELECT * FROM clientes";
    $resultado = $conexion -> query($sql);

    if($resultado){
        $datos = array();
        while($fila = $resultado ->fetch_assoc()){
            $datos[]=$fila;
    }
        echo json_encode($datos);
    }
}
//FUNCION INSERTAR
function insertar($conexion){
    $dato = json_decode(file_get_contents('php://input'),true);
    $nombre= $dato['nombre'];
    $apellido= $dato['apellido'];
    //print_r($nombre, $apellido);

    $sql="INSERT INTO clientes(nombre, apellido) VALUES ('$nombre', '$apellido')";
    $resultado = $conexion -> query($sql);

    if($resultado){
        $dato['id'] = $conexion->insert_id;
    echo json_encode($dato);
        }else{
            echo json_encode(array('error'=>'error al insertar usuario'));
        }
}
function eliminar($conexion, $id){
    $sql="DELETE FROM clientes WHERE id = '$id' ";
    $resultado = $conexion -> query($sql);

    if($resultado){
        echo json_encode(array('mensaje'=>'Usuario borrado'));
    }else{
        echo json_encode(array('error'=>'Error al borrar'));
    }
}
function actualizar($conexion, $id){

    $dato = json_decode(file_get_contents('php://input'),true);
    $nombre= $dato['nombre'];
    $apellido= $dato['apellido'];

    echo "el id actualizado es:". $id;

    $sql="UPDATE clientes SET nombre='$nombre', apellido='$apellido' WHERE id=$id";
    $resultado = $conexion -> query($sql);
    if($resultado){
        echo json_encode(array('mensaje'=>'Usuario actualizado'));
    }else{
        echo json_encode(array('error'=>'Error al actualizar'));
    }

}
?>