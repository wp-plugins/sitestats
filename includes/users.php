<?php
function sitestats_users_section_callback() {

	global $wpdb;
	global $wp_roles;

	$users = $wpdb->get_results( "SELECT ID, display_name, user_email, user_url, user_registered
									FROM wp_users 
									ORDER BY user_registered" );
	$usersCount = count_users();
	?>

	<div class="sitestats_grid sitestats_authors">
		<ul>
			<li><span>Users</span><?php echo $usersCount['total_users'] ?></li>
			<?php foreach($usersCount['avail_roles'] as $role => $count) { ?>
				<li><span><?php echo ucfirst($role) ?></span><?php echo ucfirst($count) ?></li>
			<?php } ?>
		</ul>
	</div>

	<?php 
	foreach( $wp_roles->role_names as $role => $name ) {
		$this_role = "'[[:<:]]".$role."[[:>:]]'";
		$query = "SELECT * FROM $wpdb->users 
					WHERE ID = ANY (SELECT user_id 
									FROM $wpdb->usermeta 
									WHERE meta_key = 'wp_capabilities' AND meta_value RLIKE $this_role) 
					ORDER BY user_nicename ASC 
					LIMIT 10000";
		$users_of_this_role = $wpdb->get_results($query);

		if ($users_of_this_role) { ?>
		<div class="postbox">
			<h3 class="hndle"><?php echo ucfirst($role) ?> Stats</h3>
			<div class="inside">
				<table class="wp-list-table widefat">
					<thead>
						<tr>
							<th>Name</th>
							<th class="center">Posts</th>
							<th>Email</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					<?php
						foreach($users_of_this_role as $user) {
							$curuser = get_userdata($user->ID);
							$author_post_url = get_author_posts_url($curuser->ID, $curuser->nicename);
							?>
							<tr>
								<td><?php echo $curuser->display_name ?></td>
								<td class="center"><?php echo count_user_posts( $user->ID)  ?></td>
								<td><a href="mailto:<?php echo $user->user_email ?>"><?php echo $user->user_email ?></a></td>
								<td><a href="edit.php?post_type=post&amp;author=<?php echo $user->ID ?>">View posts</a></td>
							</tr>
							<?php
						}
					?>
					</tbody>
				</table>
			</div>
		</div>
	<?php } 
	}
}