<?php

// ------------------------ defaults ------------------------

// default welcome message
$feedback_msg = '';

// the passcode to enter
$wAccessCode = trusounder_option('accesscode');


// ------------------------ door check -----------------------


// already logged in? go directly to the tool
if ( is_user_logged_in() ) {
	
	if ( current_user_can( 'edit_others_posts' ) ) {

		// If user has edit/admin role, send them to the tool
		wp_redirect ( site_url() . '/share' );
  		exit;

	} else {
	
		// if the correct user found, go directly to the tool
		if ( trusounder_check_user() ) {			
	  		wp_redirect ( site_url() . '/share' );
  			exit;
  			
  		} else {
			// we need to force a click through a logout
			$log_out_warning = true;
			$feedback_msg = 'First, please <a href="' . wp_logout_url( site_url() . '/share' ) . '">activate lasers</a>';
  		}
  	}
  	
} elseif ( $wAccessCode == '')  {
	
	// no code required, log 'em in
	wp_redirect ( site_url() . '/wp-login.php?autologin=sounder' );
	exit;

}


// ------------------------ presets ------------------------


// verify that a  form was submitted and it passes the nonce check
if ( 	isset( $_POST['trusounder_form_access_submitted'] ) 
		&& wp_verify_nonce( $_POST['trusounder_form_access_submitted'], 'trusounder_form_access' ) ) {
 
	// grab the variables from the form
	$wAccess = 	stripslashes( $_POST['wAccess'] );
	
	// let's do some validation, store an error message for each problem found
	$errors = array();
	
	if ( $wAccess != $wAccessCode ) $errors[] = '<p><strong>Incorrect Access Code</strong> - try again? Hint: ' . trusounder_option('accesshint'); 	
	
	if ( count($errors) > 0 ) {
		// form errors, build feedback string to display the errors
		$feedback_msg = '';
		
		// Hah, each one is an oops, get it? 
		foreach ($errors as $oops) {
			$feedback_msg .= $oops;
		}
		
		$feedback_msg .= '</p>';
		
	} else {

		wp_redirect ( site_url() . '/wp-login.php?autologin=sounder' );
		exit;
	}

		
} // end form submmitted check
?>

<?php get_header(); ?>

<div class="wrapper section medium-padding">
										
	<div class="section-inner">
	
		<div class="content full-width">
	
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		
				<div class="post">
				
					<div class="post-header">
												
					    <h2 class="post-title"><?php the_title(); ?></h2>
					    				    
				    </div> <!-- /post-header -->
				
					<?php if ( has_post_thumbnail() ) : ?>
						
						<div class="featured-media">
						
							<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>">
							
								<?php the_post_thumbnail('post-image'); ?>
								
								<?php if ( !empty(get_post(get_post_thumbnail_id())->post_excerpt) ) : ?>
												
									<div class="media-caption-container">
									
										<p class="media-caption"><?php echo get_post(get_post_thumbnail_id())->post_excerpt; ?></p>
										
									</div>
									
								<?php endif; ?>
								
							</a>
									
						</div> <!-- /featured-media -->
							
					<?php endif; ?>
				   				        			        		                
					<div class="post-content">
			    
			    	<?php the_content(); ?>
	
					<?php if ($log_out_warning):?>
						<div class="notify notify-green"><span class="symbol icon-tick"></span>
						<?php echo $feedback_msg?>
						</div>

					<?php else:?>
					
	
					
						<?php  
						// set up box code colors CSS

						if ( count( $errors ) ) {
							$box_style = '<div class="notify notify-red"><span class="symbol icon-error"></span> ';
							echo $box_style . $feedback_msg . '</div>';
						} 
						?>   

	 					<form  id="trusounderdesk" class="trusounderdesk" method="post" action="">
					
								<fieldset>
									<label for="wAccess"><?php _e('Access Code', 'fukasawa' ) ?></label><br />
									<p>Enter the secret code</p>
									<input type="text" name="wAccess" id="wAccess" class="required" value="<?php echo $wAccess; ?>" tabindex="1" />
								</fieldset>	
			
								<fieldset>
									<?php wp_nonce_field( 'trusounder_form_access', 'trusounder_form_access_submitted' ); ?>
									<input type="submit" class="pretty-button pretty-button-blue" value="Check Code" id="checkit" name="checkit" tabindex="15">
								</fieldset>
				
						</form>
				
					<?php endif?>
					
					<div class="clear"></div>
															            			                        
					</div> <!-- /post-content -->
					
					<?php comments_template( '', true ); ?>
									
				</div> <!-- /post -->
			
			<?php endwhile; else: ?>
			
				<p><?php _e("We couldn't find any posts that matched your query. Please try again.", "baskerville"); ?></p>
		
			<?php endif; ?>
		
			<div class="clear"></div>
			
		</div> <!-- /content -->
		
		<?php get_sidebar(); ?>
		
		<div class="clear"></div>
	
	</div> <!-- /section-inner -->

</div> <!-- /wrapper -->
								
<?php get_footer(); ?>