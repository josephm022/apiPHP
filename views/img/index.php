<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Request-Headers, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("content-type: application/json; charset=utf-8");
header("HTTP/1.1 200 OK");

$json = file_get_contents('php://input'); //recibe dato json de angular
$params = json_decode($json); //decodifica el json y lo guarda en variable

$nombreArchivo = $params -> nombreArchivo;
$archivo = $params-> base64textString;
$archivo = base64_decode($archivo);

$filePath = $_SERVER['DOCUMENT_ROOT']."/views/img/".$nombreArchivo;
file_put_contents($filePath, $archivo);

class Result{
    public $resultado;
    public $mensaje;
}

$response = new Result();
$response-> resultado ='OK';
$response-> mensaje = 'SE SUBIO EXITOSAMENTE';

header('Content-Type: application/json');
echo json_encode($response);