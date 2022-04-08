<?php

	$a_casos = trim(fgets(STDIN));
	while ($a_casos>0) {
		$line = trim(fgets(STDIN));
		$v_pildoras= explode(" " , $line);
		despachar($v_pildoras);
		$a_casos--;
	}
function despachar($p_pildoras){
	$v_cont=0; 
	$v_frasco=0; 
	$v_despachos=0; 
	$v_acarreo=0; 
	$v_resu="";
	while($p_pildoras[0]>$v_cont){
		$v_frasco = $v_frasco + $p_pildoras[$v_cont+1];
		$v_despachos++;
		if($v_frasco>=100){
			$v_resu.= $v_despachos." ";
			$v_acarreo = $v_frasco-100;
			$v_frasco = $v_acarreo;
			$v_despachos=0;
		}
		$v_cont++;
	}
	echo (trim($v_resu))."\n";
}
?>