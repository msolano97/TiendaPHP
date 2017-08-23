<?php
	require 'src/conexion.php';
	include 'src/funciones.php';
	
        if(isset($_POST['user_id'])) {
           $user_id = ($_POST['user_id']);
            $token = ($_POST['token']);
            $password = ($_POST['password']);
            $con_password = ($_POST['con_password']);

            if(validaPassword($password, $con_password))
            {

                $pass_hash = hashPassword($password);

                if(cambiaPassword($pass_hash, $user_id, $token))
                {
                    echo "<div style='border: 2px solid black; padding: 20px; background: #FFCC66; border-radius: 20px;  margin:60px; text-align:center;'>Contrase&ntilde;a Modificada <a href='index.php' >Iniciar Sesión</a></div>";
                }
                else 
                {
                    echo "Error al modificar contrase&ntilde;a";
                }
            }
            else
            {
                echo 'Las contraseñas no coinciden';
            } 
        }else {
            header('Location: index.php');
        }
            
        
	
?>
