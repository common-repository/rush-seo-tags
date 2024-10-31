<?php

if ( ! defined( 'ABSPATH' ) ) exit;

global $wpdb;

global $table_name;

$table_name = $wpdb->prefix . "rush_seo_tags";

if(!function_exists('wp_get_current_user')) {
    include(ABSPATH . "wp-includes/pluggable.php"); 
}


function rst_get_seo_tag( $tag_id ) {
    
	global $wpdb;
	
	global $table_name;
	
	$id = 0;
	
	$id = $tag_id;

    $seo_tag = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM `$table_name` WHERE `id` = %d LIMIT 1;", $tag_id ) );
    
	return $seo_tag;
}

function rst_save_seo_tag( $data ) {
    
		
	$result = '';
	
	
	if ( isset( $data["tag_id"] ) ) {
		
		global $wpdb;
		
	    global $table_name;
		
		$tag_id = sanitize_key( $data["tag_id"] );
		
		$url = sanitize_text_field( $data["url"] );
		
		$title = sanitize_text_field( $data["title"] );
		
		$h1 = sanitize_text_field( $data["h1"] ); 
		
		$description = sanitize_text_field( $data["description"] ); 
		
		$url_site = esc_url_raw( get_home_url() );
		
		$url = str_replace($url_site.'/','',$url);
		
		$url = 	preg_replace("#/$#", "", $url);
		
		if (rst_get_seo_tag( $tag_id ))
		{
			// Do the updating
			IF ($wpdb->update($table_name,array(
									'url'=>$url,
									'title'=>$title,
									'h1'=>$h1,
									'description'=>$description
										),
									array('id'=>$tag_id),
									array('%s','%s','%s','%s'),
									array('%d')
						)) 
			{
				$result =  'Данные обновлены';
			}
				
		}
		else
		{
			// Do the inserting
			if ($wpdb->insert($table_name,array(
									'url'=>$url,
									'title'=>$title,
									'h1'=>$h1,
									'description'=>$description
										),
										array('%s','%s','%s','%s')
						))
			{
				$result = 'Данные добавлены';
			}			
		}	
		
	}
	
	return $result;
   
}

function rst_delete_seo_tag( $data ) {
	
	$result = '';	
	

	
	
	if ( isset( $data["tag_id"] ) ) {
		
		global $wpdb;
		
	    global $table_name;
		
		$tag_id = sanitize_key( $data["tag_id"] ); 
		
		// Do the deleteing
		if ($wpdb->delete($table_name,array(
												'id'=>$tag_id
											),
											array('%d')
							) > 0)
		{
			$result = 'Данные удалены';
		}			
	}
	
	return $result;
}

function rst_get_seo_tag_url( $url ) {
    
	
	
	global $wpdb;
	
	global $table_name;
		
	$url_inner = '';
	
	$url_inner =  esc_html( $url );

	$seo_tag = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM `$table_name` WHERE `url` = %s LIMIT 1;", $url_inner ) );
    
	return $seo_tag;
}