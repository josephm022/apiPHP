<?php
require 'vendor/autoload.php';

use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\PublicKeyLoader;



// Funciones para generar claves y cifrar/descifrar
function generateRSAKeys() {
    $privateKey = RSA::createKey(2048);
    $publicKey = $privateKey->getPublicKey();

    file_put_contents('private_key.pem', $privateKey);
    file_put_contents('public_key.pem', $publicKey);
}

function encryptPassword($password) {
    $publicKey = file_get_contents('public_key.pem');
    if (!$publicKey) {
        die('Error reading public key');
    }

    $publicKey = PublicKeyLoader::load($publicKey);
    $encryptedPassword = $publicKey->encrypt($password);
    return base64_encode($encryptedPassword);
}

function decryptPassword($encryptedPassword) {
    $privateKey = file_get_contents('private_key.pem');
    if (!$privateKey) {
        die('Error reading private key');
    }

    $privateKey = PublicKeyLoader::load($privateKey);
    $decryptedPassword = $privateKey->decrypt(base64_decode($encryptedPassword));
    return $decryptedPassword;
}

//generateRSAKeys();
