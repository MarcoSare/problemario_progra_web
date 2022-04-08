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
    $obBD->consulta("describe BD_PagoServ_Facturas;");
    $dataPrimary = getPrimary($obBD->bloque);
    $dataforeing = getforeing($obBD->bloque);
    echo "Nombre de llave primaria: ".$dataPrimary[0]." [".$dataPrimary[1]."]\n";
    echo "Foraneas:";
    $obBD->consulta("select k.COLUMN_NAME ,k.REFERENCED_TABLE_NAME, k.REFERENCED_COLUMN_NAME
    FROM information_schema.TABLE_CONSTRAINTS i LEFT JOIN information_schema.KEY_COLUMN_USAGE k ON i.CONSTRAINT_NAME = k.CONSTRAINT_NAME
    WHERE i.CONSTRAINT_TYPE = 'FOREIGN KEY' AND i.TABLE_SCHEMA = DATABASE() AND i.TABLE_NAME = 'BD_PagoServ_Facturas'
    order by k.COLUMN_NAME;");
    if($obBD->numeRegistros>0){
        while ( $row = mysqli_fetch_assoc( $obBD->bloque)) {
            $columna = $row['COLUMN_NAME'];
            for($cont=0;count($dataforeing)>$cont;$cont++){
                if($columna==$dataforeing[$cont][0])
                echo "\nNombre:".$columna." <=> Tabla Referenciada:". $row['REFERENCED_TABLE_NAME']." <=> CampoForaneo:".$row['REFERENCED_COLUMN_NAME']." <=> [".$dataforeing[$cont][1]."]";
            }
        }
    }
}

function getPrimary($bloque){
        while ( $row = mysqli_fetch_assoc($bloque)) {
            if($row['Key']=='PRI'){
                $retorno[0] = $row['Field'];
                $retorno[1] = $row['Type'];
                return $retorno;
            }
        }
}


function getforeing($bloque){
    $cont =0;
    while ($row = mysqli_fetch_assoc($bloque)) {
        if($row['Key']=='MUL'){
            $retorno[$cont][0] = $row['Field'];
            $retorno[$cont][1] = $row['Type'];
            $cont++;
        }
    }
    return $retorno;
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