<?php
        
        extract($_GET);

	session_start();
	require 'src/conexion.php';
	include 'src/funciones.php';
	
	if(!isset($_SESSION["id_usuario"])){
		header("Location: index.php");
	}
	
	$idUsuario = $_SESSION['id_usuario'];
        $sql = ("SELECT id, nombre FROM usuarios WHERE id = '$idUsuario'");
        $stmt =$pdo->query($sql); 
        $row = $stmt->fetch(PDO::FETCH_BOTH);
?>
 
<html>
    <head>
        <title>Bienvenido</title>
        <link rel="stylesheet" href="../css/bootstrap.min.css" >
        <link rel="stylesheet" href="../css/bootstrap-theme.min.css" >
        <script src="../js/bootstrap.min.js" ></script>

        <style>
                body {
                padding-top: 20px;
                padding-bottom: 20px;
                }
        </style>
    </head>
	
    <body>
        <div class="container">

            <nav class='navbar navbar-default'>
                <div class='container-fluid'>
                    <div class='navbar-header'>
                        <button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#navbar' aria-expanded='false' aria-controls='navbar'>
                            <span class='sr-only'>Men&uacute;</span>
                            <span class='icon-bar'></span>
                            <span class='icon-bar'></span>
                            <span class='icon-bar'></span>
                        </button>
                    </div>

                    <div id='navbar' class='navbar-collapse collapse'>
                            <ul class='nav navbar-nav'>
                                <li class='active'><a href='bienvenido.php'>Inicio</a></li>			
                            </ul>

                            <?php if($_SESSION['tipo_usuario']==1) { ?>
                                <ul class='nav navbar-nav'>
                                    <li><a href='#'>Administrar Usuarios</a></li>                                                               
                                </ul>
                            <?php } ?>
                            <?php if($_SESSION['tipo_usuario']==2) { ?>
                                <ul class='nav navbar-nav'>
                                    <li><a href="?frm=frmModificar">Editar Perfil</a></li>
                                    <li><a href="?frm=frmCambiaPass">Cambiar Contraseña</a></li>
                                </ul>            
                            <?php } ?>

                            <ul class='nav navbar-nav navbar-right'>
                                <li><a href='logout.php'>Cerrar Sesi&oacute;n</a></li>
                            </ul>
                    </div>
                </div>
            </nav>	

            <div class="jumbotron">
                <h2><?php echo 'Bienvenid@ '.utf8_decode($row['nombre']); ?></h1>
                <br />
            </div>
            <section class="container-fluid rounded">
                <div> 
                    <?php
                        if (isset($frm)){
                            require_once 'frm/'. $frm.'.php';
                        }
                    ?>
                </div>
            </section>
        </div>
    </body>
</html>