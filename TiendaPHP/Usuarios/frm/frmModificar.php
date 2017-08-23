<?php
	require 'src/conexion.php';
	
	$user_id = $_SESSION['id_usuario'];
	
	$sql = "SELECT * FROM usuarios WHERE id = '$user_id'";
	$resultado = $pdo->query($sql);
	$row = $resultado->fetch(PDO::FETCH_BOTH);
        
        if(!empty($_POST))
	{
        $id = $_POST['id'];
        $nombre = $_POST['txtNombre'];
        $msg = actualizarUsuario($nombre, $id);
        header("Location: bienvenido.php?frm=frmModificar&msg=$msg");
        }
        
        
	
?>
    <div class="container">
            <div class="row">
                    <h3 style="text-align:center">Modificar Usuario</h3>
            </div>

        <form class="form-horizontal" method="POST" action="<?php $_SERVER['PHP_SELF'] ?>" autocomplete="off">
                    <div class="form-group">
                            <label for="nombre" class="col-sm-2 control-label">Nombre: </label>
                            <div class="col-sm-10">
                                    <input type="text" class="form-control" id="nombre" name="txtNombre" placeholder="Nombre" value="<?php echo $row['nombre']; ?>" required>
                            </div>
                    </div>

                    <input type="hidden" id="id" name="id" value="<?php echo $row['id']; ?>" />

                    <div class="form-group">
                            <label for="email" class="col-sm-2 control-label">Email: </label>
                            <div class="col-sm-10">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?php echo $row['correo']; ?>"  disabled>
                            </div>
                    </div>

                    <div class="form-group">
                            <label for="Usuario" class="col-sm-2 control-label">Usuario: </label>
                            <div class="col-sm-10">
                                    <input type="text" class="form-control" id="email" name="usuario" placeholder="Usuario" value="<?php echo $row['usuario']; ?>"  disabled>
                            </div>
                    </div>

                    <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                    <a href="index.php" class="btn btn-default">Regresar</a>
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                    </div>

            </form>
        <div class="row">
            <?php
                if(isset($_GET['msg'])){
                    echo $_GET['msg'];
                }

            ?>
        </div>
    </div>
</html>

