<?php

$txtID=(isset($_POST['txtID']))?$_POST['txtID']:"";
$txtTitulo=(isset($_POST['txtTitulo']))?$_POST['txtTitulo']:"";
$txtFecha=(isset($_POST['txtFecha']))?$_POST['txtFecha']:"";
$txtNombreAutor=(isset($_POST['txtNombreAutor']))?$_POST['txtNombreAutor']:"";
$txtTexto=(isset($_POST['txtTexto']))?$_POST['txtTexto']:"";
$txtImagen=(isset($_FILES['txtImagen']['name']))?$_FILES['txtImagen']['name']:"";
$accion=(isset($_POST['accion']))?$_POST['accion']:"";

include('../DB/db.php');

switch($accion){

        case "Agregar":

            $sentenciaSQL= $conexion->prepare("INSERT INTO articulos (titulo_articulo, fecha, autor_articulo, texto_articulo, imagen) VALUES (:titulo_articulo, :fecha, :autor_articulo, :texto_articulo :imagen);");
            $sentenciaSQL->bindParam(':titulo_articulo',$txtTitulo);
            $sentenciaSQL->bindParam(':fecha',$txtFecha);
            $sentenciaSQL->bindParam(':autor_articulo',$txtNombreAutor);
            $sentenciaSQL->bindParam(':texto_articulo',$txtTexto);


            $fecha= new DateTime();
            $nombreArchivo = ($txtImagen!="")?$fecha->getTimestamp()."_".$_FILES["txtImagen"]["name"]:"imagen.jpg";

            $tmpImagen=$_FILES["txtImagen"]["tmp_name"];

            if($tmpImagen!= ""){
                move_uploaded_file($tmpImagen,"../../img/".$nombreArchivo);
            }
            $sentenciaSQL->bindParam(':imagen',$nombreArchivo);
            $sentenciaSQL->execute();
            header("Location:Articulos.php");
            break;

        case "Modificar":

            $sentenciaSQL= $conexion->prepare("UPDATE articulos SET titulo_articulo=:titulo_articulo WHERE id=:id");
            $sentenciaSQL->bindParam(':titulo_articulo',$txtTitulo);
            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->execute();

            if($txtImagen!=""){

            $fecha= new DateTime();
            $nombreArchivo = ($txtImagen!="")?$fecha->getTimestamp()."_".$_FILES["txtImagen"]["name"]:"imagen.jpg";

            $tmpImagen=$_FILES["txtImagen"]["tmp_name"];
            move_uploaded_file($tmpImagen,"../../img/".$nombreArchivo);

            $sentenciaSQL= $conexion->prepare("SELECT imagen FROM articulos WHERE id=:id");
            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->execute();
            $Post=$sentenciaSQL->fetch(PDO::FETCH_LAZY);

            if(isset($Post["imagen"]) && ($Post["imagen"]!="imagen.jpg")){

                if(file_exists("../../img/".$Post["imagen"])){

                    unlink("../../img/".$Post["imagen"]);
                }

            }

            $sentenciaSQL= $conexion->prepare("UPDATE articulos SET imagen=:imagen WHERE id=:id");
            $sentenciaSQL->bindParam(':imagen',$nombreArchivo);
            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->execute();

            }
            header("Location:Articulos.php");
            break;

        case "Cancelar":
            header("Location:Articulos.php");
            break;

        case "Seleccionar":
            $sentenciaSQL= $conexion->prepare("SELECT * FROM articulos WHERE id=:id");
            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->execute();
            $Post=$sentenciaSQL->fetch(PDO::FETCH_LAZY);

            $txtTitulo=$Post['titulo_articulo'];
            $txtImagen=$Post['imagen'];
            break;

        case "Borrar":

            $sentenciaSQL= $conexion->prepare("SELECT imagen FROM articulos WHERE id=:id");
            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->execute();
            $Post=$sentenciaSQL->fetch(PDO::FETCH_LAZY);

            if(isset($Post["imagen"]) && ($Post["imagen"]!="imagen.jpg")){

                if(file_exists("../../img/".$Post["imagen"])){

                    unlink("../../img/".$Post["imagen"]);
                }

            }

            $sentenciaSQL= $conexion->prepare("DELETE FROM articulos WHERE id=:id");
            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->execute();

            header("Location:Articulos.php");
            break;

}

$sentenciaSQL= $conexion->prepare("SELECT * FROM articulos");
$sentenciaSQL->execute();
$listaPost=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../Images/favicon-16x16.png" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/estilos2.css"> 

    <title>Administrador</title>

</head>
<body>
<?php $url= "http://".$_SERVER['HTTP_HOST']."/SERCHTEC-GITHUB/Index.php" ?>

<header class="hero">
        <nav class="nav container">
            <div class="nav__logo">
                <h2 class="nav__title">SERCHTEC
                    <img src="../Images/glasses.svg" class="icon__logo">

                </h2>

            </div>

            <ul class="nav__link nav__link--menu">

                <li class="nav__items">
                    <a class="nav__links" href="../Inicio.php">Inicio Administrador</a>
                </li>

                <li class="nav__items">
                    <a href="<?php echo $url; ?>" class="nav__links">Blog SerchTec</a>
                </li>

                <li class="nav__items">
                    <a href="./Cerrar.php" class="nav__links">Cerrar sesion</a>
                </li>


                <img src="./images/close.svg" alt="" class="nav__close">
            </ul>

            <div class="nav__menu">

                <img src="./images/menu_cel.svg" alt="" class="nav__img">

            </div>

        </nav>

        <section class="hero__container container">
            <h1 class="hero__title">A continuación podrás ingresar, modificar o eliminar un articulo de la sección que desees </h1>
        </section>

    </header>

<div class="col-md-5">

    <div class="card">

        <div class="card-header">
            Datos del Post
        </div>

        <div class="card-body">

        <form method="POST" enctype="multipart/form-data">

    <div class = "form-group">
    <label for="txtID">ID: </label>
    <input type="text" required readonly  class="form-control" value="<?php echo $txtID; ?>" name="txtID" id="txtID" placeholder="Ingrese el ID del articulo">
    </div>

    <div class = "form-group">
    <label for="txtTitulo">Titulo del articulo:</label>
    <input type="text" required class="form-control" value="<?php echo $txtTitulo; ?>" name="txtTitulo" id="txtTitulo" placeholder="Ingrese el titulo del articulo">
    </div>

    <div class = "form-group">
    <label for="txtFecha">Fecha de redaccion:</label>
    <input type="text" required class="form-control" value="<?php echo $txtFecha; ?>" name="txtFecha" id="txtFecha" placeholder="Ingrese la fecha de redacción del articulo">
    </div>

    <div class = "form-group">
    <label for="txtNombreAutor">Nombre del o los autores:</label>
    <input type="text" required class="form-control" value="<?php echo $txtNombreAutor; ?>" name="txtNombreAutor" id="txtNombreAutor" placeholder="Ingrese el nombre o los nombres de los autores del articulo">
    </div>

    <div class = "form-group">
    <label for="txtTexto">Texto del articulo:</label>
    <textarea type="text" required class="form-control" value="<?php echo $txtTexto; ?>" name="txtTexto" id="txtTexto" placeholder="Ingrese el texto del articulo"></textarea>
    </div>

    <div class = "form-group">

    <label for="txtImagen">Imagen del articulo</label>

</br>

    <?php if($txtImagen!=""){ ?>

    <img src="../../img/<?php echo $txtImagen;?>" width="50" alt="" srcset="">

    <?php } ?>

    <input type="file" class="form-control" name="txtImagen" id="txtImagen" placeholder="Ingrese el nombre del usuario">
    </div>

    <div class="btn-group" role="group" aria-label="">

    <button type="submit" name="accion" <?php echo ($accion=="Seleccionar")?"disabled":"";?>value="Agregar" class="btn btn-success">Agregar</button>
    <button type="submit" name="accion" <?php echo ($accion!="Seleccionar")?"disabled":"";?>value="Modificar" class="btn btn-warning">Modificar</button>
    <button type="submit" name="accion" <?php echo ($accion!="Seleccionar")?"disabled":"";?>value="Cancelar" class="btn btn-info">Cancelar</button>

    </div>

    </form>

        </div>

    </div>

</div>

<div class="col-md-7">

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Titulo</th>
            <th>Fecha</th>
            <th>Autor-Autores</th>
            <th>Texto</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($listaPost as $post){ ?>
        <tr>
            <td><?php echo $post['id']; ?></td>
            <td><?php echo $post['titulo_articulo']; ?></td>
            <td><?php echo $post['fecha']; ?></td>
            <td><?php echo $post['autor_articulo']; ?></td>
            <td><?php echo $post['texto_articulo']; ?></td>
            <td>
            <audio controls>
            <source src="../../img/<?php echo $post['imagen']; ?>" width="50" alt="" srcset="">
            </audio>



            </td>

            <td>

            <form method="post">

                <input type="hidden" name="txtID" id="txtID" value="<?php echo $post['id']; ?>"/>

                <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary"/>

                <input type="submit" name="accion" value="Borrar" class="btn btn-danger"/>



            </form>

            </td>

        </tr>
        <?php }?>

    </tbody>
</table>

</div>


</body>
</html>






