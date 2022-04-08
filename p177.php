<?php
 const a_car = array("q","w","e","r","t","y","u","i","o","p","a","s","d","f","g","h","j","k","l","z","x","c","v","b","n","m",
	"1", "2","3","4","5","6","7","8","9","0","-","_",".");

 $a_casos = trim(fgets(STDIN));
 while($a_casos>0){
 	$v_correo = trim(fgets(STDIN));
 	$v_datos= explode("@" , $v_correo);
 	if (m_valiLocal($v_datos[0]))
 		if(sizeof($v_datos)==2)
 		if(m_valiDominio($v_datos[1]))
 			echo $v_datos[1]."\n";
 		else 
 			echo "DOMINIO INCORRECTO"."\n";
 		else 
 			echo "DOMINIO INCORRECTO"."\n";
 	else echo "USUARIO INCORRECTO"."\n";
 	$a_casos--;
 }

function m_valiLocal($p_local){
	if($p_local!=null)
		if(m_valiPunt($p_local))
		if(m_valiEspa($p_local))
			return true;
		else return false;
		else return false;
	else return false;		
}

function m_valiDominio($p_dominio){
	if($p_dominio!=null)
	if(m_valiCarac($p_dominio))
		if(m_valiPunt($p_dominio))
			return true;
		else
			return false;
	else
		return false;
	else return false;
}

function m_valiCarac($p_dominio){
$v_domiLeng = strlen($p_dominio);
$v_caraLeng = sizeof(a_car);
$v_cont =0;
$v_band = false;
$v_return = true;
for (; $v_domiLeng>$v_cont && $v_return; $v_cont++) { 
	$v_band = false;
	for ($v_cont2 =0;$v_caraLeng>$v_cont2 && !$v_band;$v_cont2++) 
		if(substr($p_dominio,$v_cont,1)==a_car[$v_cont2])
			$v_band = true;

		if(!$v_band)
			$v_return = false;
}

return  $v_return;
}


function m_valiPunt($p_local){
	$len = strlen($p_local);
	$v_return  = true; 
	for($v_cont=0;$len>$v_cont && $v_return;$v_cont++)
		if(substr($p_local, $v_cont,1)==".")
			if($v_cont+1<$len)
				if((substr($p_local, ($v_cont+1),1))!="." )
					$v_return = true; 
				else
					$v_return = false; 
			else
				$v_return = false;
	return $v_return;
}

function m_valiEspa($p_local){
$len = strlen($p_local);
for($v_cont=0;$len>$v_cont;$v_cont++)
if(substr($p_local, $v_cont,1)==" ")
	return false;
return true;
}
?>