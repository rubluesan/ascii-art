<?php

$rutaImagen = __DIR__ ."/files/".$_FILES['imagen']['name'];
$info = pathinfo(__DIR__ ."/output/".$_FILES['imagen']['name']);
$rutaOutSinExtension = $info['dirname'].'/'.$info['filename'];

move_uploaded_file($_FILES['imagen']['tmp_name'],  $rutaImagen);

//Fichero en el que deseamos guardar el resultado
    $fp = fopen($rutaOutSinExtension.".txt", "w");
    
    //imagen que queremos leer (hay que tener gd.lib instalada y activa)
    $img = imagecreatefrompng($rutaImagen);
    
    //Obtenemos el tamaño
    $w = imagesx($img); //ancho
    $h = imagesy($img); //alto
    $scale = 6;
    $fixAncho = 2.2; // ancho * 2 ya que los caracteres suelen ser el doble de alto que de ancho

    for($y = 0; $y < $h; $y+=$scale*$fixAncho) {
        for($x = 0; $x < $w; $x+=$scale) {
            $rgb = imagecolorat($img, $x, $y);

            // variables
            $luminosidad = 0;
            $caracter = "a";

            //Valor de las componentes RGB de cada pixel
            $r = $rgb >> 16;
            $g = $rgb >> 8 & 255;
            $b = $rgb & 255;

            $luminosidad = 0.299*$r+0.587*$g+0.114*$b;

            //Elegir el caracter según la luminosidad del pixel y escribir en el fichero
            if ($luminosidad <= 60) {
                $caracter = " ";
            }elseif ($luminosidad <= 120) {
                $caracter = ".";
            }elseif ($luminosidad <= 180) {
                $caracter = "o";
            }else{
                $caracter = "@";
            }
            
            fwrite($fp, $caracter);
        }
        fwrite($fp, "\n");
    }

    fclose($fp);

    echo "<a href='descargar_ascii.php?file=".$rutaOutSinExtension.".txt'> Descargar Archivo </a>";
