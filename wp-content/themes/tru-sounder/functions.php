<?php
// get the key, lee
// ---- you will need to edit this for your own version
require get_stylesheet_directory() . '/includes/misc.php';

// run when this theme is activated
add_action('after_switch_theme', 'trusounder_setup');

function trusounder_setup () {
  // make sure our categories are present
  
  // create pages if they do not exist
  
  if (! get_page_by_path( 'share' ) ) {
  
  	// create the Write page if it does not exist
  	$page_data = array(
  		'post_title' 	=> 'Share',
  		'post_content'	=> 'Share Your Sound',
  		'post_name'		=> 'share',
  		'post_status'	=> 'publish',
  		'post_type'		=> 'page',
  		'post_author' 	=> 1,
  		'post_date' 	=> date('Y-m-d H:i:s'),
  		'page_template'	=> 'page-collect.php',
  	);
  	
  	wp_insert_post( $page_data );
  
  }

  if (! get_page_by_path( 'desk' ) ) {

  	// create the Write page if it does not exist
  	$page_data = array(
  		'post_title' 	=> 'Welcome Desk',
  		'post_content'	=> 'Welcome to the place to add your audio gems to this collection.',
  		'post_name'		=> 'desk',
  		'post_status'	=> 'publish',
  		'post_type'		=> 'page',
  		'post_author' 	=> 1,
  		'post_date' 	=> date('Y-m-d H:i:s'),
  		'page_template'	=> 'page-desk.php',
  	);
  	
  	wp_insert_post( $page_data );
  
  }

  if (! get_page_by_path( 'random' ) ) {

  	// create the Write page if it does not exist
  	$page_data = array(
  		'post_title' 	=> 'Random',
  		'post_content'	=> '(Place holder for random page)',
  		'post_name'		=> 'random',
  		'post_status'	=> 'publish',
  		'post_type'		=> 'page',
  		'post_author' 	=> 1,
  		'post_date' 	=> date('Y-m-d H:i:s'),
  		'page_template'	=> 'page-random.php',
  	);
  	
  	wp_insert_post( $page_data );
  
  }
   
}


# -----------------------------------------------------------------
# Set up the table and put the napkins out
# -----------------------------------------------------------------

add_action( 'init', 'trusounder_load_theme_options' );

// change the name of admin menu items from "New Posts"
// -- h/t http://wordpress.stackexchange.com/questions/8427/change-order-of-custom-columns-for-edit-panels
// and of course the Codex http://codex.wordpress.org/Function_Reference/add_submenu_page

add_action( 'admin_menu', 'trusounder_change_post_label' );
add_action( 'init', 'trusounder_change_post_object' );

function trusounder_change_post_label() {
    global $menu;
    global $submenu;
    
    $thing_name = 'Sound';
    
    $menu[5][0] = $thing_name . 's';
    $submenu['edit.php'][5][0] = 'All ' . $thing_name . 's';
    $submenu['edit.php'][10][0] = 'Add ' . $thing_name;
    $submenu['edit.php'][15][0] = $thing_name .' Categories';
    $submenu['edit.php'][16][0] = $thing_name .' Tags';
    echo '';
}
function trusounder_change_post_object() {

    $thing_name = 'Sound';

    global $wp_post_types;
    $labels = &$wp_post_types['post']->labels;
    $labels->name =  $thing_name . 's';;
    $labels->singular_name =  $thing_name;
    $labels->add_new = 'Add ' . $thing_name;
    $labels->add_new_item = 'Add ' . $thing_name;
    $labels->edit_item = 'Edit ' . $thing_name;
    $labels->new_item =  $thing_name;
    $labels->view_item = 'View ' . $thing_name;
    $labels->search_items = 'Search ' . $thing_name;
    $labels->not_found = 'No ' . $thing_name . ' found';
    $labels->not_found_in_trash = 'No ' .  $thing_name . ' found in Trash';
    $labels->all_items = 'All ' . $thing_name;
    $labels->menu_name =  $thing_name;
    $labels->name_admin_bar =  $thing_name;
}

add_filter('comment_form_defaults', 'trusounder_comment_mod');

function trusounder_comment_mod( $defaults ) {
	$defaults['title_reply'] = 'Provide Feedback';
	$defaults['logged_in_as'] = '';
	$defaults['title_reply_to'] = 'Provide Feedback for %s';
	return $defaults;
}




// Theme setup
add_action( 'after_setup_theme', 'tru_sounder_setup', 11 );

function tru_sounder_setup() {

 	// Remove support for all post-formats
    remove_theme_support( 'post-formats' );

	// Add just the audio post-format we want
	add_theme_support( 'post-formats', array( 'audio', ) );
}


# -----------------------------------------------------------------
# Options Panel for Admin
# -----------------------------------------------------------------

// -----  Add admin menu link for Theme Options
add_action( 'wp_before_admin_bar_render', 'trusounder_options_to_admin' );

function trusounder_options_to_admin() {
    global $wp_admin_bar;
    
    // we can add a submenu item too
    $wp_admin_bar->add_menu( array(
        'parent' => '',
        'id' => 'trusounder-options',
        'title' => __('TRU Sounder Options'),
        'href' => admin_url( 'themes.php?page=trusounder-options')
    ) );
}


function trusounder_enqueue_options_scripts() {
	// Set up javascript for the theme options interface
	
	// media scripts needed for wordpress media uploaders
	wp_enqueue_media();
	
	// custom jquery for the options admin screen
	wp_register_script( 'trusounder_options_js' , get_stylesheet_directory_uri() . '/js/jquery.trusounder-options.js', null , '1.0', TRUE );
	wp_enqueue_script( 'trusounder_options_js' );
}

function trusounder_load_theme_options() {
	// load theme options Settings

	if ( file_exists( get_stylesheet_directory()  . '/class.trusounder-theme-options.php' ) ) {
		include_once( get_stylesheet_directory()  . '/class.trusounder-theme-options.php' );		
	}
}


# -----------------------------------------------------------------
# login stuff
# -----------------------------------------------------------------

// Add custom logo to entry screen... because we can
// While we are at it, use CSS to hide the back to blog and retried password links
add_action( 'login_enqueue_scripts', 'my_login_logo' );

function my_login_logo() { ?>
    <style type="text/css">
        body.login div#login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/site-login-logo.png);
            padding-bottom: 30px;
        }    
	#backtoblog {display:none;}
	#nav {display:none;}
    </style>
<?php }


// Make logo link points to blog, not Wordpress.org Change Dat
// -- h/t http://www.sitepoint.com/design-a-stylized-custom-wordpress-login-screen/

add_filter( 'login_headerurl', 'login_link' );

function login_link( $url ) {
	return get_bloginfo( 'url' );
}
 
 
// Auto Login
// create a link that can automatically log in as a specific user, bypass login screen
// -- h/t  http://www.wpexplorer.com/automatic-wordpress-login-php/

add_action( 'after_setup_theme', 'trusounder_autologin');

function trusounder_autologin() {
	
	// URL Paramter to check for to trigger login
	if ($_GET['autologin'] == 'sounder') {
	
		// change to short auto logout time
		add_filter( 'auth_cookie_expiration', 'trusounder_change_cookie_logout', 99, 3 );

		// ACCOUNT USERNAME TO LOGIN TO
		$creds['user_login'] = 'sounder';
		
		// ACCOUNT PASSWORD TO USE- lame hard coded... I do not know how to get this
		// any other way since options  are not loaded yet
		$creds['user_password'] = APASS;
			
		$creds['remember'] = true;
		$autologin_user = wp_signon( $creds, false );
		
		
		
		if ( !is_wp_error($autologin_user) ) 
			wp_redirect ( site_url() . '/share' );
	}
}

function trusounder_change_cookie_logout( $expiration, $user_id, $remember ) {
    return $remember ? $expiration : 120;
}

// remove admin tool bar for non-admins, remove access to dashboard
// -- h/t http://www.wpbeginner.com/wp-tutorials/how-to-disable-wordpress-admin-bar-for-all-users-except-administrators/

add_action('after_setup_theme', 'remove_admin_bar');

function remove_admin_bar() {
	if ( !current_user_can('edit_others_posts')  ) {
	  show_admin_bar(false);
	}

}

# -----------------------------------------------------------------
# Licensed to License
# -----------------------------------------------------------------


function trusounder_get_licences() {
	// return as an array the types of licenses available 
	
	return ( array (
				'c' => 'All Rights Reserved (copyrighted)',
				'pd'	=> 'Public Domain',
				'cc0'	=> 'CC0 No Rights Reserved',
				'cc-by' => 'CC BY Creative Commons By Attribution',
				'cc-by-sa' => 'CC BY SA Creative Commons Attribution-ShareAlike',
				'cc-by-nd' => 'CC BY ND Creative Commons Attribution-NoDerivs',
				'cc-by-nc' => 'CC BY NC Creative Commons Attribution-NonCommercial',
				'cc-by-nc-sa' => 'CC BY NC SA Creative Commons Attribution-NonCommercial-ShareAlike',
				'cc-by-nc-nd' => 'CC By NC ND Creative Commons Attribution-NonCommercial-NoDerivs',
			)
		);
}


function trusounder_the_license( $lcode ) {
	// output the title of a license
	$all_licenses = trusounder_get_licences();
	
	echo $all_licenses[$lcode];
}

function trusounder_attributor( $license, $work_title, $work_link, $work_creator='') {

	$all_licenses = trusounder_get_licences();
		
	$work_str = ( $work_creator == '') ? '"' . $work_title . '"' : '"' . $work_title . '" by ' . $work_creator;
	
	$work_str_html = ( $work_creator == '') ? '<a href="' . $work_link .'">"' . $work_title . '"</a>' : '<a href="' . $work_link .'">"' . $work_title . '"</a> by ' . $work_creator;
	
	
	
	switch ( $license ) {

		case 'c': 	
			return ( array( 
						$work_str .  '" is &copy; All Rights Reserved.', 
						$work_str_html . '" is &copy; All Rights Reserved.'
					)
			 );
			break;
		
		case 'cc0':
			return ( array( 
						$work_str . ' is made available under the Creative Commons CC0 1.0 Universal Public Domain Dedication.',
						$work_str_html .  ' is made available under the <a href="https://creativecommons.org/publicdomain/zero/1.0/">Creative Commons CC0 1.0 Universal Public Domain Dedication</a>.'	
					)
			 );
		
			break;
	
		case 'pd':
			return ( array( 
				$work_str . ' has been explicitly released into the public domain.',
				$work_str_html . ' has been explicitly released into the public domain.'
				)
			 );
			break;
		
		default:
			//find position in license where name of license starts
			$lstrx = strpos( $all_licenses[$license] , 'Creative Commons');
		
			return ( array( 
					$work_str . ' is licensed under a ' .  substr( $all_licenses[$license] , $lstrx)  . ' 4.0 International license.',
					$work_str_html . ' is licensed under a <a href="https://creativecommons.org/licenses/' . $license . '/4.0/">' .  substr( $all_licenses[$license] , $lstrx)  . ' 4.0 International</a> license.'		
				)
			 );
	}


}

# -----------------------------------------------------------------
# For the Share Form
# -----------------------------------------------------------------

add_action('wp_enqueue_scripts', 'add_trusounder_scripts');

function add_trusounder_scripts() {	 
 
 	// add local scripts to augment theme global.js
	// wp_register_script( 'trusounder_local_js' , get_stylesheet_directory_uri() . '/js/jquery.local.js', null , '1.0', TRUE );
	// wp_enqueue_script( 'trusounder_local_js' );

 
 	if ( is_page('share') ) { // use on just our form page
    
		 // add media scripts if we are on our maker page and not an admin
		 // after http://wordpress.stackexchange.com/a/116489/14945
    	 
		if (! is_admin() ) wp_enqueue_media();
		
		// Build in tag auto complete script
   		wp_enqueue_script( 'suggest' );

		// custom jquery for the uploader on the form
		wp_register_script( 'jquery.sounder' , get_stylesheet_directory_uri() . '/js/jquery.sounder.js', null , '1.0', TRUE );
		wp_enqueue_script( 'jquery.sounder' );
		
	}

}

# -----------------------------------------------------------------
# Useful spanners and wrenches
# -----------------------------------------------------------------


// function to get the caption for an attachment (stored as post_excerpt)
// -- h/t http://wordpress.stackexchange.com/a/73894/14945
function get_attachment_caption_by_id( $post_id ) {
    $the_attachment = get_post( $post_id );
    return ( $the_attachment->post_excerpt ); 
}


function trusounder_author_user_check( $expected_user = 'sounder' ) {
// checks for the proper authoring account set up

	$auser = get_user_by( 'login', $expected_user );
		
	
	if ( !$auser) {
		return ('Authoring account not set up. You need to <a href="' . admin_url( 'user-new.php') . '">create a user account</a> with login name <strong>' . $expected_user . '</strong> with a role of <strong>Author</strong>. Make a killer strong password; no one uses it.');
	} elseif ( $auser->roles[0] != 'author') {
	
		// for multisite lets check if user is not member of blog
		if ( is_multisite() AND !is_user_member_of_blog( $auser->ID, get_current_blog_id() ) )  {
			return ('The user account <strong>' . $expected_user . '</strong> is set up but has not been added as a user to this site (and needs to have a role of <strong>Author</strong>). You can <a href="' . admin_url( 'user-edit.php?user_id=' . $auser->ID ) . '">edit it now</a>'); 
			
		} else {
		
			return ('The user account <strong>' . $expected_user . '</strong> is set up but needs to have it\'s role set to <strong>Author</strong>. You can <a href="' . admin_url( 'user-edit.php?user_id=' . $auser->ID ) . '">edit it now</a>'); 
		}
		
		
		
	} else {
		return ('The authoring account <strong>' . $expected_user . '</strong> is correctly set up.');
	}
}


function trusounder_check_user( $allowed='sounder' ) {
	// checks if the current logged in user is who we expect
	global $current_user;
    get_currentuserinfo();
	
	// return check of match
	return ( $current_user->user_login == $allowed );
}

function splot_the_author() {
	// utility to put in template to show status of special logins
	// nothing is printed if there is not current user, 
	//   echos (1) if logged in user is the special account
	//   echos (0) if logged in user is the another account
	//   in both cases the code is linked to a logout script

	if ( is_user_logged_in() and !current_user_can( 'edit_others_posts' ) ) {
		$user_code = ( trusounder_check_user() ) ? 1 : 0;
		echo '<a href="' . wp_logout_url( site_url() ). '">(' . $user_code  .')</a>';
	}

}

function set_html_content_type() {
	// from http://codex.wordpress.org/Function_Reference/wp_mail
	return 'text/html';
}

function br2nl ( $string )
// from http://php.net/manual/en/function.nl2br.php#115182
{
    return preg_replace('/\<br(\s*)?\/?\>/i', PHP_EOL, $string);
}

function make_links_clickable( $text ) {
//----	h/t http://stackoverflow.com/a/5341330/2418186
    return preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1">$1</a>', $text);
}


function url_is_audio ($url) {
// tests urls to see if they point to an audio type, either an mp3 URL or Soundcloud page

	// if we have a SoundCLoud URL, activate the green light
	if ( url_is_soundcloud ( $url ) ) return true;
	
	// otherwise let's look at the file extension on the URL
	$fileExtention 	= pathinfo ( $url, PATHINFO_EXTENSION ); 	// get file extension for url	
	$allowables 	= 	array( 'mp3'); 	// allowable file extensions
	
	// check the url file extension to ones we will allow
	return ( in_array( strtolower( $fileExtention) ,  $allowables  ) );
}


function url_is_soundcloud ( $url ) {

	if ( strpos( $url, 'soundcloud.com') === false ) {
		return (false);
	} else {
		return (true);
	}
}



?>