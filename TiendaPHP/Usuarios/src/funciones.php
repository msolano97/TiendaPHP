<?php

function isNull($nombre, $usuario, $password, $con_password, $email){
    if(strlen(trim($nombre)) < 1 || strlen(trim($usuario)) < 1  || 
       strlen(trim($password))<1 || strlen(trim($con_password))<1 || 
       strlen(trim($email))<1){
        return TRUE;
    }else{
        return FALSE;
    }
        
}

function isEmail($email){
    if (filter_var($email, FILTER_VALIDATE_EMAIL)){
        return TRUE;
    }else{
        return FALSE;
    }   
}

function validaPassword($var1, $var2){
    if (strcmp($var1, $var2) !== 0){
        return FALSE;
    }else {
        return TRUE;
    }
}

function minMax($min, $max, $valor){
    if(strlen(trim($valor)) < $min){
        return TRUE;
    }else if(strlen(trim($valor)) > $max){
        return TRUE;
    }else {
        return FALSE;
    }
}

function usuarioExiste($usuario){
    include 'conexion.php';
    
    $sql= "SELECT id FROM usuarios WHERE usuario = :usuario"; 
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR); 
    $stmt->execute();
    $total=$stmt->rowCount();
    
    if ($total==1){
        return TRUE;
    }else{
        return FALSE;
    } 
    $pdo = null;
    $stmt = null;
}

function emailExiste($email){
    include 'conexion.php';
    
    $sql= "SELECT id FROM usuarios WHERE correo = :email"; 
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR); 
    $stmt->execute();
    $total=$stmt->rowCount();
    
    if ($total==1){
        return TRUE;
    }else{
        return FALSE;
    } 
    $pdo = null;
    $stmt = null;
}

function generarToken(){
    $gen = md5(uniqid(mt_rand(),FALSE));
    return $gen;
}

function hashPassword($password){
    $hash = password_hash($password, PASSWORD_DEFAULT);
    return $hash;
}

function registraUsuario($usuario, $pass_hash, $nombre, $email, $activo, $token, $tipo_usuario){
    include 'conexion.php';
    
    $sql = "INSERT INTO usuarios (usuario, password, nombre, correo, activacion, token, id_tipo) "
            . "VALUES (:usuario, :pass_hash, :nombre, :email, :activo, :token, :tipo_usuario)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
    $stmt->bindParam(':pass_hash', $pass_hash, PDO::PARAM_STR);
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':activo', $activo, PDO::PARAM_INT);
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->bindParam(':tipo_usuario', $tipo_usuario, PDO::PARAM_INT);
    $stmt->execute();
    return 1;
}

function enviarEmail($email_to, $nombre, $asunto, $cuerpo){		
    	
        require("PHPMailer/phpmailer.php");
        require("PHPMailer/smtp.php");
        require ("PHPMailer/info.php");
            

        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "ssl";
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 465;
        $mail->Username = $email;
        $mail->Password = $clave;

        $mail->From = $email;
        $mail->FromName = "Soporte";
        $mail->Subject = $asunto;
        $mail->AltBody = "";
        $mail->MsgHTML($cuerpo);

        $mail->AddAddress($email_to, $nombre);
        $mail->IsHTML(true);
    if (!$mail->send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
        echo "";
        return TRUE;
    }
}    

function resultadoBloque($errors){
    if(count($errors) > 0 ){
        echo "<div id='error' class='alert alert-danger' role='alert'><a href='#' onclick=\"showHide('error');\"></a><ul>";
        foreach ($errors as $error){
            echo "<li>".$error."</li>";
        }
        echo "</ul>";
        echo "</div>";
    }
}

function validaIdToken($id, $token){
    include 'conexion.php';
    
    $sql= "SELECT activacion FROM usuarios WHERE id = :id AND token = :token LIMIT 1"; 
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
    $stmt->bindParam(':token', $token, PDO::PARAM_INT);
    $stmt->execute();
    $total=$stmt->rowCount();
    
    if ($total > 0){
        $stmt->bindColumn('activacion',$activacion);
        $stmt->fetch(PDO::FETCH_NUM);
        if($activacion == 1){
            $msg = "La cuenta ya se activo anteriormente.";
        }else{
            if(activarUsuario($id)){
                $msg = 'Cuenta activada.';
            }else{
                $msg = 'Error al activar la cuenta';
            }
        }
    }else{
        $msg = 'No existe el registro para activar';
    } 
    return $msg;
    $pdo = null;
    $stmt = null;
}

function activarUsuario($id){
    include 'conexion.php';
    
    $sql= "UPDATE usuarios SET activacion=1 WHERE id = :id"; 
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
    $resultado=$stmt->execute();
    return $resultado;
    
    $pdo = null;
}

function isNullLogin($usuario, $password){
    if (strlen(trim($usuario)) < 1 || strlen(trim($password)) < 1){
        return true;
    }else{
        return FALSE;
    }
}

function login($usuario, $password){
    include 'conexion.php';
    
    $sql= "SELECT id, id_tipo, password FROM usuarios WHERE usuario = :usuario || correo = :usuario LIMIT 1"; 
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR); 
    $stmt->execute();
    $total=$stmt->rowCount();
    
    if($total > 0){
        if(isActivo($usuario)){
            $stmt->bindColumn('id',$id);
            $stmt->bindColumn('id_tipo',$id_tipo);
            $stmt->bindColumn('password',$passwr);
            $stmt->fetch(PDO::FETCH_BOTH);
            $validaPass = password_verify($password, $passwr);
            if($validaPass){
                $_SESSION['id_usuario'] = $id;
                $_SESSION['tipo_usuario'] = $id_tipo;
                
                header("location: bienvenido.php");
            }else{
                $errors = "La contraseña es incorrecta";
            }
        } else {
            $errors = "El usuario no está activo";
        }
    }else{
        $errors = "El nombre de usuario o correo electrónico no existe";
    }
    return $errors;
}

function isActivo($usuario){
    include "conexion.php";
    
    $sql= "SELECT activacion FROM usuarios WHERE usuario = :usuario || correo = :usuario LIMIT 1"; 
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR); 
    $stmt->execute();
    $stmt->bindColumn('activacion',$activacion);
    $stmt->fetch(PDO::FETCH_NUM);
    
    if($activacion == 1){
        return TRUE;
    }else{
        return FALSE;
    }
}

/*function getValor($campo, $campoWhere, $valor){
    include "conexion.php";
    
    $sql = "SELECT $campo FROM usuarios WHERE $campoWhere = :valor LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':valor', $usuario, PDO::PARAM_STR);
    $stmt->execute();
    $total=$stmt->rowCount();
    if($total > 0){
        $stmt->bindColumn("$campo", $_campo);
        $stmt->fetch(PDO::FETCH_BOTH);
        return $_campo;
    }else{
        
    }
}*/

function generaTokenPass($user_id){
    include 'conexion.php';
    
    $token = generarToken();
    
    $sql= "UPDATE usuarios SET token_password = :token, password_request=1 WHERE id = :id"; 
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT); 
    $stmt->execute();
    return $token;
}

function verificaTokenPass($user_id, $token){
    include 'conexion.php';
    
    $sql= "SELECT activacion FROM usuarios WHERE id = :id AND token_password = :token AND password_request = 1 LIMIT 1"; 
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT); 
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->execute();
    $total=$stmt->rowCount();
    
    if ($total > 0){
        $stmt->bindColumn('activacion',$activacion);
        $stmt->fetch(PDO::FETCH_NUM);
        if($activacion == 1){
            return TRUE;
        }else{
            return FALSE;
        }
        $pdo = null;
        $stmt = null;
    }
}

function cambiaPassword($password, $user_id, $token){
    include 'conexion.php';
    
    $sql= "UPDATE usuarios SET password = :password, token_password = '', password_request=0 WHERE id = :id AND token_password = :token"; 
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':password', $password, PDO::PARAM_INT);
    $stmt->bindParam(':token', $token, PDO::PARAM_INT);
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT); 
    $stmt->execute();
    return $token;
}

function actualizarUsuario($nombre, $id){
    require 'src/conexion.php';


    $sql = "UPDATE usuarios SET nombre='$nombre' WHERE id = '$id'";
    $stmt = $pdo->query($sql);
    $stmt->execute();
    $total=$stmt->rowCount();
    
    if ($total == 0) {
        $msg = '<h3 style="text-align:center; color:green">Los datos se actualizaron exitosamente</h3>';
    }else {
        $msg = '<h3 style="text-align:center color:red">No se pudo actualizar correctamente</h3>';
    }
    
    return $msg;
}
    
