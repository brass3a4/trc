<?php
/*
Plugin Name: TRC
Plugin URI: 
Description: Plugin que administra TRC
Version: 0.1
Author: brass3a4
Author URI: 
*/	

function agregar_scripts(){

	$handle = 'ammap';
    $js = plugins_url('statics/ammap/ammap/ammap.js',__FILE__);

    //Registro de estilos CSS
    wp_register_style( 'ammapcss', plugins_url('statics/ammap/ammap/ammap.css',__FILE__));
    wp_register_style( 'estilo', plugins_url('statics/css/estilo.css',__FILE__));
    //Registro de js

    wp_register_script( $handle, $js );
    wp_register_script( 'jsworld', plugins_url('statics/ammap/ammap/maps/js/worldLow.js',__FILE__));
    wp_register_script( 'jscharts', plugins_url('statics/amcharts/amcharts/amcharts.js',__FILE__));

    //Incluir
    wp_enqueue_script( $handle );
    wp_enqueue_script( 'jsworld' );
    wp_enqueue_script( 'jscharts' );
    wp_enqueue_style( 'estilo' );
    wp_enqueue_style( 'ammapcss' );
}
function muestra_contenido(){
	global $wpdb; 
	$table_name = $wpdb->prefix . "registros";
	$qwery= $wpdb->get_results("SELECT * FROM $table_name; ",ARRAY_A);
	if (!empty($qwery)) {
		foreach ($qwery as $rows) {
			$res[$rows['idregistros']] = $rows;
		}
	}

	include('template/trc.html');		
}

function muestra_reportes(){

	$url=plugins_url('statics/ammap/',__FILE__);

	global $wpdb; 
	$table_name = $wpdb->prefix . "registros";
	$qwery= $wpdb->get_results("SELECT DISTINCT country FROM $table_name; ",ARRAY_A);
	if (!empty($qwery)) {
		foreach ($qwery as $key => $value) {
			$valor = $value['country'];
			$qwery2 = $wpdb->get_var("SELECT COUNT(country) FROM $table_name WHERE country = '$valor'; ");
			$res[$valor] = $qwery2;
		}
	}

	include('template/reportes.html');	
}

function muestra_charts(){
	$url=plugins_url('statics/amcharts/',__FILE__);

	include('template/charts.html');	
}

function trc_instala(){

	global $wpdb; 

	//Se crea tabla guardar los registros
	$table_name= $wpdb->prefix . "registros";
    $sql = "CREATE  TABLE IF NOT EXISTS $table_name (
	  `idregistros` INT NOT NULL AUTO_INCREMENT ,
  `tipoRegistro` VARCHAR(150) NOT NULL ,
  `Idreferencia` VARCHAR(20) NULL COMMENT 'Este campo puede\nser upc-isrc-isbn ' ,
  `channel` VARCHAR(150) NULL ,
  `label` VARCHAR(500) NULL ,
  `country` VARCHAR(4) NULL ,
  `city` VARCHAR(45) NULL ,
  `artist` VARCHAR(500) NULL ,
  `album` VARCHAR(150) NULL ,
  `trackVideoTitle` VARCHAR(150) NULL ,
  `upc` VARCHAR(45) NULL ,
  `isrc` VARCHAR(45) NULL ,
  `reportDate` DATE NULL ,
  `units` INT NULL ,
  `saleReturn` VARCHAR(45) NULL ,
  `customerPrice` FLOAT NULL ,
  `cmaDiscount` FLOAT NULL ,
  `royaltyPrice` FLOAT NULL ,
  `royaltyCurrency` VARCHAR(4) NULL ,
  `royaltyEuros` FLOAT NULL ,
  `watchViews` INT NULL ,
  `embedViews` INT NULL ,
  `productType` VARCHAR(10) NULL ,
  `customerPrice2` FLOAT NULL ,
  `storeEarn` FLOAT NULL ,
  `royaltyPriceEuros1` FLOAT NULL ,
  `royaltyPriceEuros2` FLOAT NULL ,
  `trcCommission` FLOAT NULL ,
  `labelArtistRoyalty` FLOAT NULL ,
  PRIMARY KEY (`idregistros`) 
  
  )
	ENGINE = InnoDB;";
	$wpdb->query($sql);
	
}
function trc_desinstala(){
	global $wpdb; 

	$table_name = $wpdb->prefix . "registros";
	$sql = "DROP TABLE $table_name";
	$wpdb->query($sql);
}
	
function csv_panel(){

	
	include('template/panel.html');		
	//$saludo = $_FILES['archivo']['tmp_name'];
	
	if(isset($_FILES) && !empty($_FILES)){

		$archivos = file_get_contents($_FILES['archivos']['tmp_name']);
		$archivos = explode("\n", $archivos);
		$i=1;
		foreach($archivos as $archivo){
	            
	        $obtener_datos[$i] = explode("\t", $archivo);
	        $i++;
	    }
	    array_pop($obtener_datos);


	    $post = $_POST;

	    if(isset($_POST) && !empty($_POST)){
			guarda_columnas($_POST,$obtener_datos);
		}	

		//include('template/msj.html');	
		
		
	}

	
	
}

function conversor_divisas($divisa_origen, $divisa_destino, $cantidad) {
    $cantidad = urlencode($cantidad);
    $divisa_origen = $divisa_origen;
    $divisa_destino = $divisa_destino;
    $url = "http://www.google.com/ig/calculator?hl=en&q=$cantidad$divisa_origen=?$divisa_destino";
    $rawdata = file_get_contents($url);
    $data = explode('"', $rawdata);
    $data = explode(' ', $data['3']);
    $var = $data['0'];
    return round($var,7);
    
}//END FUNCTION

function guarda_columnas($post,$obtener_datos){
	
	global $wpdb;
	$table_name= $wpdb->prefix . "registros";
	$datos['post'] = $post;
	unset($obtener_datos['1']);
	$datos['registros'] = $obtener_datos;

	
	switch ($post['fuente']) {
		case '1':
			
				if(isset($datos) && !empty($datos)){
				
					foreach ($datos['registros'] as $registro) {
						//
						$productType = ($registro[8] == 'I') ? 'Album' : 'Track' ;
						$customerPrice2 = (float) $registro[9]* (float) $registro[22];
						$storeEarn = (float) $registro[22]* (float) $registro[10];
						$royaltyPriceEuros1 = conversor_divisas($registro[19],'EUR',$registro[10]);
						$royaltyPriceEuros2 = ($registro[16] == 'S') ? $royaltyPriceEuros1 : 0 ;
						$sql = "INSERT INTO $table_name (Idreferencia,tipoRegistro,label,country,artist,isrc,units,saleReturn,customerPrice,cmaDiscount,royaltyPrice,royaltyCurrency,productType,customerPrice2,storeEarn,royaltyPriceEuros1,royaltyPriceEuros2) VALUES ('{$registro[12]}','iTunes','{$registro[7]}','{$registro[18]}','{$registro[5]}','{$registro[4]}','{$registro[9]}','{$registro[16]}','{$registro[22]}','{$registro[24]}','{$registro[10]}','{$registro[19]}','{$productType}','{$customerPrice2}',{$storeEarn},{$royaltyPriceEuros1},{$royaltyPriceEuros2});";
						//$sql = " INSERT INTO $table_name (Idreferencia,channel,label,country,city,artist,album,trackVideoTitle,upc,isrc,reportDate,units,saleReturn,customerPrice,cmaDiscount,royaltyPrice,royaltyCurrency,royaltyEuros,watchViews,embedViews) VALUES ('{$registro['provider']}','{$registro['provider_contry']}','{$registro['vendor_identifier']}');";
						$wpdb->query($sql);
						
					}

					include('template/msj.html');
				}

			break;
		case '2':

				if(isset($datos) && !empty($datos)){

					foreach ($datos['registros'] as $registro) {
						//
						//
						//$sql = " INSERT INTO $table_name (Idreferencia,tipoRegistro,label,country,artist,isrc,units,saleReturn,customerPrice,cmaDiscount,royaltyPrice,royaltyCurrency) VALUES ('{$registro[12]}','iTunes','{$registro[7]}','{$registro[18]}','{$registro[5]}','{$registro[4]}','{$registro[9]}','{$registro[16]}','{$registro[22]}','{$registro[24]}','{$registro[10]}','{$registro[19]}');";
						//$sql = " INSERT INTO $table_name (Idreferencia,channel,label,country,city,artist,album,trackVideoTitle,upc,isrc,reportDate,units,saleReturn,customerPrice,cmaDiscount,royaltyPrice,royaltyCurrency,royaltyEuros,watchViews,embedViews) VALUES ('{$registro['provider']}','{$registro['provider_contry']}','{$registro['vendor_identifier']}');";
						$wpdb->query($sql);	

					}

					include('template/msj.html');
				}

			break;
		default:
			# code...
			break;
	}

}

function trc_add_menu(){	
	if (function_exists('add_options_page')) {
		//add_menu_page
		
		//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
		add_menu_page('csv', 'TRC', 8, 'qwery', 'csv_panel');
		
		//add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
		add_submenu_page('qwery', 'Muestra contenido', 'Muestra contenido', 8, 'slug_segunda_opcion_de_menu', 'muestra_contenido');
		add_submenu_page('qwery', 'Reportes', 'Reportes', 8, 'slug_tercera_opcion_de_menu', 'muestra_reportes');
		add_submenu_page('qwery', 'Charts', 'Charts', 8, 'slug_cuarta_opcion_de_menu', 'muestra_charts');
		//add_menu_page('saludo2', 'saludo2', 8, 'slug_menu', 'saludo');
	}
}
if (function_exists('add_action')) {
	add_action('admin_menu', 'trc_add_menu'); 
}

add_action('activate_trc/trc.php','trc_instala');
add_action('deactivate_trc/trc.php', 'trc_desinstala');
add_action( 'wp_print_scripts', 'agregar_scripts' );
?>