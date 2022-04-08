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
    $cont=0;
    $array_nombres = array();
    
    $bloq_total = $obBD->saca_registro("select sum(f.Monto)as monto from BD_PagoServ_Facturas f
    where fecha_Vencimiento<'2019-01-20' and fecha_Pago ='0000-00-00';");
    echo 'Total de Adeudos: $'.number_format($bloq_total->monto, 2, '.', ',');
    
    $bloq_nombres = $obBD->consulta("select distinct f.id_Cliente, concat(u.Apellidos,' ',u.Nombre) as nombre_completo, u.Apellidos from BD_PagoServ_Facturas f
    join Usuarios u on u.Usuario = f.id_Cliente
    where fecha_Pago ='0000-00-00' and fecha_Vencimiento<'2019-01-20'
    order by u.Apellidos;");

    if($obBD->numeRegistros>0){
        while ( $row = mysqli_fetch_assoc( $bloq_nombres)) {
            $array_nombres[$cont]= $row["id_Cliente"];
            echo "\n"."Cliente: ".$row["nombre_completo"]." Total de Adeudo: $".get_total_cliente($array_nombres[$cont],$obBD);
            
            $bloq_servicios =  $obBD->consulta("select  s.Nombre, f.Monto as monto,f.fecha_Vencimiento from BD_PagoServ_Facturas f
            join BD_PagoServ_Servicios s on s.id = f.id_Servicio
            where fecha_Pago ='0000-00-00' and f.id_Cliente = '".$array_nombres[$cont]."' and fecha_Vencimiento<'2019-01-20'
            order by fecha_Vencimiento;");
            if($obBD->numeRegistros>0){
                while ( $row2 = mysqli_fetch_assoc( $bloq_servicios)) {
                    echo "\n"."Servicio: ".$row2['Nombre'] ." Total: $".number_format($row2['monto'], 2, '.', ',')." Fecha Venc.: ".$row2['fecha_Vencimiento']."";
                }
        }
        $cont++;
    }
}
}

function get_total_cliente($id_cliente,$obBD){
    $bloq_cliente_total = $obBD->saca_registro("select sum(f.Monto) as monto from BD_PagoServ_Facturas f
    where f.id_Cliente ='".$id_cliente."' and fecha_Pago ='0000-00-00' and fecha_Vencimiento<'2019-01-20';");
    return number_format($bloq_cliente_total->monto, 2, '.', ',');
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



