<?php
function sitestats_category_section_callback() {

	global $wpdb;

	$unused_category = $wpdb->get_results( "SELECT name, slug 
										  FROM wp_terms 
										  WHERE term_id 
										  IN (SELECT term_id 
											 FROM wp_term_taxonomy 
											 WHERE taxonomy = 'category' 
											 AND count = 0 ) 
										  ");
?>

	 <div class="sitestats_grid sitestats_category">
		 <ul>           
			<li><span>Total</span><?php echo count($categories=get_categories()) ?></li>
			<li><span>Unused</span><?php echo count($unused_category) ?></li>
		 </ul>
	  </div>

	<div class="meta-box-sortables">
		<div class="postbox">
			<h3 class="hndle">Most Used Categories</h3>
			<div class="inside">
				<table class="wp-list-table widefat">
					<thead>
						<tr>
							<th>Category</th>
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
					$categories=get_categories($args);
					foreach($categories as $key=>$category) {
					?>					
					<tr class="<?php echo $key % 2 ? '' : 'alternate' ?>">
						<td><?php echo $category->name ?></td>
						<td class="center"><?php echo $category->count ?></td>
					</tr>
					<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="meta-box-sortables ui-sortable">
		<div class="postbox">
			<h3 class="hndle">Less Used Categories</h3>
			<div class="inside">
				<table class="wp-list-table widefat">
					<thead>
						<tr>
							<th>Category</th>
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
					$categories=get_categories($args);
					foreach($categories as $key=>$category) {
					?>					
					<tr class="<?php echo $key % 2 ? '' : 'alternate' ?>">
						<td><?php echo $category->name ?></td>
						<td class="center"><?php echo $category->count ?></td>
					</tr>
					<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
<?php }