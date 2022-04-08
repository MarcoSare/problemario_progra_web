<?php
$obBD = connecta();
getTables($obBD);
function connecta(){
$obBD = new baseDatos();
$host = trim(fgets(STDIN));
$user = trim(fgets(STDIN));
$pass = trim(fgets(STDIN));
$nameBd = trim(fgets(STDIN));
$obBD->ingDatos($host,$user,$nameBd,$pass);
return  $obBD;
}

function getTables($obBD){
    $cont=0;
    $array = array();
    $obBD->consulta("show tables;");
    if($obBD->numeRegistros>0){
        while ( $row = mysqli_fetch_assoc( $obBD->bloque)) {
            $array[$cont]= $row["Tables_in"."_".$obBD->nameBD];
            $cont++;
        }
        rsort($array);
        for($cont=0;$obBD->numeRegistros>$cont;$cont++){
            if($obBD->numeRegistros==($cont+1))
            echo $array[$cont];
            else
            echo $array[$cont].":";
        }    
    }
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