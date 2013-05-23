<?php
/*
Plugin Name: Lee CSV
Plugin URI: http://inyaka.net/blog
Description: Plugin que lee archivos css y los guarda en la base de datos
Version: 0.1
Author: brass3a4
Author URI: 
*/	
function saludo(){
	global $wpdb; 
	$table_name = $wpdb->prefix . "saludos";
	$saludo= $wpdb->get_var("SELECT saludo FROM $table_name ORDER BY RAND() LIMIT 0, 1; " );
	include('template/saludo.html');		
}
function saludo_instala(){
	global $wpdb; 
	$table_name= $wpdb->prefix . "saludos";
   $sql = " CREATE TABLE $table_name(
		id mediumint( 9 ) NOT NULL AUTO_INCREMENT ,
		saludo tinytext NOT NULL ,
		PRIMARY KEY ( `id` )	
	) ;";
	$wpdb->query($sql);
	$sql = "INSERT INTO $table_name (saludo) VALUES ('Hola Mundo');";
	$wpdb->query($sql);
}
function saludo_desinstala(){
	global $wpdb; 
	$table_name = $wpdb->prefix . "saludos";
	$sql = "DROP TABLE $table_name";
	$wpdb->query($sql);
}	
function csv_panel(){
	include('template/panel.html');		
	//$saludo = $_FILES['archivo']['tmp_name'];
	
	$saludos = file_get_contents($_FILES['archivos']['tmp_name']);
	$saludos = explode("\n", $saludos);

	foreach($saludos as $saludo){
            
        $obtener_datos = explode("\t", $saludo);
                
        $derechos[trim($obtener_datos[0])] = array('video_id' => trim($obtener_datos[0]), 'watch_views' => trim($obtener_datos[1]));

    }

	include('template/saludo.html');

	//fclose($saludo);
	// global $wpdb; 
	// $table_name = $wpdb->prefix . "saludos";
	// if(isset($_POST['saludo_inserta'])){	
	// 		$sql = "INSERT INTO $table_name (saludo) VALUES ('{$_POST['saludo_inserta']}');";
	// 		$wpdb->query($sql);
	// }
}

function saludo_add_menu(){	
	if (function_exists('add_options_page')) {
		//add_menu_page
		
		//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
		add_menu_page('csv', 'Lee csv', 8, 'qwery', 'csv_panel');
		
		//add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
		//add_submenu_page('qwery', 'Muestra saludo', 'Muestra saludo', 8, 'slug_segunda_opcion_de_menu', 'saludo');
		//add_menu_page('saludo2', 'saludo2', 8, 'slug_menu', 'saludo');
	}
}
if (function_exists('add_action')) {
	add_action('admin_menu', 'saludo_add_menu'); 
}

add_action('activate_saludo/saludo.php','saludo_instala');
add_action('deactivate_saludo/saludo.php', 'saludo_desinstala');
?>