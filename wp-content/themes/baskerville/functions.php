<?php

// Theme setup
add_action( 'after_setup_theme', 'baskerville_setup' );

function baskerville_setup() {
	
	// Automatic feed
	add_theme_support( 'automatic-feed-links' );
		
	// Post thumbnails
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'post-image', 945, 9999 );
	add_image_size( 'post-thumbnail', 600, 9999 );
	
	// Post formats
	add_theme_support( 'post-formats', array( 'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video' ) );

	// Custom header
	$args = array(
		'width'         => 1440,
		'height'        => 221,
		'default-image' => get_template_directory_uri() . '/images/header.jpg',
		'uploads'       => true,
		'header-text'  	=> false
		
	);
	add_theme_support( 'custom-header', $args );
	
	// Add support for custom background
	$args = array(
		'default-color'	=> '#f1f1f1'
	);
	add_theme_support( "custom-background", $args );

	// Add nav menu
	register_nav_menu( 'primary', 'Primary Menu' );
	
	// Make the theme translation ready
	load_theme_textdomain('baskerville', get_template_directory() . '/languages');
	
	$locale = get_locale();
	$locale_file = get_template_directory() . "/languages/$locale.php";
	if ( is_readable($locale_file) )
	  require_once($locale_file);
	
}

// Enqueue Javascript files
function baskerville_load_javascript_files() {

	if ( !is_admin() ) {
		wp_register_script( 'baskerville_imagesloaded', get_template_directory_uri().'/js/imagesloaded.pkgd.js', array('jquery'), '', true );
		wp_register_script( 'baskerville_mediaelement', get_template_directory_uri().'/js/mediaelement-and-player.min.js', array('jquery'), '', true );
		wp_register_script( 'baskerville_flexslider', get_template_directory_uri().'/js/flexslider.min.js', array('jquery'), '', true );
		wp_register_script( 'baskerville_global', get_template_directory_uri().'/js/global.js', array('jquery'), '', true );
		
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'masonry' );
		wp_enqueue_script( 'baskerville_imagesloaded' );
		wp_enqueue_script( 'baskerville_mediaelement' );
		wp_enqueue_script( 'baskerville_flexslider' );
		wp_enqueue_script( 'baskerville_global' );
	}
}

add_action( 'wp_enqueue_scripts', 'baskerville_load_javascript_files' );


// Enqueue styles
function baskerville_load_style() {
	if ( !is_admin() ) {
	    wp_register_style('baskerville_googleFonts',  '//fonts.googleapis.com/css?family=Roboto+Slab:400,700|Roboto:400,400italic,700,700italic,300|Pacifico:400' );
		wp_register_style('baskerville_style', get_stylesheet_uri() );
		
	    wp_enqueue_style( 'baskerville_googleFonts' );
	    wp_enqueue_style( 'baskerville_style' );
	}
}

add_action('wp_print_styles', 'baskerville_load_style');


// Add editor styles
function baskerville_add_editor_styles() {
    add_editor_style( 'baskerville-editor-style.css' );
    $font_url = '//fonts.googleapis.com/css?family=Roboto+Slab:400,700|Roboto:400,400italic,700,700italic,300';
    add_editor_style( str_replace( ',', '%2C', $font_url ) );
}
add_action( 'init', 'baskerville_add_editor_styles' );


// Add footer widget areas
add_action( 'widgets_init', 'baskerville_sidebar_reg' ); 

function baskerville_sidebar_reg() {
	register_sidebar(array(
	  'name' => __( 'Footer A', 'baskerville' ),
	  'id' => 'footer-a',
	  'description' => __( 'Widgets in this area will be shown in the left column in the footer.', 'baskerville' ),
	  'before_title' => '<h3 class="widget-title">',
	  'after_title' => '</h3>',
	  'before_widget' => '<div class="widget %2$s"><div class="widget-content">',
	  'after_widget' => '</div><div class="clear"></div></div>'
	));	
	register_sidebar(array(
	  'name' => __( 'Footer B', 'baskerville' ),
	  'id' => 'footer-b',
	  'description' => __( 'Widgets in this area will be shown in the middle column in the footer.', 'baskerville' ),
	  'before_title' => '<h3 class="widget-title">',
	  'after_title' => '</h3>',
	  'before_widget' => '<div class="widget %2$s"><div class="widget-content">',
	  'after_widget' => '</div><div class="clear"></div></div>'
	));
	register_sidebar(array(
	  'name' => __( 'Footer C', 'baskerville' ),
	  'id' => 'footer-c',
	  'description' => __( 'Widgets in this area will be shown in the right column in the footer.', 'baskerville' ),
	  'before_title' => '<h3 class="widget-title">',
	  'after_title' => '</h3>',
	  'before_widget' => '<div class="widget %2$s"><div class="widget-content">',
	  'after_widget' => '</div><div class="clear"></div></div>'
	));
	register_sidebar(array(
	  'name' => __( 'Sidebar', 'baskerville' ),
	  'id' => 'sidebar',
	  'description' => __( 'Widgets in this area will be shown in the sidebar.', 'baskerville' ),
	  'before_title' => '<h3 class="widget-title">',
	  'after_title' => '</h3>',
	  'before_widget' => '<div class="widget %2$s"><div class="widget-content">',
	  'after_widget' => '</div><div class="clear"></div></div>'
	));
}
	
// Add theme widgets
require_once (get_template_directory() . "/widgets/dribbble-widget.php");  
require_once (get_template_directory() . "/widgets/flickr-widget.php");  
require_once (get_template_directory() . "/widgets/video-widget.php");


// Set content-width
if ( ! isset( $content_width ) ) $content_width = 676;


// Custom title function
function baskerville_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'baskerville' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'baskerville_wp_title', 10, 2 );


// Add classes to next_posts_link and previous_posts_link
add_filter('next_posts_link_attributes', 'baskerville_posts_link_attributes_1');
add_filter('previous_posts_link_attributes', 'baskerville_posts_link_attributes_2');

function baskerville_posts_link_attributes_1() {
    return 'class="post-nav-older fleft"';
}
function baskerville_posts_link_attributes_2() {
    return 'class="post-nav-newer fright"';
}


// Menu walker adding "has-children" class to menu li's with children menu items
class baskerville_nav_walker extends Walker_Nav_Menu {
    function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
        $id_field = $this->db_fields['id'];
        if ( !empty( $children_elements[ $element->$id_field ] ) ) {
            $element->classes[] = 'has-children';
        }
        Walker_Nav_Menu::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }
}


// Add class to body if the post/page has a featured image
add_action('body_class', 'baskerville_if_featured_image_class' );

function baskerville_if_featured_image_class($classes) {
     global $post;
     if ( isset( $post ) && has_post_thumbnail() ) {
             $classes[] = 'has-featured-image';
     } else {
	     $classes[] = 'no-featured-image';
     }
     return $classes;
}


// Add class to body if it's viewed on mobile
add_action('body_class', 'baskerville_if_is_mobile' );

function baskerville_if_is_mobile($classes) {
     global $post;
     if ( wp_is_mobile() ) {
             $classes[] = 'is_mobile';
     }
     return $classes;
}


// Add class to body if it's a single page
add_action('body_class', 'baskerville_if_page_class' );

function baskerville_if_page_class($classes) {
     global $post;
     if ( is_page() || is_404() || is_attachment() ) {
             $classes[] = 'single single-post';
     }
     return $classes;
}

// Change the length of excerpts
function custom_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );


// Add more-link text to excerpt
function new_excerpt_more( $more ) {
	return '... <a class="more-link" href="'. get_permalink( get_the_ID() ) . '">' . __('Continue Reading', 'baskerville') . ' &rarr;</a>';
}
add_filter( 'excerpt_more', 'new_excerpt_more' );


// Remove inline styling of attachment
add_shortcode('wp_caption', 'baskerville_fixed_img_caption_shortcode');
add_shortcode('caption', 'baskerville_fixed_img_caption_shortcode');

function baskerville_fixed_img_caption_shortcode($attr, $content = null) {
	if ( ! isset( $attr['caption'] ) ) {
		if ( preg_match( '#((?:<a [^>]+>\s*)?<img [^>]+>(?:\s*</a>)?)(.*)#is', $content, $matches ) ) {
			$content = $matches[1];
			$attr['caption'] = trim( $matches[2] );
		}
	}
	
	$output = apply_filters('img_caption_shortcode', '', $attr, $content);
	
	if ( $output != '' ) return $output;
	extract(shortcode_atts(array(
		'id' => '',
		'align' => 'alignnone',
		'width' => '',
		'caption' => ''
	), $attr));
	
	if ( 1 > (int) $width || empty($caption) )
	return $content;
	if ( $id ) $id = 'id="' . esc_attr($id) . '" ';
	return '<div ' . $id . 'class="wp-caption ' . esc_attr($align) . '" >' 
	. do_shortcode( $content ) . '<p class="wp-caption-text">' . $caption . '</p></div>';
}


// Get domain name from URL
function url_to_domain($url) {
    $host = @parse_url($url, PHP_URL_HOST);
 
    if (!$host) {
        $host = $url;
    }
 
    if (substr($host, 0, 4) == "www.") {
        $host = substr($host, 0);
    }
 
    return $host;
}


// Style the admin area
function baskerville_custom_colors() {
   echo '<style type="text/css">
   
#postimagediv #set-post-thumbnail img {
	max-width: 100%;
	height: auto;
}

         </style>';
}

add_action('admin_head', 'baskerville_custom_colors');


// Flexslider function for format-gallery
function baskerville_flexslider($size = thumbnail) {

	if ( is_page()) :
		$attachment_parent = $post->ID;
	else : 
		$attachment_parent = get_the_ID();
	endif;

	if($images = get_posts(array(
		'post_parent'    => $attachment_parent,
		'post_type'      => 'attachment',
		'numberposts'    => -1, // show all
		'post_status'    => null,
		'post_mime_type' => 'image',
                'orderby'        => 'menu_order',
                'order'           => 'ASC',
	))) { ?>
	
		<div class="flexslider">
		
			<ul class="slides">
	
				<?php foreach($images as $image) { 
					$attimg = wp_get_attachment_image($image->ID,$size); ?>
					
					<li>
						<?php echo $attimg; ?>
						<?php if ( !empty($image->post_excerpt) && is_single()) : ?>
							<div class="media-caption-container">
								<p class="media-caption"><?php echo $image->post_excerpt ?></p>
							</div>
						<?php endif; ?>
					</li>
					
				<?php }; ?>
		
			</ul>
			
		</div><?php
		
	}
}


// Baskerville comment function
if ( ! function_exists( 'baskerville_comment' ) ) :
function baskerville_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
	
		<?php __( 'Pingback:', 'baskerville' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'baskerville' ), '<span class="edit-link">', '</span>' ); ?>
		
	</li>
	<?php
			break;
		default :
		global $post;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
	
		<div id="comment-<?php comment_ID(); ?>" class="comment">
		
			<?php echo get_avatar( $comment, 80 ); ?>
		
			<div class="comment-inner">

				<div class="comment-header">
											
					<?php printf( '<cite class="fn">%1$s</cite>',
						get_comment_author_link()
					); ?>
					
					<p><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ) ?>"><?php echo get_comment_date() . ' at ' . get_comment_time() ?></a></p>
					
					<div class="comment-actions">
					
						<?php edit_comment_link( __( 'Edit', 'baskerville' ), '', '' ); ?>
						
						<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'baskerville' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
						
						<div class="clear"></div>
					
					</div> <!-- /comment-actions -->
					
				</div> <!-- /comment-header -->

				<div class="comment-content">
				
					<?php if ( '0' == $comment->comment_approved ) : ?>
					
						<p class="comment-awaiting-moderation"><?php _e( 'Awaiting moderation', 'baskerville' ); ?></p>
						
					<?php endif; ?>
				
					<?php comment_text(); ?>
					
				</div><!-- /comment-content -->
				
				<div class="comment-actions-below hidden">
					
					<?php edit_comment_link( __( 'Edit', 'baskerville' ), '', '' ); ?>
					
					<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'baskerville' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
					
					<div class="clear"></div>
				
				</div> <!-- /comment-actions -->
				
			</div> <!-- /comment-inner -->

		</div><!-- /comment-## -->
	<?php
		break;
	endswitch;
}
endif;


// Add Twitter field to user profiles
function baskerville_modify_contact_methods($profile_fields) {

	// Add new fields
	$profile_fields['twitter'] = 'Twitter-username (without the @)';

	return $profile_fields;
}
add_filter('user_contactmethods', 'baskerville_modify_contact_methods');


// Add the option to show or hide the email address for post authors
add_action( 'show_user_profile', 'show_extra_profile_fields' );
add_action( 'edit_user_profile', 'show_extra_profile_fields' );

function show_extra_profile_fields( $user ) { ?>

	<h3>Extra profile information</h3>

	<table class="form-table">


		<tr>
			<th><label for="showemail"><?php _e('Show email', 'baskerville'); ?></label></th>

			<td>
				<input type="checkbox" name="showemail" id="showemail" value="yes" <?php if (esc_attr( get_the_author_meta( "showemail", $user->ID )) == "yes") echo "checked"; ?> />	
				<span class="description"><?php _e('Check if you want to display your email address in single posts and the contributors page template.', 'baskerville'); ?></span>
			</td>
		</tr>

	</table>
<?php }

add_action( 'personal_options_update', 'save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_profile_fields' );

function save_extra_profile_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	update_user_meta( $user_id, 'showemail', $_POST['showemail'] );

}



// Add and save meta boxes for posts
add_action( 'add_meta_boxes', 'cd_meta_box_add' );
function cd_meta_box_add() {
	add_meta_box( 'post-video-url', __('Video URL', 'baskerville'), 'cd_meta_box_video_url', 'post', 'side', 'high' );
	add_meta_box( 'post-audio-url', __('Audio URL', 'baskerville'), 'cd_meta_box_audio_url', 'post', 'side', 'high' );
	add_meta_box( 'post-quote-content-box', __('Quote content', 'baskerville'), 'cd_meta_box_quote_content', 'post', 'normal', 'core' );
	add_meta_box( 'post-quote-attribution-box', __('Quote attribution', 'baskerville'), 'cd_meta_box_quote_attribution', 'post', 'normal', 'core' );
	add_meta_box( 'post-link-url', __('Link URL', 'baskerville'), 'cd_meta_box_link_url', 'post', 'side', 'high' );
	add_meta_box( 'post-link-title', __('Link title', 'baskerville'), 'cd_meta_box_link_title', 'post', 'side', 'high' );
}

function cd_meta_box_video_url( $post ) {
	$values = get_post_custom( $post->ID );
	$video_url = isset( $values['video_url'] ) ? esc_attr( $values['video_url'][0] ) : '';
	wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
	?>
		<p>
			<input type="text" class="widefat" name="video_url" id="video_url" value="<?php echo $video_url; ?>" />
		</p>
	<?php		
}

function cd_meta_box_audio_url( $post ) {
	$values = get_post_custom( $post->ID );
	$audio_url = isset( $values['audio_url'] ) ? esc_attr( $values['audio_url'][0] ) : '';
	wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
	?>
		<p>
			<input type="text" class="widefat" name="audio_url" id="audio_url" value="<?php echo $audio_url; ?>" />
		</p>
	<?php		
}

function cd_meta_box_quote_content( $post ) {
	$values = get_post_custom( $post->ID );
	$quote_content = isset( $values['quote_content'] ) ? esc_attr( $values['quote_content'][0] ) : '';
	wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
	?>
		<p>
			<textarea name="quote_content" id="quote_content" class="widefat" rows="5"><?php echo $quote_content; ?></textarea>
		</p>
	<?php		
}

function cd_meta_box_quote_attribution( $post ) {
	$values = get_post_custom( $post->ID );
	$quote_attribution = isset( $values['quote_attribution'] ) ? esc_attr( $values['quote_attribution'][0] ) : '';
	wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
	?>
		<p>
			<input name="quote_attribution" id="quote_attribution" class="widefat" value="<?php echo $quote_attribution; ?>" />
		</p>
	<?php		
}

function cd_meta_box_link_url( $post ) {
	$values = get_post_custom( $post->ID );
	$link_url = isset( $values['link_url'] ) ? esc_attr( $values['link_url'][0] ) : '';
	wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
	?>
		<p>
			<input name="link_url" id="link_url" class="widefat" value="<?php echo $link_url; ?>" />
		</p>
	<?php		
}

function cd_meta_box_link_title( $post ) {
	$values = get_post_custom( $post->ID );
	$link_title = isset( $values['link_title'] ) ? esc_attr( $values['link_title'][0] ) : '';
	wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
	?>
		<p>
			<input name="link_title" id="link_title" class="widefat" value="<?php echo $link_title; ?>" />
		</p>
	<?php		
}

add_action( 'save_post', 'cd_meta_box_save' );
function cd_meta_box_save( $post_id ) {
	// Bail if we're doing an auto save
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	
	// if our nonce isn't there, or we can't verify it, bail
	if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;
	
	// if our current user can't edit this post, bail
	if( !current_user_can( 'edit_post' ) ) return;
	
	// now we can actually save the data
	$allowed = array( 
		'a' => array( // on allow a tags
			'href' => array() // and those anchords can only have href attribute
		)
	);
	
	// Probably a good idea to make sure the data is set		
	if( isset( $_POST['video_url'] ) ) {
		update_post_meta( $post_id, 'video_url', wp_kses( $_POST['video_url'], $allowed ) );
	}
	
	if( isset( $_POST['audio_url'] ) ) {
		update_post_meta( $post_id, 'audio_url', wp_kses( $_POST['audio_url'], $allowed ) );
	}
	
	if( isset( $_POST['quote_content'] ) ) {
		update_post_meta( $post_id, 'quote_content', wp_kses( $_POST['quote_content'], $allowed ) );
	}
	
	if( isset( $_POST['quote_attribution'] ) ) {
		update_post_meta( $post_id, 'quote_attribution', wp_kses( $_POST['quote_attribution'], $allowed ) );
	}
	
	if( isset( $_POST['link_url'] ) ) {
		update_post_meta( $post_id, 'link_url', wp_kses( $_POST['link_url'], $allowed ) );
	}
	
	if( isset( $_POST['link_title'] ) ) {
		update_post_meta( $post_id, 'link_title', wp_kses( $_POST['link_title'], $allowed ) );
	}

}


// Hide/show meta boxes depending on the post format selected
function meta_box_post_format_toggle()
{
    wp_enqueue_script( 'jquery' );

    $script = '
    <script type="text/javascript">
        jQuery( document ).ready( function($)
            {
            
                $( "#post-video-url" ).hide();
                $( "#post-audio-url" ).hide();
                $( "#post-link-title" ).hide();
                $( "#post-link-url" ).hide();
                $( "#post-quote-content-box" ).hide();
                $( "#post-quote-attribution-box" ).hide();
            	
            	if($("#post-format-video").is(":checked"))
	                $( "#post-video-url" ).show();
            	if($("#post-format-audio").is(":checked"))
	                $( "#post-audio-url" ).show();
            	if($("#post-format-link").is(":checked")) {
	                $( "#post-link-title" ).show();
	                $( "#post-link-url" ).show();
				}
            	if($("#post-format-quote").is(":checked")) {
	                $( "#post-quote-content-box" ).show();
	                $( "#post-quote-attribution-box" ).show();
				}
                
                $( "input[name=\"post_format\"]" ).change( function() {
	                $( "#post-video-url" ).hide();
	                $( "#post-audio-url" ).hide();
	                $( "#post-link-url" ).hide();
	                $( "#post-link-title" ).hide();
	                $( "#post-quote-content-box" ).hide();
	                $( "#post-quote-attribution-box" ).hide();
                } );

                $( "input#post-format-video" ).change( function() {
                    $( "#post-video-url" ).show();
				});
                
                $( "input#post-format-audio" ).change( function() {
					$( "#post-audio-url" ).show();
				});
				
                $( "input#post-format-link" ).change( function() {
                    $( "#post-link-url" ).show();
                    $( "#post-link-title" ).show();
                });
                
                $( "input#post-format-quote" ).change( function() {
                    $( "#post-quote-content-box" ).show();
                    $( "#post-quote-attribution-box" ).show();
                });

            }
        );
    </script>';

    return print $script;
}
add_action( 'admin_footer', 'meta_box_post_format_toggle' );


// Baskerville theme options

class baskerville_Customize {

   public static function register ( $wp_customize ) {
   
      //1. Define a new section (if desired) to the Theme Customizer
      $wp_customize->add_section( 'baskerville_options', 
         array(
            'title' => __( 'Baskerville Options', 'baskerville' ), //Visible title of section
            'priority' => 35, //Determines what order this appears in
            'capability' => 'edit_theme_options', //Capability needed to tweak
            'description' => __('Allows you to customize some settings for Baskerville.', 'baskerville'), //Descriptive tooltip
         ) 
      );
      
      $wp_customize->add_section( 'baskerville_logo_section' , array(
		    'title'       => __( 'Logo', 'baskerville' ),
		    'priority'    => 40,
		    'description' => 'Upload a logo to replace the default site name and description in the header',
		) );
      
      //2. Register new settings to the WP database...      
      $wp_customize->add_setting( 'baskerville_logo', 
      	array( 
      		'sanitize_callback' => 'esc_url_raw'
      	) 
      );
                  
      //3. Finally, we define the control itself (which links a setting to a section and renders the HTML controls)...
      $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'baskerville_logo', array(
		    'label'    => __( 'Logo', 'baskerville' ),
		    'section'  => 'baskerville_logo_section',
		    'settings' => 'baskerville_logo',
		) ) );
      
      //4. We can also change built-in settings by modifying properties. For instance, let's make some stuff use live preview JS...
      $wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
      $wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
   }
   
   public static function generate_css( $selector, $style, $mod_name, $prefix='', $postfix='', $echo=true ) {
      $return = '';
      $mod = get_theme_mod($mod_name);
      if ( ! empty( $mod ) ) {
         $return = sprintf('%s { %s:%s; }',
            $selector,
            $style,
            $prefix.$mod.$postfix
         );
         if ( $echo ) {
            echo $return;
         }
      }
      return $return;
    }
}

// Setup the Theme Customizer settings and controls...
add_action( 'customize_register' , array( 'baskerville_Customize' , 'register' ) );


?>