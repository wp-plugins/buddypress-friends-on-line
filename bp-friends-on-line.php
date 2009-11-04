<?php
/*
Plugin Name: BuddyPress Friends On-line (FOL)
Plugin URI: http://cosydale.com
Description: Plugin will display on your Friends page in a widget under the search field currently on-line friends. Data is dynamically updated.
Author: slaFFik
Version: 0.1
Author URI: http://ovirium.com/
Site Wide Only: true
*/

/* FOR FUTURE RELEASES
function fol_tab_menu() {
	global $bp, $create_group_step, $completed_to_step;
?>
	<li<?php if ( 'on-line' == $bp->action_variables[0] ) : ?> class="current"<?php endif; ?>><a href="<?php echo $bp->displayed_user->domain . $bp->friends->slug ?>/my-friends/on-line"><?php _e( 'On-line', 'friends-on-line' ) ?></a></li>
<?php
}
add_action('friends_header_tabs','fol_tab_menu');
*/

/**
 * Adding language support.
 */
if ( file_exists( dirname(__File__) . '/friends-on-line-' . get_locale() . '.mo' ) )
	load_textdomain( 'friends-on-line', dirname(__File__) . '/friends-on-line-' . get_locale() . '.mo' );

function fol_list() {
	
		// Получаю список ID всех людей on-line на сайте
		if ( bp_has_site_members('type=online') ) {
				while ( bp_site_members() ) : bp_the_site_member();
					$fol_online_ids[] = bp_get_the_site_member_user_id();
				endwhile;
			
		}
	
		// Получаю список ID (в чистом формате - без вложений второго уровня) всех друзей авторизованного человека. 
		// Ставлю ограничитель в 1000 первых, все равно больше не надо.
		if ( bp_has_friendships('per_page=1000') ) {
			while ( bp_user_friendships() ) : bp_the_friendship();
				$fol_friends_ids[] = bp_get_friend_id();
			endwhile;
		}
	
		// Меняю местами ключи и значения в $fol_friends_ids чтобы получить в ключах id людей.
		if ( $fol_friends_ids != null && is_array( $fol_friends_ids ) ) {
			$fol_friends_ids = array_flip($fol_friends_ids); 	
		}
		
		// Сравниваю ID on-line и ID друзей, при совпадении вывожу ссылку на них
		echo '<div class="widget" style="margin:20px 0;">';
		echo '<h2 class="widgettitle" style="width:95%;background:transparent url(' . get_bloginfo('stylesheet_directory') . '/_inc/images/rightcol_header_back.gif) no-repeat scroll left top;color:#FFFFFF;">';
			_e('Friends on-line','friends-on-line');
		echo '</h2>';
		echo '<div class="avatar-block" style="display:block;">';
		foreach ( (array) $fol_online_ids as $fol_online_id ) {
			if ( is_array( $fol_friends_ids ) && array_key_exists( $fol_online_id, $fol_friends_ids ) ) {
				echo '<div class="item-avatar" style="float:left;display:block">
					<a href="' . bp_core_get_userurl( $fol_online_id ) . '" title="' . bp_core_get_user_displayname( $fol_online_id ) . '">' . bp_core_get_avatar(  $fol_online_id, 1, 50, 50 ) . '</a>
					</div>';
			}
		}
		echo '</div></div>';
}
add_action('bp_after_my_friends_search','fol_list');

?>