<?php

require 'funciones.php';
require 'templates/config/database.php';
require __DIR__ . '/../vendor/autoload.php';

// Conectar a la base de datos
use Model\ActiveRecord;

$db = conectarDB();

ActiveRecord::setDB($db);
