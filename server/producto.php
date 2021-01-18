<?php

require_once(__DIR__.'/conexion.php');

$getOption = trim($_GET['option']);

//READ
if($getOption=='read'):
    $query = "SELECT * FROM productos";
    $result = $mysqli->query($query);

    if(!$result){
        $data['message'] = "Mensaje: No Existe registro";
        $data['response'] = false;
        die(json_encode($data));
    }

    $data['response'] = $result->fetch_all(MYSQLI_ASSOC);
endif;

//CREATE OR UPDATE
if($getOption=='create'):
    if($_POST['id']!=''){
        //UPDATE
        $query = "UPDATE productos SET name='".$_POST['name']."',price='".$_POST['price']."',stock='".$_POST['stock']."' WHERE id='".$_POST['id']."'";
    }else{
        //CREATE
        $query = "INSERT INTO productos (name, price, stock) VALUES ('".$_POST['name']."','".$_POST['price']."','".$_POST['stock']."')";
    }
    
    if ($mysqli->query($query)) {
        $data['response'] = true;
    } else {
        $data['response'] = false;
        $data['message'] = 'Error: ' . $mysqli->error;
    }
endif;

if($getOption=='delete'):
    if($_POST['id']!=''){
        //DELETE
        $query = "DELETE FROM productos WHERE id='".$_POST['id']."'";
    }
    
    $mysqli->query($query);
    
endif;

mysqli_free_result($resultado);
mysqli_close($enlace);

echo json_encode($data);

