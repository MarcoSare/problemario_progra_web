<?php
$obBD = connecta();
start($obBD);

function connecta(){
$obBD = new baseDatos();
$host = trim(fgets(STDIN));
$user = trim(fgets(STDIN));
$pass = trim(fgets(STDIN));
$nameBd = trim(fgets(STDIN));
$obBD->ingDatos($host,$user,$nameBd,$pass);
return  $obBD;
}

function start($obBD){
    $obBD->consulta("select s.id, s.nombre from BD_PagoServ_Servicios s
    order by s.Nombre;");
    $bloque_nombre = $obBD->bloque;
    if($obBD->numeRegistros>0){
        while ( $row = mysqli_fetch_assoc($bloque_nombre)) {
            $servicio = getMonto($row['id'],$obBD);
            echo $row['nombre'].":".$servicio[0].":$".$servicio[1]."\n";
        }
         
    }
}

function getMonto($id,$obBD){
    $registro = $obBD->saca_registro("SELECT t.cont, ifnull(t.monto,0) as monto from (select count(f.id_Servicio) as cont, sum(f.monto) as monto from BD_PagoServ_Facturas f
    where id_Servicio =".$id." and f.fecha_Pago<=fecha_Vencimiento and fecha_Pago !='0000-00-00') as t;");
    $returno[0]=$registro->cont;
    $returno[1]=number_format($registro->monto, 2, '.', '');
    return $returno;
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
    function saca_registro($query){
        $this->consulta($query);
        return mysqli_fetch_object($this->bloque);
    }
	function cerrar(){
		mysqli_close($this->conn);
	}

}
?>