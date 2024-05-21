<?php

include("conexion.php");
/*=================
CORS
=================*/
$method = $_SERVER["REQUEST_METHOD"];
if($method == "OPTIONS"){
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Request-Headers, Authorization");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
    header("content-type: application/json; charset=utf-8");
    header("HTTP/1.1 200 OK");
    die();
}

$json = file_get_contents('php://input'); //recibe dato json de angular
$params = json_decode($json); //decodifica el json y lo guarda en variable

//Establece conexion con una nueva instancia
$pdo = new Conexion() ;

$action = isset($_GET['action']) ? $_GET['action'] : (isset($_GET['login']) ? 'login' : (isset($_GET['roles']) ? 'roles' : (isset($_GET['tipo_documentos']) ? 'tipo_documentos' : 'usuario')));

//FUNCION PARA LOS DATOS DE USUARIO
function manejarUsuarios($pdo, $method, $params) {
    //Obtener datos -- metodo GET
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        if(isset($_GET['id'])){
    
            $sql = $pdo -> prepare('SELECT * FROM usuario WHERE id=:id');
            $sql -> bindValue(':id', $_GET['id']);
            $sql -> execute();
            $sql -> setFetchMode(PDO::FETCH_ASSOC);
    
            header('HTTP/1.1 200 OK');
            echo json_encode($sql -> fetchAll());
            exit;
    
        }else{      
            $sql = $pdo -> prepare('SELECT * FROM usuario');
            $sql -> execute();
            $sql -> setFetchMode(PDO::FETCH_ASSOC);
    
            header('HTTP/1.1 200 OK');
            echo json_encode($sql -> fetchAll());
            exit;
        }
    
    }
    //Registrar datos -- metodo POST
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $sql = "INSERT INTO usuario (nombres, apellidos, email, num_telefono,num_documento, contrasena, genero, foto, activo, fecha_nacimiento, id_rol, id_tipo_doc) 
        VALUES (:nombres, :apellidos, :email, :num_telefono,:num_documento, :contrasena, :genero, :foto, :activo, :fecha_nacimiento, :id_rol, :id_tipo_doc)";
        $stmt = $pdo -> prepare($sql);
    
        $stmt -> bindValue(':nombres', $params -> nombres);
        $stmt -> bindValue(':apellidos', $params -> apellidos);
        $stmt -> bindValue(':email', $params -> email);
        $stmt -> bindValue(':num_telefono', $params -> num_telefono);
        $stmt -> bindValue(':num_documento', $params -> num_documento);
        $stmt -> bindValue(':contrasena', $params -> contrasena);
        $stmt -> bindValue(':genero', $params -> genero);
        $stmt -> bindValue(':foto', $params -> foto);
        $stmt -> bindValue(':activo', $params -> activo);
        $stmt -> bindValue(':fecha_nacimiento', $params -> fecha_nacimiento);
        $stmt -> bindValue(':id_rol', $params -> id_rol);
        $stmt -> bindValue(':id_tipo_doc', $params -> id_tipo_doc);
        $stmt -> execute();
        $idPost = $pdo -> lastInsertId();
    
        if($idPost){
            header('HTTP/1.1 200 OK');
            echo json_encode('El usuario se agrego correctamente');
        }else {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode('Error al agregar el usuario');
        }
        exit;
    }
    
    //Actualizar datos -- metodo PUT
    if($_SERVER['REQUEST_METHOD'] == 'PUT'){
        $sql = 'UPDATE usuario SET nombres=:nombres, apellidos=:apellidos, email=:email, num_telefono=:num_telefono, num_documento=:num_documento, contrasena=:contrasena, genero=:genero, foto=:foto, activo=:activo, fecha_nacimiento=:fecha_nacimiento, id_rol=:id_rol, id_tipo_doc=:id_tipo_doc WHERE id=:id';
        $stmt = $pdo -> prepare($sql);
    
        $stmt -> bindValue(':nombres', $params -> nombres);
        $stmt -> bindValue(':apellidos', $params -> apellidos);
        $stmt -> bindValue(':email', $params -> email);
        $stmt -> bindValue(':num_telefono', $params -> num_telefono);
        $stmt -> bindValue(':num_documento', $params -> num_documento);
        $stmt -> bindValue(':contrasena', $params -> contrasena);
        $stmt -> bindValue(':genero', $params -> genero);
        $stmt -> bindValue(':foto', $params -> foto);
        $stmt -> bindValue(':activo', $params -> activo);
        $stmt -> bindValue(':fecha_nacimiento', $params -> fecha_nacimiento);
        $stmt -> bindValue(':id_rol', $params -> id_rol);
        $stmt -> bindValue(':id_tipo_doc', $params -> id_tipo_doc);
        $stmt -> bindValue(':id', $_GET['id']);
        $stmt  -> execute();
    
        header('HTTP/1.1 200 OK');
            echo json_encode('El registro se actualizo correctamente');
            exit();
    }
    
    //Eliminar datos -- metodo DELETE
    if($_SERVER['REQUEST_METHOD'] == 'DELETE'){
        $sql = 'DELETE FROM usuario WHERE id=:id';
        $stmt = $pdo -> prepare($sql);
        $stmt -> bindValue(':id', $_GET['id']);
        $stmt -> execute();
    
        header('HTTP/1.1 200 OK');
            echo json_encode('El registro se elimino correctamente');
            exit;
    }
}

//FUNCION PARA LOS ROLES
function manejarRoles($pdo, $method, $params) {
    //Obtener datos -- metodo GET
    if ($method == 'GET') {
        if (isset($_GET['id'])) {
            $sql = $pdo->prepare('SELECT * FROM roles WHERE id = :id');
            $sql->bindValue(':id', $_GET['id']);
            $sql->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);

            header('HTTP/1.1 200 OK');
            echo json_encode($sql->fetchAll());
        } else {
            $sql = $pdo->prepare('SELECT * FROM roles');
            $sql->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);

            header('HTTP/1.1 200 OK');
            echo json_encode($sql->fetchAll());
        }
        exit;
    }

    //Registrar datos -- metodo POST
    if ($method == 'POST') {
        $sql = "INSERT INTO roles (descripcion) VALUES (:descripcion)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':descripcion', $params->descripcion);
        $stmt->execute();
        $idPost = $pdo->lastInsertId();

        if ($idPost) {
            header('HTTP/1.1 200 OK');
            echo json_encode('El rol se agrego correctamente');
        } else {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode('Error al agregar el rol');
        }
        exit;
    }

    //Actualizar datos -- metodo PUT
    if ($method == 'PUT') {
        $sql = 'UPDATE roles SET descripcion=:descripcion WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':descripcion', $params->descripcion);
        $stmt->bindValue(':id', $_GET['id']);
        $stmt->execute();

        header('HTTP/1.1 200 OK');
        echo json_encode('El rol se actualizo correctamente');
        exit;
    }

    //Eliminar datos -- metodo DELETE
    if ($method == 'DELETE') {
        $sql = 'DELETE FROM roles WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $_GET['id']);
        $stmt->execute();

        header('HTTP/1.1 200 OK');
        echo json_encode('El rol se elimino correctamente');
        exit;
    }
}


//FUNCION PARA LOS TIPOS DE DOCUMENTOS
function manejarDocumentos($pdo, $method, $params) {
    //Obtener datos -- metodo GET
    if ($method == 'GET') {
        if (isset($_GET['id'])) {
            $sql = $pdo->prepare('SELECT * FROM tipo_documentos WHERE id = :id');
            $sql->bindValue(':id', $_GET['id']);
            $sql->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);

            header('HTTP/1.1 200 OK');
            echo json_encode($sql->fetchAll());
        } else {
            $sql = $pdo->prepare('SELECT * FROM tipo_documentos');
            $sql->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);

            header('HTTP/1.1 200 OK');
            echo json_encode($sql->fetchAll());
        }
        exit;
    }

    //Registrar datos -- metodo POST
    if ($method == 'POST') {
        $sql = "INSERT INTO tipo_documentos (descripcion) VALUES (:descripcion)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':descripcion', $params->descripcion);
        $stmt->execute();
        $idPost = $pdo->lastInsertId();

        if ($idPost) {
            header('HTTP/1.1 200 OK');
            echo json_encode('El tipo de documento se agrego correctamente');
        } else {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode('Error al agregar el tipo de documento');
        }
        exit;
    }

    //Actualizar datos -- metodo PUT
    if ($method == 'PUT') {
        $sql = 'UPDATE tipo_documentos SET descripcion=:descripcion WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':descripcion', $params->descripcion);
        $stmt->bindValue(':id', $_GET['id']);
        $stmt->execute();

        header('HTTP/1.1 200 OK');
        echo json_encode('El tipo de documento se actualizo correctamente');
        exit;
    }

    //Eliminar datos -- metodo DELETE
    if ($method == 'DELETE') {
        $sql = 'DELETE FROM tipo_documentos WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $_GET['id']);
        $stmt->execute();

        header('HTTP/1.1 200 OK');
        echo json_encode('El tipo de documento se elimino correctamente');
        exit;
    }
}

// FUNCIÓN PARA EL INICIO DE SESIÓN
function loginUsuario($pdo, $params) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $params->email;
        $password = $params->contrasena;

        $stmt = $pdo->prepare("SELECT * FROM usuario WHERE email = :email");
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar la contraseña
        if ($user && $password === $user['contrasena']) {
            $token = bin2hex(random_bytes(16));
            header('HTTP/1.1 200 OK');
            echo json_encode([
                "auth" => true,
                "token" => $token,
                "user" => [
                    "id" => $user['id'],
                    "nombres" => $user['nombres'],
                    "apellidos" => $user['apellidos'],
                    "email" => $user['email'],
                    "foto" => $user['foto']
                ]
            ]);
        } else {
            
            header('HTTP/1.1 401 Unauthorized');
            echo json_encode(["error" => "Invalid email or password."]);
        }
        exit();
    }
}


switch ($action) {
    case 'usuario':
        manejarUsuarios($pdo, $method, $params);
        break;
    case 'roles':
        manejarRoles($pdo, $method, $params);
        break;
    case 'tipo_documentos':
        manejarDocumentos($pdo, $method, $params);
        break;
    case 'login':
        loginUsuario($pdo, $params);
        break;
    default:
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(['error' => 'Solicitud no soportada']);
        break;
}
