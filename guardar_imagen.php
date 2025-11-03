<?php

// enfoque diferente, usando sesiones, sin guardar archivos
session_start();

//imagen que queremos leer (hay que tener gd.lib instalada y activa)
// Soporte para JPG y PNG
$info = getimagesize($_FILES['imagen']['tmp_name']);
if ($info['mime'] == 'image/jpeg') {
    $img = imagecreatefromjpeg($_FILES['imagen']['tmp_name']);
} else {
    $img = imagecreatefrompng($_FILES['imagen']['tmp_name']);
}

//Obtenemos el tamaño
$w = imagesx($img); //ancho
$h = imagesy($img); //alto
$scale = 6;
$fixAncho = 2.4; // ancho * 2 ya que los caracteres suelen ser el doble de alto que de ancho

$ascii = '';

for ($y = 0; $y < $h; $y += $scale * $fixAncho) {
    for ($x = 0; $x < $w; $x += $scale) {
        $rgb = imagecolorat($img, $x, $y);

        // variables
        $luminosidad = 0;
        $caracter = "a";

        //Valor de las componentes RGB de cada pixel
        $r = $rgb >> 16;
        $g = $rgb >> 8 & 255;
        $b = $rgb & 255;

        $luminosidad = 0.299 * $r + 0.587 * $g + 0.114 * $b;

        //Elegir el caracter según la luminosidad del pixel y escribir en el fichero
        if ($luminosidad <= 60) $caracter = " ";
        elseif ($luminosidad <= 120) $caracter = ".";
        elseif ($luminosidad <= 180) $caracter = "o";
        else $caracter = "@";

        $ascii .= $caracter;
    }
    $ascii .= "\n";
}

// destruimos la imagen
imagedestroy($img);

// Guardamos el resultado en la sesión
$_SESSION['ascii_result'] = $ascii;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Conversor Image to ASCII art</title>

    <!-- CSS de Materialize -->
    <link rel="stylesheet" href="css/materialize.min.css" />

    <!-- JS de Materialize -->
    <script src="js/materialize.min.js" defer></script>

    <!-- CSS Normalize -->
    <link rel="stylesheet" href="./css/normalize.css">
    <!-- CSS custom -->
    <link rel="stylesheet" href="./css/styles.css">
</head>

<body>
    <header>
        <nav class="blue darken-3">
            <div class="nav-wrapper container">
                <a href="./" class="brand-logo">ASCII Converter</a>
            </div>
        </nav>
    </header>

    <main>
        <div class="container center-align">
            <a href="./descargar_ascii.php" class="btn green waves-effect waves-light">Descargar ASCII</a>
            <a href="./" class="btn blue waves-effect waves-light">Volver</a>
            <pre><?= htmlspecialchars($_SESSION['ascii_result']); ?></pre>
        </div>
    </main>

    <footer class="page-footer blue darken-3">
        <div class="footer-copyright center-align blue darken-3">
            <div class="container">
                © 2025 Conversor ASCII | R. Luengo
            </div>
        </div>
    </footer>
</body>

</html>