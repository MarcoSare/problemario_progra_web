<?php
$obBD = connecta();
ganador($obBD);
sinVotos($obBD);
sinCandidatos($obBD);
votos($obBD);
votosFuera($obBD);
function connecta(){
    $obBD = new baseDatos();
    $host = trim(fgets(STDIN));
    $user = trim(fgets(STDIN));
    $pass = trim(fgets(STDIN));
    $nameBd = trim(fgets(STDIN));
    $obBD->ingDatos($host,$user,$nameBd,$pass);
    return  $obBD;
    }

function ganador($obBD){
        $obBD->consulta("select concat(u.Nombre,' ', u.Apellidos) as nombre, bep.Nombre as partido,count(bev.IdVoto) as votos from Usuarios u
        join BD_Elecciones_Candidato bec on u.Usuario = bec.IdPersona
        join BD_Elecciones_Partido bep on bep.IdPartido = bec.IdPartido
        join BD_Elecciones_Voto bev on bep.IdPartido = bev.IdPartido
        group by nombre
        order by votos desc limit 2;");
            if($obBD->numeRegistros>0){     
                $ganador="";
                $partido="";
                $votos=0;
                $segundo="";
                $cont=0;       
                while ( $row = mysqli_fetch_assoc($obBD->bloque)) {
                    if($cont==0){
                        $ganador = $row['nombre'];
                        $partido = $row['partido'];
                        $votos = $row['votos'];
                        $cont++;
                    }
                    else{
                        $segundo = $row['nombre'];
                        $votos-= $row['votos'];
                    }

                }
                echo "<b>".$ganador."</b>"." de ".$partido." gano con ".$votos." votos a ".$segundo.".";
                echo "\n";
        }
        
}

function sinVotos($obBD){
    $obBD->consulta("select T.partido as partido from (select bep.Nombre as partido, sum(bev.IdPapeleta) as votos,bep.IdPartido as idP from BD_Elecciones_Partido bep
    left join BD_Elecciones_Voto bev on bep.IdPartido = bev.IdPartido
    group by partido) as T
    where T.votos is null
    order by T.idP;");
            if($obBD->numeRegistros>0){           
                $cont=0;
                while ( $row = mysqli_fetch_assoc($obBD->bloque)) {
                if($cont==0){
                    echo $row['partido'];
                    $cont++;
                }
                else
                echo ", ".$row['partido'];
                }
                echo " Sin Votos.";
                echo "\n";
        }
}

function sinCandidatos($obBD){
    $obBD->consulta("select T.partido as partido from (select bep.Nombre as partido, count(bec.IdPersona) as candidatos from BD_Elecciones_Partido bep
    left join BD_Elecciones_Candidato bec on bep.IdPartido = bec.IdPartido
    group by partido) as T 
    where T.candidatos=0;");
            if($obBD->numeRegistros>0){           
                $cont=0;
                while ( $row = mysqli_fetch_assoc($obBD->bloque)) {
                if($cont==0){
                    echo "<i>".$row['partido'];
                    $cont++;
                }
                else
                echo ", ".$row['partido'];
                }
                echo " No tenian candidatos.</i>";
                echo "\n";
        }
}
function votos($obBD){
    $obBD->consulta("select bed.nombre as partido,count(bev.IdDistrito) as votos from BD_Elecciones_Distrito bed
    join BD_Elecciones_Voto bev on bed.IdDistrito = bev.IdDistrito
    group by partido;");
            if($obBD->numeRegistros>0){           
                $cont=0;
                while ( $row = mysqli_fetch_assoc($obBD->bloque)) {
                    if($cont==0){
                    echo "<b>".$row['partido']."</b>"." "."<u>".$row['votos']."</u>";  
                    $cont++;  
                    }
                    else
                    echo " : "."<b>".$row['partido']."</b>"." "."<u>".$row['votos']."</u>";

                }
                }
                echo "\n";
        }
        function votosFuera($obBD){
            $bloque = $obBD->consulta("select IdDistrito,Rango_Papeleta from BD_Elecciones_Distrito;");        
            if($obBD->numeRegistros>0){             
                $cont=1;        
                while ( $row = mysqli_fetch_assoc($bloque)) {
                        $datos = explode("-",$row['Rango_Papeleta']);
                        $registro = $obBD->saca_registro("select bed.nombre as partido,count(bev.IdDistrito) as votos from BD_Elecciones_Distrito bed
                        join BD_Elecciones_Voto bev on bed.IdDistrito = bev.IdDistrito
                        where bed.IdDistrito=".$row['IdDistrito']." and (bev.IdPapeleta<".$datos[0]." or bev.IdPapeleta>".$datos[1].")
                        group by partido");
                            if($cont%2==0)
                                echo " : "."<i>".$registro->partido." ".$registro->votos."</i>";
                            else
                                 if($cont==1)
                                 echo "<b>".$registro->partido." ".$registro->votos."</b>";
                                 else
                                 echo " : "."<b>".$registro->partido." ".$registro->votos."</b>";
                                 $cont++;
                        }       
                        }
                        echo "\n";
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