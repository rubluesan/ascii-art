<?php

session_start();

$ascii = $_SESSION['ascii_result'];

// Limpiamos la sesión después de la descarga
unset($_SESSION['ascii_result']);

header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename="ascii_art.txt"');
echo $ascii;
exit;

?>
