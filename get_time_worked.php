<?php
function calculartiempo($start, $end){
$fechaMayor = $end;
$fechaMenor = $start;
$segundosfechaMayor = strtotime($fechaMayor);
$segundosfechaMenor = strtotime($fechaMenor);
$segundos = $segundosfechaMayor - $segundosfechaMenor;
$minutos = intdiv($segundos,60);
$horas = intdiv($minutos,60);
$dias = $horas / 24;
 return "".$horas.":".(($minutos)%60).":".(($segundos)%60);
}
//echo calculartiempo("06:30:45","12:00:00");
?>