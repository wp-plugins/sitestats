<?php
/*
 * sitestats Home Page
*/
function sitestats_home_section_callback(  ) { 

	global $wpdb;

	$words_total = $wpdb->get_var("SELECT SUM(LENGTH(post_content) - LENGTH(REPLACE(post_content,' ',''))+1) 
												FROM $wpdb->posts 
												WHERE post_status = 'publish' 
												AND post_type = 'post'");

	$words_avg = $wpdb->get_var("SELECT AVG(LENGTH(post_content) - LENGTH(REPLACE(post_content,' ',''))+1) 
											FROM $wpdb->posts 
											WHERE post_status = 'publish' 
											AND post_type = 'post'");

	$wp_count_posts = wp_count_posts();
	$users = count_users();
	$comments_count = wp_count_comments(); 

	$unused_tags = $wpdb->get_results( "SELECT name, slug 
										FROM wp_terms 
										WHERE term_id 
										IN (SELECT term_id 
											FROM wp_term_taxonomy 
											WHERE taxonomy = 'post_tag' 
											AND count = 0 ) 
										");

	$unused_category = $wpdb->get_results( "SELECT name, slug 
														FROM wp_terms 
														WHERE term_id 
														IN (SELECT term_id 
															FROM wp_term_taxonomy 
															WHERE taxonomy = 'category' 
															AND count = 0 ) 
														");

?>	
		<div class="sitestats_home_grid">
			<ul>
				<li class="title red">Posts</li>
				<li><span>Published</span><?php echo $wp_count_posts->publish ?></li>
				<li><span>Future</span><?php echo$wp_count_posts->future ?></li>
				<li><span>Draft</span><?php echo $wp_count_posts->draft ?></li>
				<li><span>Pending</span><?php echo $wp_count_posts->pending ?></li>
				<li><span>Private</span><?php echo $wp_count_posts->private ?></li>
				<li><span>Trash</span><?php echo $wp_count_posts->trash ?></li>
			</ul>
		</div>

		<div class="sitestats_home_grid">
			<ul>
				<li class="title orange">Pages</li>
				<li><span>Published</span><?php echo wp_count_posts('page')->publish ?></li>
				<li><span>Future</span><?php echo wp_count_posts('page')->future ?></li>
				<li><span>Draft</span><?php echo wp_count_posts('page')->draft ?></li>
				<li><span>Pending</span><?php echo wp_count_posts('page')->pending ?></li>
				<li><span>Private</span><?php echo wp_count_posts('page')->private ?></li>
				<li><span>Trash</span><?php echo wp_count_posts('page')->trash ?></li>
			</ul>
		</div>

		<div class="sitestats_home_grid">
			<ul>
				<li class="title blue">Comments</li>
				<li><span>Approved</span><?php echo $comments_count->approved ?></li>
				<li><span>Moderation</span><?php echo $comments_count->moderated ?></li>
				<li><span>Spam</span><?php echo $comments_count->spam ?></li>
				<li><span>Trash</span><?php echo $comments_count->trash ?></li>
				<li><span>Total</span><?php echo $comments_count->total_comments ?></li>
			</ul>
		</div>

		<div class="sitestats_home_grid">
			<ul>
				<li class="title green">Users</li>
				<li><span>Users</span><?php echo $users['total_users'] ?></li>
				<?php foreach($users['avail_roles'] as $role => $count) { ?>
					<li><span><?php echo ucfirst($role) ?></span><?php echo ucfirst($count) ?></li>
				<?php } ?>
			</ul>
		</div>

		<div class="sitestats_home_grid">
			<ul>
				<li class="title yellow">Categories</li>
				<li><span>Total</span><?php echo count($categories=get_categories()) ?></li>
				<li><span>Unused</span><?php echo count($unused_category) ?></li>
			</ul>

		</div>

		<div class="sitestats_home_grid">
			<ul>
				<li class="title violet">Tags</li>
				<li><span>Total</span><?php echo count($tags=get_tags()) ?></li>
				<li><span>Unused</span><?php echo count($unused_tags) ?></li>
			</ul>
		</div>

		<div class="sitestats_home_grid">
			<ul>
				<li class="title marine">Words</li>
				<li><span>Total</span><?php echo number_format($words_total) ?></li>
				<li><span>Average</span><?php echo number_format($words_avg) ?></li>
			</ul>
		</div>

<?php
}