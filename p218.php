<?php
$obBD = connecta();
getConstantes($obBD);

function connecta(){
$obBD = new baseDatos();
$host = trim(fgets(STDIN));
$user = trim(fgets(STDIN));
$pass = trim(fgets(STDIN));
$nameBd = trim(fgets(STDIN));
$obBD->ingDatos($host,$user,$nameBd,$pass);
return  $obBD;
}

function getConstantes($obBD){
    $obBD->consulta("select T.nombre from (select j.id_usuario,concat(u.Nombre,' ',u.Apellidos) as nombre,u.Apellidos,rank() over(order by count(j.id_usuario) desc) as rango
    from BD_Domino_Juegos j
    join Usuarios u on  j.id_usuario=u.Usuario
    group by j.id_usuario) as T
    where T.rango=1
    order by T.Apellidos;");
    if($obBD->numeRegistros>0){
        echo "Invita";
        while ( $row = mysqli_fetch_assoc( $obBD->bloque)) {
        echo  "\n".$row["nombre"];
        }
    }
    $obBD->consulta("select T.nombre from(select j.id_invitado,concat(u.Nombre,' ',u.Apellidos) as nombre,u.Apellidos,rank() over(order by count(j.id_invitado) desc) as rango
    from BD_Domino_Juegos j
    join Usuarios u on  j.id_invitado=u.Usuario
    group by j.id_invitado) as T
    where T.rango =1
    order by T.Apellidos;");
    if($obBD->numeRegistros>0){
        echo "\n"."Invitado";
        while ( $row = mysqli_fetch_assoc( $obBD->bloque)) {
        echo  "\n".$row["nombre"];
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