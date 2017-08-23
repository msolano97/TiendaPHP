<?php
	require 'src/conexion.php';
	include 'src/funciones.php';
	
	session_start();
	
	if(isset($_SESSION["id_usuario"])){
		header("Location: bienvenido.php");
	}
	
	$errors = array();
	
	if(!empty($_POST))
	{
            
            $email = $_POST['email'];

            if(!isEmail($email))
            {
                $errors[] = "Debe ingresar un correo electronico valido";
            }

            if(emailExiste($email))
            {			
                $sql = ("SELECT id, nombre FROM usuarios WHERE correo = '$email'");
                $stmt =$pdo->query($sql); 
                $row = $stmt->fetch(PDO::FETCH_BOTH);
                
                $id = $row['id'];
                $nombre = $row['nombre'];

                $token = generaTokenPass($id);

                $url = 'http://'.$_SERVER["SERVER_NAME"].'/TiendaPHP/TiendaPHP/Usuarios/cambiaPass.php?user_id='.$id.'&token='.$token;

                $asunto = 'Recuperar Password - TiendaPHP';
                $cuerpo = "<div style='border: 2px solid black; padding: 20px; background: #FFCC66; border-radius: 20px;'>Hola $nombre: Se ha solicitado un reinicio de contrase&ntilde;a.</div><br/><br/><span style='border: 2px solid black; padding: 20px; background: #FFCC66; border-radius: 20px;'>Para restaurar la contrase&ntilde;a, visita la siguiente direcci&oacute;n: <a href='$url'>Cambiar Contrase&ntilde;a</a></span>";

                if(enviarEmail($email, $nombre, $asunto, $cuerpo)){
                        echo "<div style='border: 2px solid black; padding: 20px; background: #FFCC66; border-radius: 20px;  margin:60px; text-align:center;'>Hemos enviado un correo electronico a la dirección <b>$email</b> para restablecer tu contraseña. <a href='index.php' > Iniciar Sesion</a></div>";
                        exit;
                }
                } else {
                $errors[] = "La direccion de correo electronico no existe";
            }
	}
?>
<html>
    <head>
        <title>Recuperar Password</title>

        <link rel="stylesheet" href="../css/bootstrap.min.css" >
        <link rel="stylesheet" href="../css/bootstrap-theme.min.css" >
        <script src="../js/bootstrap.min.js" ></script>
    </head>
	
    <body>
        <div class="container">    
            <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
                <div class="panel panel-info" >
                    <div class="panel-heading">
                        <div class="panel-title">Recuperar Password</div>
                        <div style="float:right; font-size: 80%; position: relative; top:-10px"><a href="index.php">Iniciar Sesi&oacute;n</a></div>
                    </div>     

                    <div style="padding-top:30px" class="panel-body" >

                        <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>

                        <form id="loginform" class="form-horizontal" role="form" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" autocomplete="off">

                            <div style="margin-bottom: 25px" class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                <input id="email" type="email" class="form-control" name="email" placeholder="email" required>                                        
                            </div>

                            <div style="margin-top:10px" class="form-group">
                                <div class="col-sm-12 controls">
                                    <button id="btn-login" type="submit" class="btn btn-success">Enviar</a>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12 control">
                                    <div style="border-top: 1px solid#888; padding-top:15px; font-size:85%" >
                                        No tiene una cuenta! <a href="registro.php">Registrate aquí</a>
                                    </div>
                                </div>
                            </div>    
                        </form>
                        <?php echo resultadoBloque($errors); ?>
                    </div>                     
                </div>  
            </div>
        </div>
    </body>
</html>

