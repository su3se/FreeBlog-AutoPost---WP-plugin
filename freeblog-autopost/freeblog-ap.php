<?php
/*
	Plugin Name: FreeBrog Autopost
	Plugin URI: http://www.out48.com/download/hatena-bookmark-autopost/
	Description: When you added new post, this plugin send email to Hatena bookmark.
	Author: Output48
	Author URI: https://www.out48.com/
	Text Domain: hatena-bookmark-autopost
	Domain Path: /languages/
	Version: 1.0
*/

/*
	Copyright 2016 Output48 (email : out48@out48.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


// 投稿メニューへのフィールド追加
add_action( 'admin_init', 'easy_cpts_FreeBrog_autopost' );

function easy_cpts_FreeBrog_autopost() {
     add_settings_field(
          'htn_bm_email', // id
          'はてなブックマークのメール投稿用アドレス', // title
          'htn_bm_email_callback_function', // callback
          'writing', // page
          'default', // section
          array( 'email' => '', 'label_for' => 'htn_bm_email' ) // args
     );
 
     register_setting(
          'writing', // option_group
          'htn_bm_email', // option_name
          'htn_bm_email_validation' // sanitize_callback
     );
}
 
function htn_bm_email_callback_function( $args ) {
     $set_email = get_site_option( 'htn_bm_email' );
     echo '<input type="hidden" name="htn_bm_email" value="0">';
     echo '<label for="htn_bm_email"><input type="text" id="htn_bm_email" name="htn_bm_email" size="30" value="'. $set_email .'" /></input></label>';
}
 
function htn_bm_email_validation( $email ) {
     if ( is_email( $email ) ) {
          return $email;
     } else {
          add_settings_error(
               'htn_bm_email',
               'htn_bm_email-validation_error',
               __( 'はてなブックマークのメール投稿用アドレスが正しくありません。', 'Hello_World' ),
               'error'
          );
     }
}


// 新規投稿時にはてなブックマークへメールを送る
add_action( 'publish_post', 'FreeBrog_autopost', 1 ,6);

function FreeBrog_autopost($post_id) {

	// 管理者用メールアドレスの取得
	$from_email = get_option( 'admin_email' );

	// メール投稿用アドレスの取得
	$to_email = get_site_option( 'htn_bm_email' );

	$post = get_post($post_id);
	$url = get_permalink($post);
	$send_title = $post->post_title;
	wp_mail($to_email, $send_title, $url, $from_email);
	return;
}

