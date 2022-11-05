<?php

session_start();

if(!isset($_SESSION['usuario'])){

    header("Location:../Index.php");

}else{

    if($_SESSION['usuario']=="ok"){
        $nombreUsuario=$_SESSION["nombreUsuario"];
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./images/favicon-16x16.png" type="image/x-icon">
    <link rel="stylesheet" href="./css/normalize.css">
    <link rel="stylesheet" href="./css/estilos2.css"> 

    <title>Administrador</title>

</head>
<body>

<?php $url= "http://".$_SERVER['HTTP_HOST']."/SERCHTEC-GITHUB/Index.php" ?>

<header class="hero">
        <nav class="nav container">
            <div class="nav__logo">
                <h2 class="nav__title">SERCHTEC
                    <img src="./Images/glasses.svg" class="icon__logo">

                </h2>

            </div>

            <ul class="nav__link nav__link--menu">

                <li class="nav__items">
                    <a href="#" class="nav__links">Adiministrador de SerchTec</a>
                </li>

                <li class="nav__items">
                    <a class="nav__links" href="Inicio.php">Inicio</a>
                </li>

                <li class="nav__items">
                    <a href="./Sesion/Articulos.php" class="nav__links">Subir Articulos</a>
                </li>

                <li class="nav__items">
                    <a href="<?php echo $url; ?>" class="nav__links">Blog SerchTec</a>
                </li>

                <li class="nav__items">
                    <a href="./Sesion/Cerrar.php" class="nav__links">Cerrar sesion</a>
                </li>


                <img src="./images/close.svg" alt="" class="nav__close">
            </ul>

            <div class="nav__menu">

                <img src="./images/menu_cel.svg" alt="" class="nav__img">

            </div>

        </nav>

        <section class="hero__container container">
            <h1 class="hero__title">Bienvenido al modo Administrador de SerchTec</h1>
        </section>

    </header>

</body>
</html>
