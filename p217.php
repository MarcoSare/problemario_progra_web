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
    $obBD->consulta("select j.ganador,concat(u.Nombre,' ',u.Apellidos) as nombre ,sum(j.puntos) as puntos from BD_Domino_Juegos j
    join Usuarios u on j.ganador = u.Usuario
    group by j.ganador
    order by sum(j.puntos) desc limit 1;");
    if($obBD->numeRegistros>0){
        while ( $row = mysqli_fetch_assoc( $obBD->bloque)) {
            $id_user= $row["nombre"];
            $puntos = $row["puntos"];
            echo $id_user." ".$puntos;
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