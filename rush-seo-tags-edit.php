<?php
	
	if ( ! defined( 'ABSPATH' ) ) exit;
	
	//обработка данных получаемых при запросе
	//и выдача результатов
	$filter = array(
						'action'   => FILTER_SANITIZE_STRING,
						'_wpnonce'    => FILTER_SANITIZE_STRING,
						'tag_id'      => FILTER_VALIDATE_INT,
						'url' => FILTER_SANITIZE_STRING,
						'title' => FILTER_SANITIZE_STRING,
						'h1' => FILTER_SANITIZE_STRING,
						'description'  => FILTER_SANITIZE_STRING,

	);
	
	switch($_SERVER['REQUEST_METHOD'])
	{
		case 'GET': $data =  filter_input_array( INPUT_GET , $filter ); break;
		case 'POST': $data =  filter_input_array( INPUT_POST , $filter ); break;
		
		default:
	}
	
	$nonce='';
	
	
	
	if ( ISSET( $data['action'] ) ){
				
		
		if  ( !current_user_can( 'manage_options' ) )  {
			// This nonce is not valid.
			wp_die( __('You do not have sufficient permissions to access this action.') );
		} else 
		{
			// The nonce was valid.
			// Do stuff here.
			
			global $messages;
			
			
			
			if (isset( $data['_wpnonce'] ))
			{ 
				$nonce = $data['_wpnonce'];		
			}
			
			$messages = '';
			
			
			$action = sanitize_text_field( $data['action'] );
			
			switch ( $action )
			{
				
				case 'update':
				case 'insert':
								{
									if  ( ! wp_verify_nonce( $nonce, 'edit_tag' )) 
									{
										// This nonce is not valid.
										wp_die( __('You do not have sufficient permissions to access this action.') );
										
									}	
									$messages = rst_save_seo_tag( $data );
									
									break;
								}
				case 'del':{
									
									
									if (isset( $data['tag_id']) )
									{
										$tag_id = sanitize_key( $data['tag_id'] );		
									}
									
									if ( ( ! wp_verify_nonce( $nonce,  'delete_tag-' . $tag_id )) ) {
										// This nonce is not valid.
										wp_die( __('You do not have sufficient permissions to access this action.') );
									} 
										$messages = rst_delete_seo_tag( $data );
											
									break;
								}
			}
			
		}
	}