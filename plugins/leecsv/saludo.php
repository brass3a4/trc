<?php
/*
Plugin Name: Lee CSV
Plugin URI: http://inyaka.net/blog
Description: Plugin que lee archivos css y los guarda en la base de datos
Version: 0.1
Author: brass3a4
Author URI: 
*/	

function agregar_scripts(){

	$handle = 'ammap';
    $js = plugins_url('statics/ammap/ammap/ammap.js',__FILE__);

    wp_register_style( 'ammapcss', plugins_url('statics/ammap/ammap/ammap.css',__FILE__));

    wp_register_script( $handle, $js );
    wp_register_script( 'jsworld', plugins_url('statics/ammap/ammap/maps/js/worldLow.js',__FILE__));
    wp_enqueue_script( $handle );
    wp_enqueue_script( 'jsworld' );
    wp_enqueue_style( 'ammapcss' );
}
function muestra_contenido(){
	global $wpdb; 
	$table_name = $wpdb->prefix . "csv";
	$qwery= $wpdb->get_var("SELECT video_id FROM $table_name WHERE id_registro=3; " );
	$qwery= $wpdb->get_results("SELECT * FROM $table_name; ",ARRAY_A);
	if (!empty($qwery)) {
		foreach ($qwery as $rows) {
			$res[$rows['id_registro']] = $rows;
		}
	}

	include('template/saludo.html');		
}

function muestra_reportes(){

	$url=plugins_url('statics/ammap/',__FILE__);

	global $wpdb; 
	$table_name = $wpdb->prefix . "csv";
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

function leecsv_instala(){
	global $wpdb; 
	$table_name= $wpdb->prefix . "csv";
    $sql = " CREATE TABLE $table_name(
		id_registro mediumint( 9 ) NOT NULL AUTO_INCREMENT ,
		provider varchar(15),
		provider_contry varchar(15),
		vendor_identifier varchar(15),
		country varchar(4),
		PRIMARY KEY ( `id_registro` )	
	) ;";
	$wpdb->query($sql);
}
function leecsv_desinstala(){
	global $wpdb; 
	$table_name = $wpdb->prefix . "csv";
	$sql = "DROP TABLE $table_name";
	$wpdb->query($sql);
}	
function csv_panel(){

	
	include('template/panel.html');		
	//$saludo = $_FILES['archivo']['tmp_name'];
	
	if(isset($_FILES) && !empty($_FILES)){

		$saludos = file_get_contents($_FILES['archivos']['tmp_name']);
		$saludos = explode("\n", $saludos);
		$i=1;
		foreach($saludos as $saludo){
	            
	        $obtener_datos[$i] = explode("\t", $saludo);
	        $i++;
	    }
	    array_pop($obtener_datos);

		include('template/msj.html');	
		
		
	}

	if(isset($_POST) && !empty($_POST)){
		guarda_columnas($_POST);
	}
	
}

function guarda_columnas($post){
	$datos = $post;
	$datos2 = $post;

	foreach ($datos as $key => $value) {
		if ($key != 'datos') {
			foreach ($datos2['datos'] as $key2 => $value2) {
				$cheks = 'hola2';
			}
			$cheks = 'hola1';
		}
	}


	include('template/guarda.html');
	// global $wpdb; 
	// $table_name= $wpdb->prefix . "csv";

	// foreach ($registros as $registro) {
	
	// 	$sql = " INSERT INTO $table_name (provider,provider_contry,vendor_identifier) VALUES ('{$registro['provider']}','{$registro['provider_contry']}','{$registro['vendor_identifier']}');";
	// 	$wpdb->query($sql);	

	// }

	// //obtenemos el ultimo campo insertado para eliminarlo después
	// $sql2 = "SELECT MAX(id_registro) FROM $table_name;";
	// $consulta = $wpdb->get_var($sql2);

	// //como se agrega un campo vacio al final lo eliminamos
	// $sql3 = "DELETE FROM $table_name WHERE id_registro = $consulta;";
	// $wpdb->query($sql3);
}

function saludo_add_menu(){	
	if (function_exists('add_options_page')) {
		//add_menu_page
		
		//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
		add_menu_page('csv', 'Lee csv', 8, 'qwery', 'csv_panel');
		
		//add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
		add_submenu_page('qwery', 'Muestra contenido', 'Muestra contenido', 8, 'slug_segunda_opcion_de_menu', 'muestra_contenido');
		add_submenu_page('qwery', 'Reportes', 'Reportes', 8, 'slug_tercera_opcion_de_menu', 'muestra_reportes');
		//add_menu_page('saludo2', 'saludo2', 8, 'slug_menu', 'saludo');
	}
}
if (function_exists('add_action')) {
	add_action('admin_menu', 'saludo_add_menu'); 
}

add_action('activate_leecsv/saludo.php','leecsv_instala');
add_action('deactivate_leecsv/saludo.php', 'leecsv_desinstala');
add_action( 'wp_print_scripts', 'agregar_scripts' );
?>