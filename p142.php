<?php 
$casos = trim(fgets(STDIN));
while($casos>0){
$linea = trim(fgets(STDIN));

 if (es_ISBN(get_dig10($linea),$linea))
    echo "CORRECTO\n";
    else
    echo "INCORRECTO\n";
$casos--;
}

function get_dig10($datos){
    $position =1;
    $resultado=0;
    $grupo=1;
    $guiones=0;
    $espacios=0;
    for($i=0;strlen($datos)-1>$i;$i++){
        if($datos[$i]!=' ' and $datos[$i]!='-')
            if(is_numeric($datos[$i])){
        $resultado+= $datos[$i]*$position;
        $position++; 
    }
    else
    return -1;
    else {$grupo++;
        if($datos[$i]==' ')
            $espacios++;
            else
            $guiones++;
    }
        
    }
if($grupo==4 or $grupo==1)
if($guiones==0 and $espacios==0)
return $resultado;
else
if(($guiones>0 and $espacios==0) or ($guiones==0 and $espacios>0))
return $resultado;
else return -1;
else
return -1;
}

function es_ISBN($suma,$linea){    
    if($suma==-1)
    return false;
$dig10 = $linea[strlen($linea)-1];
return $retorno = ($suma%11== $dig10)? true:false;
}
?>