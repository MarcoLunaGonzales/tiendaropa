<?php
set_time_limit(0);

error_reporting(0);

$enlaceCon=mysqli_connect("localhost","root","4868422Marco","kidsplacesiat");
//$enlaceCon=mysqli_connect("localhost","root","12345678","carmelita");
// $enlaceCon=mysqli_connect("localhost","root","4868422Marco","carmelitasiat_temp");
// $enlaceCon=mysqli_connect("localhost","root","12345678","carmelita");
//$enlaceCon=mysqli_connect("localhost","root","12345678","tiendaropa1000");

if (mysqli_connect_errno())
{
    echo "Error en la conexión: " . mysqli_connect_error();
}
mysqli_set_charset($enlaceCon,"utf8");


if (!function_exists('mysqli_result')) {
    function mysqli_result($result, $number, $field=0) {
        mysqli_data_seek($result, $number);
        $row = mysqli_fetch_array($result);
        return $row[$field];
    }
}
?>
