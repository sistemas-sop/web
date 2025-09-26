<?php
// Configuración de base de datos MySQL.
// Ajusta estos valores al instalar en tu servidor o XAMPP.
function db(){
  $host = 'localhost';
  $db   = 'tickets_db';
  $user = 'root';
  $pass = '';
  $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
  $opt = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
  ];
  return new PDO($dsn, $user, $pass, $opt);
}
