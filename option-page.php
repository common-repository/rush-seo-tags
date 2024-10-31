<?php

		if ( ! defined( 'ABSPATH' ) ) exit;
		
		global $wpdb;
		
		$table_name = $wpdb->prefix . "rush_seo_tags";
		
		global $messages;
		
	
?>
<div class="wrap">
		<div class='procontainer'>
			<div class='inner'>
				
				<?php screen_icon('themes'); ?> <h2>Rush Seo Tags</h2>
					<div class="manage_plugin">
						
						<a href="<?php echo esc_url( 'admin.php?page=rush_seo_tags_edit' ); ?>" class="button">Добавить данные</a>
					</div>
					<?php if (trim($messages) !='') :?>
						<div class="message"><?php print $messages; ?></div>
					<?php endif;?>
					<table class="form-table grid">
					<thead>
						<tr valign="top">
							<th scope="row" style="width:5%">
								ID
							</th>
							<th scope="row" style="width:20%">
								URL
							</th>
							<th scope="row" style="width:20%">
								TITLE
							</th>
							<th scope="row" style="width:15%">
								H1
							</th>
							<th scope="row" style="width:20%">
								Description
							</th>
							<th scope="row" style="width:10%" >						
							</th>
							<th scope="row" style="width:10%">
								
							</th>
						</tr>
					</thead>
					<?php 
						
						$seo_tags = $wpdb->get_results( "SELECT * FROM `$table_name`" );	
					?>	
					<tbody>
						<?php foreach ( $seo_tags as $row ):?>
						<tr>		
							<td><?php print ($row->id); ?></td>
							<td><?php print ($row->url); ?></td>
							<td><?php print ($row->title); ?></td>
							<td><?php print ($row->h1); ?></td>
							<td><?php print ($row->description); ?></td>
							<?
								$nonce = wp_create_nonce(  'delete_tag-' . $row->id );
								
								$url_update = 'admin.php?page=rush_seo_tags_edit&tag_id='.$row->id;
								
								$url_del = $_SERVER['REQUEST_URI'].'&action=del&_wpnonce=' .esc_attr( $nonce ).'&tag_id='.$row->id;
								
							?>
							<td class="modify"><a href="<?php echo esc_url( $url_update ); ?>">Редактировать</a></td>
							<td class="modify"><a href="<?php echo esc_url( $url_del ); ?>">Удалить</a></td>
						</tr>			
						<?php endforeach; ?>
					</tbody>
				</table>
					

			</div>
		</div>
</div>