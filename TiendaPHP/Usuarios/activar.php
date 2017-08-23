<?php
	require 'src/conexion.php';
        include 'src/funciones.php';
	
	if(isset($_GET["id"]) AND isset($_GET['val']))
	{	
		$idUsuario = $_GET['id'];
		$token = $_GET['val'];
		
		$msg = validaIdToken($idUsuario, $token);
	}
?>
<html>
    <head>
            <title>Registro</title>
            <link rel="stylesheet" href="css/bootstrap.min.css" >
            <link rel="stylesheet" href="css/bootstrap-theme.min.css" >
            <script src="js/bootstrap.min.js" ></script>

    </head>
    <body>
        <div class="container">
            <div class="jumbotron">
                <span style='border: 2px solid black; padding: 20px; background: #FFCC66; border-radius: 20px; margin:50px;'><?php echo $msg; ?><a class="btn btn-primary btn-lg" href="index.php" role="button">Iniciar Sesi&oacute;n</a></span>
            </div>
        </div>
    </body>
</html>