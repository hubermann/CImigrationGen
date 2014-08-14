<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Crear migracion CI</title>
	<link rel="stylesheet" href="http://hubermann.com/css/BuenosWebGrid.css">
	<style>
	form{width: 100%}
	textarea{width: 99%}
	label{width: 99%}
	input{width: 99%; padding: .5em 0 }
	.form_item{width: 100%; float: left; margin:.5em;}
	body{font-size: 1em}
	.notice{ background: #f5f5f5; border: 1px dotted #ccc; }
	.notice p{margin-bottom: .8em}

	</style>
</head>
<body>
	<header class="block">
		<div class="full">
			<h2>Generar archivo migracion.</h2>
		</div>
	</header>


<?php  
$tabla 	= $_POST['tabla'];
$campos = $_POST['campos'];

$todos 	= explode(",", $campos);
$campo_individual="";
foreach ($todos as $key => $value) {
	$nombre_campo = strtolower($value);
	$campo_individual .= '
				"'.trim($nombre_campo).'"    =>        array(
                    "type"                =>        "VARCHAR",
                    "constraint"        =>        100,
                ),';
}

$tabla = strtolower($tabla);

$nombre_tabla = ucfirst($tabla);
?>
<div class="block">
	<div class="full notice">
		<div class="inside">
			<p>Configurar los campos a el tipo de campo necesario (INT, DATE, TEXT) por el momento se han generado todos como varchar.</p>
			<p>Crear un archivo enumerado como 001_create_<?php echo $tabla;?>.php  dentro de /migrations.php y copiar el siguiente codigo:</p>
		</div>
	</div>
</div>
<div class="block">
<div class="full">
<?php  



$salida = "<?php defined('BASEPATH') OR exit('No direct script access allowed');  
 
class Migration_Create_".$nombre_tabla." extends CI_Migration
{
    public function up()
    {
        //TABLA ".ucwords($tabla)."
        $"."this->dbforge->add_field(
            array(
                \"id\"        =>        array(
                    \"type\"                =>        \"INT\",
                    \"constraint\"        =>        11,
                    \"unsigned\"            =>        TRUE,
                    \"auto_increment\"    =>        TRUE,
 
                ),".$campo_individual."
            )
        );
 
        $"."this->dbforge->add_key('id', TRUE); //ID como primary_key
        $"."this->dbforge->create_table('".$tabla."');//crea la tabla
    }
 
    public function down()
    {
        //ELIMINAR TABLA
        $"."this->dbforge->drop_table('".$tabla."');
 
    }
}
?>
";

echo '<pre>', highlight_string($salida, true), '</pre>';

?>
</div>
</div>
<div class="block">
	<div class="full">
		<p>Para correr la migracion de los archivos generados.</p>
		<p>En algun controlador a eleccion o creado para tal fin se incluye la libreria de migraciones:</p>
		
	</div>
	<div class="full">
		<p>
		<?php 
		$salida_2 = "
		$"."this->load->library('migration');";
		echo '<pre>', highlight_string($salida_2, true), '</pre>'; 
		?>
		</p>
	</div>
	<div class="full">
		<p>Y luego podemos correr la migracion de la siguiente forma:</p>
	</div>
	<div class="full">
		<?php 
		$salida_3 ="
		//4 como ejemplo de la migracion que queremos correr
		//Tambien puede usarse $"."this->migration->latest()

		if(!$"."this->migration->version(4)){ 

            echo \"error al realizar migracion.\";
        }else{
            echo \"Migracion realizada\";
        }
		";
		echo '<pre>', highlight_string($salida_3, true), '</pre>'; 
		 ?>
	</div>


</div>

</body>
</html>