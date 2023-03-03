<?php
    $host="localhost";
    $user="root";
    $password="";
    $db="globaldotcom";

    $connection = new mysqli($host, $user, $password, $db);

    if(!$connection){
        die("Connection failed: ".mysqli_connect_error());//die() é uma função que imprime uma mensagem e termina o script
    }
?>