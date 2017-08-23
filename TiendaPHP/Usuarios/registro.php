<?php
	require 'src/conexion.php';
        include 'src/funciones.php';
 
	$errors = array();
	 
        
	if(!empty($_POST))
	{
            $nombre = $_POST['nombre'];
            $usuario = $_POST['usuario'];
            $password = $_POST['password'];
            $con_password = $_POST['con_password'];
            $email = $_POST['email'];
            $activo = 0;
            $tipo_usuario = 2;
    
		
            if(isNull($nombre, $usuario, $password, $con_password, $email))
            {
                    $errors[] = "Debe llenar todos los campos";
            }

            if(!isEmail($email))
            {
                    $errors[] = "Dirección de correo inválida";
            }

            if(!validaPassword($password, $con_password))
            {
                    $errors[] = "Las contraseñas no coinciden";
            }		

            if(usuarioExiste($usuario))
            {
                    $errors[] = "El nombre de usuario $usuario ya existe";
            }

            if(emailExiste($email))
            {
                    $errors[] = "El correo electronico $email ya existe";
            }
		
            if(count($errors) == 0){
                
                $pass_hash = hashPassword($password);
                $token = generarToken();

                $registro = registraUsuario($usuario, $pass_hash, $nombre, $email, $activo, $token, $tipo_usuario);
                if($registro > 0){	
                    $sql = ("SELECT id FROM usuarios WHERE usuario = '$usuario'");
                    $stmt =$pdo->query($sql); 
                    $row = $stmt->fetch(PDO::FETCH_BOTH);
                    $id = $row['id'];
                    $url = 'http://'.$_SERVER["SERVER_NAME"].'/TiendaPHP/TiendaPHP/Usuarios/activar.php?id='.$id.'&val='.$token;

                    $asunto = 'Activar Cuenta - TiendaPHP';
                    $cuerpo = "<span style='border: 2px solid black; padding: 20px; background: #FFCC66; border-radius: 20px;'>Estimado $nombre:  Para continuar con el proceso de registro, haga click en el siguiente link <a href='$url'>Activar Cuenta</a></span>";
                    
                    if(enviarEmail($email, $nombre, $asunto, $cuerpo)){

                        echo "<div style='border: 2px solid black; padding: 20px; background: #FFCC66; border-radius: 20px; margin:60px; text-align:center;'>Para terminar el proceso de registro siga las instrucciones que le hemos enviado la direccion de correo electronico: <b>$email</b><a href='index.php' > Iniciar Sesion</a></span>";
                        exit;
                        } else {
                        $errors[] = "Error al enviar Email";
                        }        
                    } else {
                    $errors[] = "Error al Registrar";
                    
                }			
            }
	}
?>
<html>
    <head>
        <title>Registro</title>

        <link rel="stylesheet" href="../css/bootstrap.min.css" >
        <link rel="stylesheet" href="../css/bootstrap-theme.min.css" >
        <script src="../js/bootstrap.min.js" ></script>
    </head>
	
    <body>
        <div class="container">
            <div id="signupbox" style="margin-top:50px" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="panel-title">Reg&iacute;strate</div>
                        <div style="float:right; font-size: 85%; position: relative; top:-10px"><a id="signinlink" href="index.php">Iniciar Sesi&oacute;n</a></div>
                    </div>  

                    <div class="panel-body" >
                        <form id="signupform" class="form-horizontal" role="form" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" autocomplete="off">

                            <div id="signupalert" style="display:none" class="alert alert-danger">
                                <p>Error:</p>
                                <span></span>
                            </div>

                            <div class="form-group">
                                <label for="nombre" class="col-md-3 control-label">Nombre:</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="nombre" placeholder="Nombre" value="<?php if(isset($nombre)) echo $nombre; ?>" required >
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="usuario" class="col-md-3 control-label">Usuario</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="usuario" placeholder="Usuario" value="<?php if(isset($usuario)) echo $usuario; ?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                    <label for="password" class="col-md-3 control-label">Password</label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                                    </div>
                            </div>

                            <div class="form-group">
                                <label for="con_password" class="col-md-3 control-label">Confirmar Password</label>
                                <div class="col-md-9">
                                    <input type="password" class="form-control" name="con_password" placeholder="Confirmar Password" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-md-3 control-label">Email</label>
                                <div class="col-md-9">
                                    <input type="email" class="form-control" name="email" placeholder="Email" value="<?php if(isset($email)) echo $email; ?>" required>
                                </div>
                            </div>						
                            <div class="form-group">                             
                                <div class="col-md-offset-3 col-md-9">
                                    <button id="btn-signup" type="submit" class="btn btn-info"><i class="icon-hand-right"></i>Registrar</button> 
                                </div>
                            </div>
                        </form>
                        <?php resultadoBloque($errors); ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>