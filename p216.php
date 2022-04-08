<?php
$obBD = connecta();
veriJuegos($obBD);

function connecta(){
$obBD = new baseDatos();
$host = trim(fgets(STDIN));
$user = trim(fgets(STDIN));
$pass = trim(fgets(STDIN));
$nameBd = trim(fgets(STDIN));
$obBD->ingDatos($host,$user,$nameBd,$pass);
return  $obBD;
}

function veriJuegos($obBD){
    $cont=0;
    $array = array();
    $obBD->consulta("select j.id,j.secuencia,concat (u.Nombre,' ',u.Apellidos) as nombre, concat (uI.Nombre,' ',uI.Apellidos) as nombreI 
    from BD_Domino_Juegos j join Usuarios u on j.id_usuario=u.Usuario
    join Usuarios uI on id_invitado=uI.Usuario order by j.id;");
    if($obBD->numeRegistros>0){
        while ( $row = mysqli_fetch_assoc( $obBD->bloque)) {
            $array[$cont]= $row["secuencia"];
            if(duplicidad($array[$cont]))
                echo $row["id"].":".$row["nombre"].":".$row["nombreI"].":"."Ficha Duplicada\n";
            else
                if(mal_secuencia($array[$cont]))
                echo $row["id"].":".$row["nombre"].":".$row["nombreI"].":"."Secuencia Mal\n";
            $cont++;
        }
         
    }
}

function duplicidad($secuencia){
    $datos=explode(" ",$secuencia);
    for($cont=0;count($datos)>$cont;$cont++)
        for($cont2=$cont+1;count($datos)>$cont2;$cont2++)
        if(esDuplicado($datos[$cont],$datos[$cont2]))
            return true;
    return false;
}
function esDuplicado($ficha1,$ficha2){
    if($ficha1 == $ficha2)
    return true;
    else
    if($ficha1[2]== $ficha2[0] and $ficha1[0]== $ficha2[2])
    return true;

    return false;
}



function mal_secuencia($secuencia){
    $datos=explode(" ",$secuencia);
    $cont=0;
        for($cont2=$cont+1;count($datos)>$cont2;$cont2++)
            if($datos[$cont][2] != $datos[$cont2][0])
            return true;
            else
            $cont = $cont2;
    return false;
}


?>

<?php 
class baseDatos
{
	var $conn;
	var $bloque;
	var $numeRegistros;
    var $nameBD;
    var $user;
    var $pass;
    var $host;

    function ingDatos($host,$user,$nameBD,$pass){
        $this->host = $host;
        $this->nameBD = $nameBD;
        $this->user = $user;
        $this->pass = $pass;
    }
	function conecta(){
		$this->conn = mysqli_connect($this->host,$this->user,$this->pass,$this->nameBD);
	}

	function consulta($query){
		$this->conecta();
		$this->bloque = mysqli_query($this->conn, $query);
		$this->numeRegistros=mysqli_num_rows($this->bloque);
		$this->cerrar();
		return $this->bloque;
	}
	function cerrar(){
		mysqli_close($this->conn);
	}

}
?>