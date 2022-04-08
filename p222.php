<?php
$obBD = connecta();
start($obBD);

function connecta(){
$obBD = new baseDatos();
$BD = trim(fgets(STDIN));
$bd_datos = explode(" ", $BD);
$obBD->ingDatos($bd_datos[0],$bd_datos[1],$bd_datos[3],$bd_datos[2]);
return  $obBD;
}

function start($obBD){
    do{
    $linea = getLinea();
        if($linea!=false){
            $datos = explode(" ",$linea);
        $obBD->consulta("select distinct f.Ref_Bancaria,concat(u.Nombre,' ',u.Apellidos) as nombre from BD_PagoServ_Facturas f
        join Usuarios u on f.id_Cliente = u.Usuario
        where u.Usuario = '".$datos[0]."' and u.Clave = password('".$datos[1]."')
        order by f.Ref_Bancaria;");
            if($obBD->numeRegistros>0){
                $cont =0;
                while ( $row = mysqli_fetch_assoc($obBD->bloque)) {
                    if($cont==0)
                    echo $datos[0].":".$row['nombre'];
                    if($row['Ref_Bancaria']!='')
                    echo ":".$row['Ref_Bancaria'];
                    $cont++;
                }
                echo "\n";
            }
        }   
}while($linea!= false);
}

function getLinea(){
    try{
        $linea = trim(fgets(STDIN));
        return $linea;
    }
    catch(Exception $e){
            return false;
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
    function saca_registro($query){
        $this->consulta($query);
        return mysqli_fetch_object($this->bloque);
    }
	function cerrar(){
		mysqli_close($this->conn);
	}

}
?>