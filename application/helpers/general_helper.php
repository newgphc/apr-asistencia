<?php
function dateToFormat($date)
{
	$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes",utf8_encode("Sábado"));
	$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	return utf8_decode($dias[date('w', $date)]." ".date('d', $date)." de ".$meses[date('n', $date)-1]. " del ".date('Y', $date));
}