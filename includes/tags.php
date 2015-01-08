<?php
function sitestats_tags_section_callback() {

	global $wpdb;

	$unused_tags = $wpdb->get_results( "SELECT name, slug 
										FROM wp_terms 
										WHERE term_id 
										IN (SELECT term_id 
											FROM wp_term_taxonomy 
											WHERE taxonomy = 'post_tag' 
											AND count = 0 ) 
										");


?>
	
	<div class="sitestats_grid sitestats_tags">
		<ul>
			<li><span>Total</span><?php echo count($tags=get_tags()) ?></li>
			<li><span>Unused</span><?php echo count($unused_tags) ?></li>
		</ul>
	</div>		
			
	<div class="meta-box-sortables">
		<div class="postbox">
			<h3 class="hndle">Most Used Tags</h3>
			<div class="inside">
				<table class="wp-list-table widefat">
					<thead>
						<tr>
							<th>Tags</th>
							<th class="center">Posts</th>
						</tr>
					</thead>
					<tbody> 		
					<?php
					$args=array(
						'orderby' => 'count',
						'order' => 'DESC',
						'hide_empty' => 0,
						'number' => 10
					);
					$tags=get_tags($args);
					foreach($tags as $key=>$tag) {
					?>
					<tr class="<?php echo $key % 2 ? '' : 'alternate' ?>">
						<td><?php echo $tag->name ?></td>
						<td class="center"><?php echo $tag->count ?></td>
					</tr>
					<?php
					}
					?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="meta-box-sortables">
		<div class="postbox">
			<h3 class="hndle">Less Used Tags</h3>
			<div class="inside">
				<table class="wp-list-table widefat">
					<thead>
						<tr>
							<th>Tags</th>
							<th class="center">Posts</th>
						</tr>
					</thead>
					<tbody> 		
					<?php
					$args=array(
						'orderby' => 'count',
						'order' => 'ASC',
						'hide_empty' => 0,
						'number' => 10
					);
					$tags=get_tags($args);
					foreach($tags as $key=>$tag) {
					?>
					<tr class="<?php echo $key % 2 ? '' : 'alternate' ?>">
						<td><?php echo $tag->name ?></td>
						<td class="center"><?php echo $tag->count ?></td>
					</tr>
					<?php
					}
					?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

<?php }