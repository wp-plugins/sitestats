<?php
/*
 * sitestats Pages
*/
function sitestats_posts_section_callback () {

	global $wpdb;

	// Get years that have posts
	$years = $wpdb->get_results( "SELECT YEAR(post_date) AS year FROM wp_posts 
									WHERE post_type = 'post' AND post_status = 'publish' 
									GROUP BY year DESC" );	

	$wp_count_posts = wp_count_posts();
	?>

	<div class="sitestats_grid sitestats_post">
		<ul>
			<li><span>Published</span><?php echo $wp_count_posts->publish ?></li>
			<li><span>Future</span><?php echo$wp_count_posts->future ?></li>
			<li><span>Draft</span><?php echo $wp_count_posts->draft ?></li>
			<li><span>Pending</span><?php echo $wp_count_posts->pending ?></li>
			<li><span>Private</span><?php echo $wp_count_posts->private ?></li>
			<li><span>Trash</span><?php echo $wp_count_posts->trash ?></li>
		</ul>
	 </div>


	<div class="postbox">     	 	
		<h3 class="hndle">Total Posts by Month</h3>
		<div class="inside">
		<table class="wp-list-table widefat">
			<thead>
				<tr>
					<th></th>
					<th>Jan</th>
					<th>Feb</th>
					<th>Mar</th>
					<th>Apr</th>
					<th>May</th>
					<th>Jun</th>
					<th>Jul</th>
					<th>Aug</th>
					<th>Sep</th>
					<th>Oct</th>
					<th>Nov</th>
					<th>Dec</th>
					<th>Total</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach($years as $year) { 
					$posts_this_year = $wpdb->get_results( "SELECT ID, post_title FROM wp_posts WHERE post_type = 'post' AND post_status = 'publish' AND YEAR(post_date) = '" . $year->year . "'" );
					$posts_by_month = $wpdb->get_results( "SELECT MONTH(post_date) as post_month, COUNT(ID) as post_count 
															FROM wp_posts 
															WHERE post_type = 'post' 
															AND post_status = 'publish' 
															AND YEAR(post_date) = '" . $year->year . "'
															GROUP BY post_month
															ORDER BY post_date DESC"
														);
				?>
				<tr class="<?php echo $year->year % 2 ? 'alternate' : '' ?>">
					<td><?php echo $year->year ?></td>
					<?php foreach(range(0,11) as $m) { ?>
						<td><?php echo isset($posts_by_month[$m]) ? $posts_by_month[$m]->post_count : '0';	?></td>
					<?php }	?>
					<td><strong><?php echo count($posts_this_year) ?></strong></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		</div>      
	</div>

	<div class="postbox">
		<h3 class="hndle">Longest Articles</h3>
		<div class="inside">
		<?php
		$longest = $wpdb->get_results("SELECT ID,post_title,post_date,LENGTH(post_content) - LENGTH(REPLACE(post_content,' ',''))+1 AS post_length 
									FROM $wpdb->posts 
									WHERE post_status = 'publish' AND post_type = 'post'
									GROUP BY ID
									ORDER BY post_length DESC 
									LIMIT 10");

		$shortest = $wpdb->get_results("SELECT ID,post_title,post_date,LENGTH(post_content) - LENGTH(REPLACE(post_content,' ',''))+1 AS post_length 
									FROM $wpdb->posts 
									WHERE post_status = 'publish' AND post_type = 'post'
									GROUP BY ID
									ORDER BY post_length 
									LIMIT 10");

		?>

		<table class="wp-list-table widefat">
			<thead>
				<tr>
					<th>Title</th>
					<th class="center">Date</th>
					<th class="center">Words</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($longest as $key=>$article) { ?>
				<tr class="<?php echo $key % 2 ? '' : 'alternate' ?>">
					<td><?php echo $article->post_title ?></td>					
					<td class="center"><?php echo date_i18n( get_option( 'date_format' ), strtotime( $article->post_date )) ?></td>
					<td class="center"><?php echo $article->post_length ?></td>
					<td class="center">
						<a href="<?php echo site_url(); ?>/?p=<?php echo $article->ID ?>">View</a> | <a href="post.php?post=<?php echo $article->ID ?>&amp;action=edit">Edit</a>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
		</div>
	</div>

	<div class="postbox">
		<h3 class="hndle">Shortest Articles</h3>
		<div class="inside">
			<table class="wp-list-table widefat">
				<thead>
					<tr>
						<th>Title</th>
						<th class="center">Date</th>
						<th class="center">Words</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($shortest as $key=>$article) { ?>
					<tr class="<?php echo $key % 2 ? '' : 'alternate' ?>">
						<td><?php echo $article->post_title ?></td>
						<td class="center"><?php echo date_i18n( get_option( 'date_format' ), strtotime( $article->post_date )) ?></td>
						<td class="center"><?php echo $article->post_length ?></td>
						<td class="center">
							<a href="<?php echo site_url(); ?>/?p=<?php echo $article->ID ?>">View</a> | 
							<a href="post.php?post=<?php echo $article->ID ?>&amp;action=edit">Edit</a>
						</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
<?php }