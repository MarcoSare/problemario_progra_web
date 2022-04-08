<?php 
$casos = trim(fgets(STDIN));
while($casos>0){
$linea = trim(fgets(STDIN));

 if (es_ISBN(get_dig13($linea),$linea))
    echo "CORRECTO\n";
    else
    echo "INCORRECTO\n";
$casos--;
}

function get_dig13($datos){
    $position =1;
    $resultado=0;
    $grupo=1;
    $guiones=0;
    $espacios=0;
    for($i=0;strlen($datos)-1>$i;$i++){
        if($datos[$i]!=' ' and $datos[$i]!='-')
            if(is_numeric($datos[$i])){
                if($position%2==0)
                $resultado+= $datos[$i]*3;
                else 
                $resultado+= $datos[$i];
        $position++; 
    }
    else
    return -1;

    else 
    {
        $grupo++;
        if($datos[$i]==' ')
            $espacios++;
            else
            $guiones++;
    }
        
    }
    if($grupo==5 or $grupo==1)
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
    $dig13 = $linea[strlen($linea)-1];
    $DC = ($suma%10== 0)? 0:10-$suma%10;
    return ($dig13==$DC)? true:false;
}
?>