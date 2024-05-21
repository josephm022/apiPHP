<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Request-Headers, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("content-type: application/json; charset=utf-8");
header("HTTP/1.1 200 OK");

$params = $_GET['foto'];

class Result{
    public $resultado;
    public $mensaje;
}

$nombreArchivo = $params;

if ($nombreArchivo !== "") {
    $filePath = $_SERVER['DOCUMENT_ROOT']."/views/img/".$nombreArchivo;
    unlink($filePath);

    $response = new Result();

    $response-> resultado ='OK';
    $response -> mensaje = 'SE ELIMINO EXITOSAMENTE';
}else{

    $response = new Result();

    $response-> resultado ='404';
    $response -> mensaje = 'NO EXISTE EL ARCHIVO';
}

header('Content-Type: application/json');
echo json_encode($response);