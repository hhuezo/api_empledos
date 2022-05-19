<?php
    class db{
        private $dbHost = 'localhost';
        private $dbUser= 'root';
        private $dbPass = '';
        private $dbName = 'documento';

        // conexion

        public function conexion()
        {
            $mysqlConnet = "mysql:host=$this->dbHost;dbname=$this->dbName";
            $conexion = new PDO( $mysqlConnet,$this->dbUser,$this->dbPass);
            $conexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

            return $conexion;
        }
    }


