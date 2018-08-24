<?php
// manages all of the theme options
// heavy lifting via http://alisothegeek.com/2011/01/wordpress-settings-api-tutorial-1/
// Revision Oct 27, 2017 as jQuery update killed TAB UI

class trusounder_Theme_Options {

	/* Array of sections for the theme options page */
	private $sections;
	private $checkboxes;
	private $settings;

	/* Initialize */
	function __construct() {

		// This will keep track of the checkbox options for the validate_settings function.
		$this->checkboxes = array();
		$this->settings = array();
		$this->get_settings();
		
		$this->sections['general'] = __( 'General Settings' );
		$this->sections['reset']   = __( 'Reset to Defaults' );

		// create a colllection of callbacks for each section heading
		foreach ( $this->sections as $slug => $title ) {
			$this->section_callbacks[$slug] = 'display_' . $slug;
		}
		

		// enqueue scripts for media uploader
        add_action( 'admin_enqueue_scripts', 'trusounder_enqueue_options_scripts' );
		
		add_action( 'admin_menu', array( &$this, 'add_pages' ) );
		add_action( 'admin_init', array( &$this, 'register_settings' ) );
		
		if ( ! get_option( 'trusounder_options' ) )
			$this->initialize_settings();
	}

	/* Add page(s) to the admin menu */
	public function add_pages() {
		$admin_page = add_theme_page( 'TRU Sounder Options', 'TRU Sounder Options', 'manage_options', 'trusounder-options', array( &$this, 'display_page' ) );
		
		// documents page, but don't add to menu		
		$docs_page = add_theme_page( 'TRU Sounder Documentation', '', 'manage_options', 'trusounder-docs', array( &$this, 'display_docs' ) );
			}

	/* HTML to display the theme options page */
	public function display_page() {
		echo '<div class="wrap">
		<h2>TRU Sounder Options</h2>';
		
		if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == true )
			echo '<div class="updated fade"><p>' . __( 'Theme options updated.' ) . '</p></div>';
				
		echo '<form action="options.php" method="post" enctype="multipart/form-data">';

			settings_fields( 'trusounder_options' );
			
			echo  '<h2 class="nav-tab-wrapper"><a class="nav-tab nav-tab-active" href="?page=trusounder-options">Settings</a>
	<a class="nav-tab" href="?page=trusounder-docs">Documentation</a></h2>';

		do_settings_sections( $_GET['page'] );

			echo '<p class="submit"><input name="Submit" type="submit" class="button-primary" value="' . __( 'Save Changes' ) . '" /></p>			
		</form>
		</div>
		
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			
			$("input[type=text], textarea").each(function() {
				if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "")
					$(this).css("color", "#999");
			});
			
			$("input[type=text], textarea").focus(function() {
				if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "") {
					$(this).val("");
					$(this).css("color", "#000");
				}
			}).blur(function() {
				if ($(this).val() == "" || $(this).val() == $(this).attr("placeholder")) {
					$(this).val($(this).attr("placeholder"));
					$(this).css("color", "#999");
				}
			});
			
			// This will make the "warning" checkbox class really stand out when checked.
			// I use it here for the Reset checkbox.
			$(".warning").change(function() {
				if ($(this).is(":checked"))
					$(this).parent().css("background", "#c00").css("color", "#fff").css("fontWeight", "bold");
				else
					$(this).parent().css("background", "none").css("color", "inherit").css("fontWeight", "normal");
			});
		});
		</script>';	
	}
			
		/* Insert custom CSS */
		public function styles() {

			wp_register_style( 'trusounder-admin', get_stylesheet_directory_uri() . '/trusounder-options.css' );
			wp_enqueue_style( 'trusounder-admin' );

		}


	/*  display documentation in a tab */
	public function display_docs() {	
		// This displays on the "Documentation" tab. 
		
	 	echo '<div class="wrap">
		<h1>TRU Sounder Documentation</h1>
		<h2 class="nav-tab-wrapper">
		<a class="nav-tab" href="?page=trusounder-options">Settings</a>
		<a class="nav-tab nav-tab-active" href="?page=trusounder-docs">Documentation</a></h2>';
		
		include( get_stylesheet_directory() . '/includes/trusounder-theme-options-docs.php');
		
		echo '</div>';		
	}


	/* Define all settings and their defaults */
	public function get_settings() {
	
		/* General Settings
		===========================================*/


		$this->settings['accesscode'] = array(
			'title'   => __( 'Access Code' ),
			'desc'    => __( 'Set necessary code to access the sharing tool; leave blank to make wide open' ),
			'std'     => '',
			'type'    => 'text',
			'section' => 'general'
		);

		$this->settings['accesshint'] = array(
			'title'   => __( 'Access Hint' ),
			'desc'    => __( 'Suggestion if someone cannot guess the code. Not super secure, but hey.' ),
			'std'     => 'Name of this site (lower the case, Ace!)',
			'type'    => 'text',
			'section' => 'general'
		);
		
		$this->settings['allow_comments'] = array(
			'section' => 'general',
			'title'   => __( 'Allow Comments?' ),
			'desc'    => __( 'Enable comments on items.' ),
			'type'    => 'checkbox',
			'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
		);
		
		$this->settings['new_item_status'] = array(
			'section' => 'general',
			'title'   => __( 'Status For New Sounds' ),
			'desc'    => __( 'Set to draft to moderate submissions via web form' ),
			'type'    => 'radio',
			'std'     => 'publish',
			'choices' => array(
				'publish' => 'Publish immediately',
				'draft' => 'Set to draft',
			)
		);		
 
 
  		// Build array to hold options for select, an array of post categories

		// Walk those cats, store as array index=ID 
	  	$all_cats = get_categories('hide_empty=0'); 
		foreach ( $all_cats as $item ) {
  			$cat_options[$item->term_id] =  $item->name;
  		}
 
		$this->settings['def_cat'] = array(
			'section' => 'general',
			'title'   => __( 'Default Category for New Sound'),
			'desc'    => '',
			'type'    => 'select',
			'std'     => get_option('default_category'),
			'choices' => $cat_options
		);	

		$this->settings['notify'] = array(
			'title'   => __( 'Notification Emails' ),
			'desc'    => __( 'Send notifications to these addresses (separate multiple wth commas). They must have an Editor Role on this site to be able to moderate' ),
			'std'     => get_option( 'admin_email' ),
			'type'    => 'text',
			'section' => 'general'
		);

		$this->settings['authorcheck'] = array(
		'section' => 'general',
		'title' 	=> '' ,// Not used for headings.
		'desc'   => 'Author Account', 
		'std'    =>  trusounder_author_user_check( 'sounder' ),
		'type'    => 'heading'
		);	


		$this->settings['pkey'] = array(
			'title'   => __( 'Author Account Password' ),
			'desc'    => __( 'When you create the account, we suggest using the generated strong password, make sure you save it so you can add it here.' ),
			'std'     => '',
			'type'    => 'password',
			'section' => 'general'
		);
			
		/* Reset
		===========================================*/
		
		$this->settings['reset_theme'] = array(
			'section' => 'reset',
			'title'   => __( 'Reset Options' ),
			'type'    => 'checkbox',
			'std'     => 0,
			'class'   => 'warning', // Custom class for CSS
			'desc'    => __( 'Check this box and click "Save Changes" below to reset bank options to their defaults.' )
		);

		
	}
	
	public function display_general() {
		// section heading for general setttings
		echo '<p>These settings manaage the behavior and appearance of your TRU Sounder site. There are quite a few of them!</p>';		
	}


	public function display_reset() {
		// section heading for reset section setttings
	}

	/* HTML output for individual settings */
	public function display_setting( $args = array() ) {

		extract( $args );

		$options = get_option( 'trusounder_options' );

		if ( ! isset( $options[$id] ) && $type != 'checkbox' )
			$options[$id] = $std;
		elseif ( ! isset( $options[$id] ) )
			$options[$id] = 0;

		$options['new_types'] = 'New Type Name'; // always reset
		
		$field_class = '';
		if ( $class != '' )
			$field_class = ' ' . $class;
			
			
		switch ( $type ) {
		
			case 'heading':
				echo '</td></tr><tr valign="top"><td colspan="2"><h4 style="margin-bottom:0;">' . $desc . '</h4><p style="margin-top:0">' . $std . '</p>';
				break;

			case 'checkbox':

				echo '<input class="checkbox' . $field_class . '" type="checkbox" id="' . $id . '" name="trusounder_options[' . $id . ']" value="1" ' . checked( $options[$id], 1, false ) . ' /> <label for="' . $id . '">' . $desc . '</label>';

				break;

			case 'select':
				echo '<select class="select' . $field_class . '" name="trusounder_options[' . $id . ']">';

				foreach ( $choices as $value => $label )
					echo '<option value="' . esc_attr( $value ) . '"' . selected( $options[$id], $value, false ) . '>' . $label . '</option>';

				echo '</select>';

				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';

				break;

			case 'radio':
				$i = 0;
				foreach ( $choices as $value => $label ) {
					echo '<input class="radio' . $field_class . '" type="radio" name="trusounder_options[' . $id . ']" id="' . $id . $i . '" value="' . esc_attr( $value ) . '" ' . checked( $options[$id], $value, false ) . '> <label for="' . $id . $i . '">' . $label . '</label>';
					if ( $i < count( $options ) - 1 )
						echo '<br />';
					$i++;
				}

				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';

				break;

			case 'textarea':
				echo '<textarea class="' . $field_class . '" id="' . $id . '" name="trusounder_options[' . $id . ']" placeholder="' . $std . '" rows="5" cols="30">' . wp_htmledit_pre( $options[$id] ) . '</textarea>';

				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';

				break;
				
			case 'medialoader':
			
			
				echo '<div id="uploader_' . $id . '">';
				
				
				
				if ( $options[$id] )  {
					$front_img = wp_get_attachment_image_src( $options[$id], 'radcliffe' );
					echo '<img id="previewimage_' . $id . '" src="' . $front_img[0] . '" width="640" height="300" alt="default thumbnail" />';
				} else {
					echo '<img id="previewimage_' . $id . '" src="http://placehold.it/640x300" alt="default header image" />';
				}

				echo '<input type="hidden" name="trusounder_options[' . $id . ']" id="' . $id . '" value="' . $options[$id]  . '" />
  <br /><input type="button" class="upload_image_button button-primary" name="_trusounder_button' . $id .'" id="_trusounder_button' . $id .'" data-options_id="' . $id  . '" data-uploader_title="Set Default Header Image" data-uploader_button_text="Select Image" value="Set/Change Image" />
</div><!-- uploader -->';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';

				break;

			case 'password':
				echo '<input class="regular-text' . $field_class . '" type="text" id="' . $id . '" name="trusounder_options[' . $id . ']" value="' . esc_attr( $options[$id] ) . '" /> <input type="button" id="showHide" value="Show" /> ';

				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';

				break;

			case 'text':
			default:
				echo '<input class="regular-text' . $field_class . '" type="text" id="' . $id . '" name="trusounder_options[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $options[$id] ) . '" />';

				if ( $desc != '' ) {
				
					if ($id == 'def_thumb') $desc .= '<br /><a href="' . $options[$id] . '" target="_blank"><img src="' . $options[$id] . '" style="overflow: hidden;" width="' . $options["index_thumb_w"] . '"></a>';
					echo '<br /><span class="description">' . $desc . '</span>';
				}

				break;
		}
	}	
			


	/**
	 * Description for Docs section
	 *
	 * @since 1.0
	 */
	public function display_docs_section() {
		
		// This displays on the "Documentation" tab. 
		
		include( get_stylesheet_directory() . '/includes/trusounder-theme-options-docs.php');
		
		
	}

	/* Initialize settings to their default values */
	public function initialize_settings() {
	
		$default_settings = array();
		foreach ( $this->settings as $id => $setting ) {
			if ( $setting['type'] != 'heading' )
				$default_settings[$id] = $setting['std'];
		}
	
		update_option( 'trusounder_options', $default_settings );
	
	}


	/* Register settings via the WP Settings API */
	public function register_settings() {

		register_setting( 'trusounder_options', 'trusounder_options', array ( &$this, 'validate_settings' ) );
		//register_setting( 'trusounder_options', 'trusounder_options' );

		foreach ( $this->sections as $slug => $title )
		
			if ( $slug == 'docs' ) {
				add_settings_section( $slug, $title, array( &$this, 'display_docs_section' ), 'trusounder-options' );
			} else {
				add_settings_section( $slug, $title, array( &$this, 'display_section' ), 'trusounder-options' );
			}

		$this->get_settings();
	
		foreach ( $this->settings as $id => $setting ) {
			$setting['id'] = $id;
			$this->create_setting( $setting );
		}

	}
	
	
	/* tool to create settings fields */
	public function create_setting( $args = array() ) {

		$defaults = array(
			'id'      => 'default_field',
			'title'   => 'Default Field',
			'desc'    => 'This is a default description.',
			'std'     => '',
			'type'    => 'text',
			'section' => 'general',
			'choices' => array(),
			'class'   => ''
		);

		extract( wp_parse_args( $args, $defaults ) );

		$field_args = array(
			'type'      => $type,
			'id'        => $id,
			'desc'      => $desc,
			'std'       => $std,
			'choices'   => $choices,
			'label_for' => $id,
			'class'     => $class
		);

		if ( $type == 'checkbox' )
			$this->checkboxes[] = $id;
				

		add_settings_field( $id, $title, array( $this, 'display_setting' ), 'trusounder-options', $section, $field_args );

	}
	
	
	/* jQuery Tabs */
	public function scripts() {
		wp_print_scripts( 'jquery-ui-tabs' );
	}
	
	public function validate_settings( $input ) {
		
		if ( ! isset( $input['reset_theme'] ) ) {
			$options = get_option( 'trusounder_options' );
			
			if ( $input['notify'] != $options['notify'] ) {
				$input['notify'] = str_replace(' ', '', $input['notify']);
			}

					
			foreach ( $this->checkboxes as $id ) {
				if ( isset( $options[$id] ) && ! isset( $input[$id] ) )
					unset( $options[$id] );
			}
			
			
			return $input;
		}
		
		return false;
		
		
	}
 }
 
$theme_options = new trusounder_Theme_Options();

function trusounder_option( $option ) {
	$options = get_option( 'trusounder_options' );
	if ( isset( $options[$option] ) )
		return $options[$option];
	else
		return false;
}
?>