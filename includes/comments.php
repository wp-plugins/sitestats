<?php
/*
 * sitestats by Comments
*/
function sitestats_comments_section_callback () {

	global $wpdb;
	// Get years that have posts
	$years = $wpdb->get_results( "SELECT YEAR(post_date) AS year FROM wp_posts 
									WHERE post_type = 'post' AND post_status = 'publish' 
									GROUP BY year DESC" );

	$comments_count = wp_count_comments();	

	$comments = $wpdb->get_results('SELECT COUNT(comment_author_email) AS comments_count, comment_author_email, comment_author, comment_author_url
								FROM wp_comments
								WHERE comment_author_email != "" AND comment_type = "" AND comment_approved = 1
								GROUP BY comment_author_email
								ORDER BY comments_count DESC, comment_author ASC
								LIMIT 10');	

	$all_posts = $wpdb->get_results( "SELECT ID, post_title, comment_count, post_author, post_date
										FROM wp_posts 
										WHERE post_type = 'post' AND post_status = 'publish' " );	

	$most_commented = $wpdb->get_results("SELECT comment_count, ID, post_title, post_author, post_date
									FROM $wpdb->posts wposts, $wpdb->comments wcomments
									WHERE wposts.ID = wcomments.comment_post_ID
									AND wposts.post_status='publish'
									AND wcomments.comment_approved='1'
									GROUP BY wposts.ID
									ORDER BY comment_count DESC
									LIMIT 0 ,  10
									");				

	?>

	<div class="sitestats_grid sitestats_comments">
		<ul>
			<li><span>Approved</span><?php echo $comments_count->approved ?></li>
			<li><span>Moderation</span><?php echo $comments_count->moderated ?></li>
			<li><span>Spam</span><?php echo $comments_count->spam ?></li>
			<li><span>Trash</span><?php echo $comments_count->trash ?></li>
			<li><span>Total</span><?php echo $comments_count->total_comments ?></li>
		</ul>
	</div>

	<div class="postbox">
		<h3 class="hndle">Comments by Month</h4>
		<div class="inside">
		<table class="wp-list-table widefat">
			<thead>
				<tr>
					<th></th>
					<th class="center">Jan</th>
					<th class="center">Feb</th>
					<th class="center">Mar</th>
					<th class="center">Apr</th>
					<th class="center">May</th>
					<th class="center">Jun</th>
					<th class="center">Jul</th>
					<th class="center">Aug</th>
					<th class="center">Sep</th>
					<th class="center">Oct</th>
					<th class="center">Nov</th>
					<th class="center">Dec</th>
					<th class="center">Total</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach($years as $year) { 
					$comments_by_month = $wpdb->get_results( "SELECT MONTH(comment_date) as comment_month, COUNT(comment_ID) as comment_count 
															FROM wp_comments
															WHERE YEAR(comment_date) =  '" . $year->year . "' AND comment_type = '' AND comment_approved = 1
															GROUP BY comment_month
															ORDER BY comment_date ASC"
														);
					$comments_by_year = $wpdb->get_results( "SELECT YEAR(comment_date) as comment_year, COUNT(comment_ID) as comment_count 
															FROM wp_comments
															WHERE YEAR(comment_date) =  '" . $year->year . "' AND comment_type = '' AND comment_approved = 1
															GROUP BY comment_year
															ORDER BY comment_date ASC"
														);
				?>
				<tr class="<?php echo $year->year % 2 ? 'alternate' : '' ?>">
					<td><?php echo $year->year ?></td>
					<?php foreach(range(0,11) as $m) { ?>
						<td class="center"><?php echo isset($comments_by_month[$m]) ? $comments_by_month[$m]->comment_count : '0';	?></td>
					<?php }	?>
					<td class="center"><?php echo isset($comments_by_year['comment_year' == $year->year]) ? $comments_by_year['comment_year' == $year->year]->comment_count : 0 ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table> 
		</div>       
	</div>

	<div class="postbox">
		<h3 class="hndle">Top 10 Comment Authors</h3>
		<div class="inside">
			<table class="wp-list-table widefat">
				<thead>
					<tr>
						<th>Name</th>
						<th class="center">Amount</th>
						<th>Email</th>
						<th>Url</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php 
					foreach($comments as $key=>$comment) { ?>
						<tr class="<?php echo $key % 2 ? '' : 'alternate' ?>">
							<td><?php echo $comment->comment_author ?></td>
							<td class="center"><?php echo $comment->comments_count ?></td>
							<td><a href="<?php echo  $comment->comment_author_email ?>"><?php echo  $comment->comment_author_email ?></a></td>
							<td><a href="<?php echo $comment->comment_author_url ?>" target="_blank"><?php echo $comment->comment_author_url ?></a></td>
							<td><a href="edit-comments.php?s=<?php echo  $comment->comment_author_email ?>" target="_blank">View Comments</a></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>


	<div class="postbox">
		<h3 class="hndle">Most Commented Articles of All Times</h3>
		<div class="inside">
			<table class="wp-list-table widefat">
			<thead>
				<tr>
					<th>Title</th>
					<th class="center">Comments</th>
					<th class="center">Date</th>
					<th class="center">Author</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($most_commented as $key=>$post) { ?>
				<tr class="<?php echo $key % 2 ? '' : 'alternate' ?>">
					<td><?php echo $post->post_title ?></td>
					<td class="center"><?php echo $post->comment_count ?></td>
					<td class="center"><?php echo date_i18n( get_option( 'date_format' ), strtotime( $post->post_date )) ?></td>
					<td class="center"><?php echo get_the_author_meta('display_name', $post->post_author) ?></td>
					<td class="center">
						<a href="<?php echo site_url(); ?>/?p=<?php echo $post->ID ?>">View</a> | <a href="post.php?post=<?php echo $post->ID ?>&amp;action=edit">Edit</a>
					</td>
				</tr>
			<?php } ?>
			</tbody>
			</table>
		</div>
	</div>


	<div class="postbox">
		<h3 class="hndle">Most Commented By Year</h3>	
		<div class="inside">

		<ul id="yeartabs">
			<?php foreach($years as $key=>$year) { ?>
			<li><a class="<?php echo $key == 0 ? 'active' : '' ?>" data-id="<?php echo $year->year ?>-link" href="javascript:void(0);"><?php echo $year->year ?></a></li>
			<?php } ?>
		</ul>

		<?php 
		foreach($years as $key=>$year) {			
			$selectedYear = $year->year;
			$posts_this_year = $wpdb->get_results( "SELECT ID, post_title, post_author, post_date, comment_count FROM wp_posts 
													WHERE post_type = 'post' AND post_status = 'publish' AND YEAR(post_date) = '" . $selectedYear . "' 
													ORDER BY comment_count DESC" );
			?>
			<div class="commentbox <?php echo $selectedYear ?>-box <?php echo $key == 0 ? 'show' : '' ?>">
				<h4>Most Commented Posts Published this Year</h4>
				<table class="wp-list-table widefat">
					<thead>
						<tr>
							<th>Title</th>
							<th class="center">Comments</th>
							<th class="center">Date</th>
							<th class="center">Author</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					<?php foreach (array_slice($posts_this_year, 0, 10) as $key=>$post) { ?>
					<tr class="<?php echo $key % 2 ? '' : 'alternate' ?>">
						<td><?php echo $post->post_title ?></td>
						<td class="center"><?php echo $post->comment_count ?></td>
						<td class="center"><?php echo date_i18n( get_option( 'date_format' ), strtotime( $post->post_date )) ?></td>
						<td class="center"><?php echo get_the_author_meta('display_name', $post->post_author) ?></td>
						<td class="center">
							<a href="<?php echo site_url(); ?>/?p=<?php echo $post->ID ?>">View</a> | <a href="post.php?post=<?php echo $post->ID ?>&amp;action=edit">Edit</a>
						</td>
					</tr>
					<?php } ?>
					</tbody>
				</table>
				<h4>Most Commented Posts during the Year</h4>
				<?php
				$comments_this_year = $wpdb->get_results( "SELECT comment_ID, comment_post_ID, count(comment_post_ID) AS totalComments 
															FROM wp_comments 
															WHERE YEAR(comment_date) =  '" . $selectedYear . "' AND comment_type = '' AND comment_approved = 1
															GROUP BY comment_post_ID
															ORDER BY count(comment_post_ID) DESC");
															?>
				<table class="wp-list-table widefat">
					<thead>
						<tr>
							<th>Title</th>
							<th class="center">Comments</th>
							<th class="center">Date</th>
							<th class="center">Author</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					<?php foreach (array_slice($comments_this_year, 0, 10) as $key=>$comment) { 
						foreach ($all_posts as $post) { 
							if ($comment->comment_post_ID == $post->ID) { ?>
								<tr class="<?php echo $key % 2 ? '' : 'alternate' ?>">
									<td><?php echo $post->post_title ?></td>
									<td class="center"><?php echo $comment->totalComments ?></td>
									<td class="center"><?php echo date_i18n( get_option( 'date_format' ), strtotime( $post->post_date )) ?></td>
									<td class="center"><?php echo get_the_author_meta('display_name', $post->post_author) ?></td>
									<td class="center">
										<a href="<?php echo site_url(); ?>/?p=<?php echo $post->ID ?>">View</a> | <a href="post.php?post=<?php echo $post->ID ?>&amp;action=edit">Edit</a>
									</td>
								</tr>
								<?php 
								}
							} 
						} 
						?>
					</tbody>
				</table>
			</div>
		<?php    	
		} 
		?>
		</div>
	</div>	

	<script>
		(function($) {
			$('#yeartabs a').click(function() {
				$('#yeartabs a').removeClass('active');
				$(this).addClass('active');
				var clickYear = $(this).attr('data-id').split('-')[0];
				$('.commentbox').removeClass('show');
				$( '.' + clickYear + '-box' ).addClass('show');
			});			
		})( jQuery );
	</script>

<?php }
