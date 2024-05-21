<?php
class Conexion extends PDO{

    private $hostBd = 'localhost';
    private $nombreBd = 'gestion_usuarios';
    private $usuarioBd = 'root';
    private $passwordBd = '';

    public function __construct() {

        try {
            parent::__construct(

                'mysql:host='. $this->hostBd .
                ';dbname='. $this->nombreBd . ';charset=utf8',
                $this->usuarioBd,
                $this->passwordBd, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
            );
        } catch (PDOException $e) {
            echo 'Ocurrio un Error ' . $e->getMessage();
            exit;
        }
    }




}