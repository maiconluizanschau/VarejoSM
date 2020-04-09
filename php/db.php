<?php

	$servername = "mysql20-farm70.uni5.net";
    $username = "varejosm";
    $password = "8Q6ukHFb8edN";
    $dbname = "varejosm";

    $nome = $descricao = $telefone = $celular = $atividades = $imagem = $website = "";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
	
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
	}	
?>