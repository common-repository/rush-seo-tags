<?php
/**
Plugin Name: RUSH Seo Tags
Description: Плагин подставляет SEO данные из БД
Plugin URI:
Version:      1.0
Author:       Rush Agency Dev
Author URI:
Text Domain:  rush seo tags
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
**/

if ( ! defined( 'ABSPATH' ) ) exit;

add_action('admin_menu', 'rst_admin_menu'); //Добавить новое меню в админку Wordpress
add_action('admin_menu','rst_admin_submenu');//Добавить страницы редактирования записями в админку Wordpress
add_action( 'admin_enqueue_scripts', 'rst_enqueue_styles_scripts' ); 

//создаем функцию создания таблицы в БД
//версия структур таблицы в БД
global $seo_tags_db_version;

$seo_tags_db_version = "1.0";

include_once dirname( __FILE__ ).'/metadata.php';//файл массив с данными $array_meta 

include_once dirname( __FILE__ ).'/rush-seo-tags-function.php';//функции работы с базой 

include_once dirname( __FILE__ ).'/rush-seo-tags-edit.php';//функции работы с базой 

include_once dirname( __FILE__ ).'/cannonicaldata.php';//файл массив с данными 

global $arrDesc;

$arrDesc = $array_meta;

$arrCannonical = $array_canonical;

//$url_site = get_home_url( ).'/';

//-------------------------------
//install plugin
//-------------------------------

function rst_install ( ) {

	global $wpdb;
	
	global $seo_tags_db_version;

	$table_name = $wpdb->prefix . "rush_seo_tags";
	
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
        
		$sql = "CREATE TABLE " . $table_name . " (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  url VARCHAR(255) NOT NULL,
		  description text  NULL,
		  title VARCHAR(255)  NULL,
		  h1 VARCHAR(255)  NULL,
		  PRIMARY KEY  (id)
		);";
	
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		
		dbDelta( $sql );
	
	
		global $arrDesc;
		
		foreach($arrDesc as $key=>$value)
		{
			$rows_affected = $wpdb->insert( $table_name, array( 
										'url' => preg_replace("#/$#", "", sanitize_text_field( $key )), 'description' => $wpdb->escape(sanitize_text_field( $value['description'])), 'title' => $wpdb->escape(sanitize_text_field( $value['title'] )),'h1' => $wpdb->escape(sanitize_text_field( $value['h1']) ) ) );	
		}
		
		add_option("seo_tags_db_version", $seo_tags_db_version);
		
	}
   
}
register_activation_hook( __FILE__, 'rst_install');

//-------------------------------
//Deactivate plugin
//-------------------------------
function rst_deactivate( ){
    
	global $wpdb;
	
	$table_name = $wpdb->prefix . "rush_seo_tags";
	
    $wpdb->query("DROP TABLE IF EXISTS `$table_name`");
}
register_deactivation_hook( __FILE__, 'rst_deactivate');

//--------------------
//css file plugin

function rst_enqueue_styles_scripts()
{
    if( is_admin( ) ) {              
        $css= plugins_url() . '/'.  basename(dirname(__FILE__)) . "/style.css";               
        wp_enqueue_style( 'rush-seo-tags-css', $css );
    }
}


//----------------------------------
//Пункт меню на Страницу настроек плагина 
//---------------------------------
function rst_admin_menu( ) {
   
	
	add_menu_page('Плагин подставляет SEO данные из БД', 'Rush Seo Tags', 'manage_options', basename(__FILE__), 'rst_options_menu' );
}


function rst_admin_submenu( ) {
	add_submenu_page(
		null, 
		'Add/Edit Rush Seo Tags',
		'Add/Edit Rush Seo Tags',
		'manage_options',
		'rush_seo_tags_edit',
		'rst_edit_callback'
	);
}


//---------------------------------------------------------------
function rst_options_menu( ) {
	
	if (!current_user_can('manage_options'))  {
			wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	
	include "option-page.php";
}

//--------------------------------------
//функции вывода формы редактирование/добавления записи.
//--------------------------------------
function rst_edit_callback( ) { 
	
	//результат выполнения формы
	
	global $messages;
	
	$tag_id = 0;
	
	$nonce = wp_create_nonce( 'edit_tag' );
	
	if (isset( $_REQUEST['tag_id']) )
	{
		$tag_id = sanitize_key( $_REQUEST['tag_id'] );		
	}
	
	//делаем запрос к базе на наличие переданного id, если она существует, 
	//то выводим форму заполненную этими данными, иначе пустую форму
	$seo_tag = rst_get_seo_tag( $tag_id );

?>	
	
	<div class="wrap">
		<div class='procontainer'>
			<div class='inner'>
					<?php screen_icon( 'themes' ); ?> <h2>Rush Seo Tags</h2>
		<?php if ($seo_tag):?>
			<h3>Редактирование записи </h3>
		<?php else : ?>
			<h3>Добавление записи </h3>
		<?endif ?>
		<div class="manage_plugin">
		
			<a href="<?php echo esc_url( 'admin.php?page=rush-seo-tags.php' ); ?>" class="button">На главную</a>
		</div>	
		
		<?php if (trim( $messages ) !='') :?>
			<div class="message"><?php print $messages; ?></div>
		<?php endif;?>
		<form method="post" action="<? echo esc_url( $_SERVER['REQUEST_URI'] );?>">
			<div>
				<label>Url</label><br>
				<input type="text" name="url" size="100" value="<?php if (isset($seo_tag->url)) { echo esc_html( $seo_tag->url );} ?>" />
			</div>
			<div>
				<label>H1</label><br>
				<input type="text" name="h1" size="100" value="<?php if (isset($seo_tag->h1)) {echo esc_html( $seo_tag->h1 ); }?>" />
			</div>
			<div>
				<label>Meta tag Title </label><br>
				<input type="text" name="title" size="100" value="<?php if (isset($seo_tag->title)){ echo esc_html( $seo_tag->title ); }?>" />
			</div>
			<div>
				<label>Meta tag Description</label><br>	
				<textarea cols=100 rows=12 name="description"  ><?php if (isset($seo_tag->description)){  echo esc_html( $seo_tag->description ); } ?></textarea> 
			</div>
			<div>	
				<input type="hidden" name="tag_id" value="<?php print esc_html( $tag_id ); ?>">
				<input type="hidden" name="action" value="<?php print  $tag_id > 0 ? 'update' : 'insert'; ?>">
				<input type="hidden" name="_wpnonce" value="<?php print   esc_attr( $nonce );  ?>">
				<p class="submit">
				<input type="submit" name="Submit" value="<?php echo __('Save','rush_seo_tags'); ?>" />
				</p>
			</div>
		</form>	

	</div>
	</div>
	</div>

<?php
//Конец функции вывода формы редактирование добавления записи.
}

//--------------------------------------------
//функция вывода description и title из массива
//----------------------------------------------

//удаляем стандартный вывод title
remove_filter( 'wp_head','_wp_render_title_tag',1 );

add_action( 'wp_head', 'rst_set_meta_tags_from_xml' );

function rst_set_meta_tags_from_xml( ){
    
	global $post;
	
	global $wp;
		
	$title = wp_get_document_title();

	$seo_tag = rst_get_seo_tag_url(urldecode($wp->request));
	
	if ($seo_tag){
		
		if (trim($seo_tag->description != ''))
			
			echo '<meta name="Description" content="'.esc_html( $seo_tag->description ).'">';
		
		if (trim($seo_tag->title != ''))
		{
			
			$title =  esc_html( $seo_tag -> title );
		}
	
	}
		
	echo "\n<title>" . $title . "</title>\n";
}

//-----------------------------------
//функция вывода h1 из массива
//-----------------------------------
add_action( 'set_caption', 'rst_set_caption_from_arr',1 );

function rst_set_caption_from_arr( $tag_open, $tag_close ){
	
	global $post;
	
	global $arrDesc;
	
	global $wp;
		
	$title = wp_get_document_title();
	
	$seo_tag = rst_get_seo_tag_url(urldecode($wp->request));
	
	if ($seo_tag){
		
		
		if (trim($seo_tag->h1 != ''))
			{
				
				$title =  trim(esc_html($seo_tag->h1));
			}	 
		
	}
		
	print $tag_open.$title.$tag_close;
	
}
//-----------------------------------
/*** Функция вывода rel="canonical" ***/
//-----------------------------------
remove_action('wp_head', 'rst_rel_canonical');
add_action('wp_head', 'rst_rel_canonical_from_arr',1);

function rel_canonical_from_arr(){
	
	global $post;
	
	global $wp;
	
	global $arrCannonical;
	
	
	$current_url = home_url(add_query_arg(array(),$wp->request));
	//1. узнаем текущи урл 
	$currenturl = urldecode($current_url);
	if (isset($arrCannonical) && count($arrCannonical) > 0)
	{
		foreach($arrCannonical as $key => $value)
		{
			if ($currenturl == $key)
					{
											
						if (trim($value != ''))
						{
							
							
							echo "\n<link rel='canonical' href='$value' />\n";
						}	
							 				
						break;
					}
		}
		
	}	
	
}	
 


 
