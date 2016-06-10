<?php

if ( !is_user_logged_in() ) {
	// already not logged in? go to desk.
  	wp_redirect ( site_url() . '/desk' );
  	exit;
  	
} elseif ( !current_user_can( 'edit_others_posts' ) ) {
	// okay user, who are you? we know you are not an admin or editor
		
	// if the collector user not found, we send you to the desk
	if ( !trusounder_check_user() ) {
		// now go to the desk and check in properly
	  	wp_redirect ( site_url() . '/desk' );
  		exit;
  	}
}

		

// ------------------------ defaults ------------------------

// default welcome message
$feedback_msg = 'So you want to add some sound files to this collection? We have a form for you!';

$wAuthor = 'Anonymous';
				
$wCats = array( trusounder_option('def_cat')); // preload default category
$wLicense = '--';
$all_licenses = trusounder_get_licences();


// not yet saved
$is_published = false;
$box_style = '<div class="notify"><span class="symbol icon-info"></span> ';


// ------------------- form processing ------------------------

// verify that a form was submitted and it passes the nonce check
if ( isset( $_POST['trusounder_form_make_submitted'] ) && wp_verify_nonce( $_POST['trusounder_form_make_submitted'], 'trusounder_form_make' ) ) {
 
 		// grab the variables from the form
 		$wTitle = 					sanitize_text_field( stripslashes( $_POST['wTitle'] ) );
 		$wAuthor = 					( isset ($_POST['wAuthor'] ) ) ? sanitize_text_field( stripslashes($_POST['wAuthor']) ) : 'Anonymous';		
 		$wTags = 					sanitize_text_field( $_POST['wTags'] );	
 		$wText = 					sanitize_text_field( stripslashes( $_POST['wText'] ) );
 		$wCredit = 					sanitize_text_field( $_POST['wCredit']  );
 		$wNotes = 					sanitize_text_field( stripslashes( $_POST['wNotes'] ) );
 		$wAudioURL = 				$_POST['wAudioURL'];
 		$wCats = 					( isset ($_POST['wCats'] ) ) ? $_POST['wCats'] : array();
 		$wLicense = 				$_POST['wLicense'];
 		
 		

 		
 		// let's do some validation, store an error message for each problem found
 		$errors = array();
 		
 		if ( $wAudioURL == '' ) {
 			$errors[] = '<strong>Audio Missing</strong> - you can either upload and audio file or enter an external URL for where it is found, but we need something!'; 
 		} elseif (!url_is_audio( $wAudioURL ) ) {
 			$errors[] = '<strong>Wrong File Type</strong> -  At this time we can only accept MP3 audio files, try again? Perhaps try <a href="https://cloudconvert.com/" target="_blank">CloudConvert</a> to get your audio in mp3 format.'; 
 		}
 		
 		if ( $wTitle == '' ) $errors[] = '<strong>Title Missing</strong> - enter an interesting title.'; 
 		if ( $wLicense == '--' ) $errors[] = '<strong>License Not Selected</strong> - select an appropriate license for this audio file.'; 

 		
 		if ( count($errors) > 0 ) {
 			// form errors, build feedback string to display the errors
 			$feedback_msg = 'Sorry, but there are a few errors in your information. Please correct and try again. We really want to add your entry.<ul>';
 			
 			// Hah, each one is an oops, get it? 
 			foreach ($errors as $oops) {
 				$feedback_msg .= '<li>' . $oops . '</li>';
 			}
 			
 			$feedback_msg .= '</ul>';
 			
 			$box_style = '<div class="notify notify-red"><span class="symbol icon-error"></span> ';
 			
 		} else {
 			
 			// good enough, let's make a post! 
 			 			
			$w_information = array(
				'post_title' => $wTitle,
				'post_content' => $wText,
				'post_status' => trusounder_option('new_item_status'),
				'post_category' => $wCats		
			);

			// insert as a new post
			$post_id = wp_insert_post( $w_information );
			
			
			//sets to 'audio' post-format
			set_post_format( $post_id, 'audio' ); 
			
			// store audio url
			add_post_meta( $post_id, 'audio_url', $wAudioURL);
			
			// store the author as post meta data
			add_post_meta( $post_id, 'shared_by', $wAuthor );
			
			// store the name of person to credit
			add_post_meta( $post_id, 'credit', $wCredit );

			// store the license code
			add_post_meta( $post_id, 'license', $wLicense );

			// store extra notes
			if ( $wExtraNotes ) add_post_meta($post_id, 'extra_notes', $wExtraNotes);
			
			// add the tags
			wp_set_post_tags( $post_id, $wTags);
		
							
			

			if ( trusounder_option('new_item_status') == 'publish' ) {
				// feed back for published item
				$feedback_msg = 'Your shared sound  <strong>' . $wTitle . '</strong> has been published!  You can <a href="'. wp_logout_url( site_url() . '/?p=' . $post_id  )  . '">view it now</a>. Or you can <a href="' . site_url() . '/share">share another</a>.';
			
			} else {
				// feed back for item left in draft
				$feedback_msg = 'Your shared sound <strong>' . $wTitle . '</strong> has been submitted as a draft. You can <a href="'. wp_logout_url( site_url() . '/?p=' . $post_id  )  . '">preview it now</a>. Once it has been approved by a moderator, everyone can see it.';	
			
			}		
							
			// set the gate	open, we are done.
			
			$is_published = true;
			$box_style = '<div class="notify notify-green"><span class="symbol icon-tick"></span> ';	
			
		} // count errors		
		
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
				
					<?php if ( has_post_thumbnail() and !isset( $_POST['trusounder_form_make_submitted'] ) )  : ?>
						
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
	
			    	<?php 
					if ( !is_user_logged_in() ) :?>
						<a href="<?php echo get_bloginfo('url')?>/wp-login.php?autologin=sounder">activate lasers</a>
					<?php endif?>
		    	
		    		<?php echo $box_style . $feedback_msg . '</div>';?>   
		    				
			    	<?php wp_link_pages('before=<div class="clear"></div><p class="page-links">' . __('Pages:','fukasawa') . ' &after=</p>&seperator= <span class="sep">/</span> '); ?>


	<?php if ( is_user_logged_in() and !$is_published ) : // show form if logged in and it has not been published ?>
			
		<form  id="sounderform" class="sounderform" method="post" action="" enctype="multipart/form-data">

				<fieldset>
					<label for="headerImage"><?php _e('Upload a Sound File', 'baskerville') ?></label>
					<p>Upload your sound file by dragging its icon to the window that opens when clicking  <strong>Upload Sound</strong> button.</p>
					<div class="uploader">
						<input type="button" id="wFeatureImage_button"  class="btn btn-success btn-medium  upload_image_button" name="_wImage_button"  data-uploader_title="Add a New Sound" data-uploader_button_text="Select Sound" value="Upload Sound" tabindex="1" />
						
						</div>
						
						<p>You can  upload MP3 files less than 20Mb in size. Or you can simply enter the URL for any externally hosted MP3 audio file  a SoundCloud track.<br clear="left"></p>
					
					<label for="wAudioURL"><?php _e('Audio URL', 'baskerville' ) ?></label><br />
					<p>Must be a fully formed URL (including http:// or https://) to an MP3 file or SoundCloud track</p>
					<input type="text" name="wAudioURL" id="wAudioURL" class="required" value="<?php echo $wAudioURL; ?>" tabindex="2" />
					
					
				</fieldset>						


				<fieldset>
					<label for="wTitle"><?php _e('Title for the Sound', 'baskerville' ) ?></label><br />
					<p>An interesting title goes a long way; it's the headline.</p>
					<input type="text" name="wTitle" id="wTitle" class="required" value="<?php echo $wTitle; ?>" tabindex="3" />
				</fieldset>	

				<fieldset>
					<label for="wCats"><?php _e( 'Categories', 'baskerville' ) ?></label>
					<p>Check all that apply.</p>
					<?php 
					
					// set up arguments to get all categories that are children of "Published"
					$args = array(
						'hide_empty'               => 0,
					); 
					
					$article_cats = get_categories( $args );

					foreach ( $article_cats as $acat ) {
					
						$checked = ( in_array( $acat->term_id, $wCats) ) ? ' checked="checked"' : '';
						
						echo '<br /><input type="checkbox" name="wCats[]" tabindex="4" value="' . $acat->term_id . '"' . $checked . '> ' . $acat->name;
					}
					
					?>
					
				</fieldset>

				<fieldset>
					<label for="wTags"><?php _e( 'Tags', 'baskerville' ) ?></label>
					<p>Descriptive tags, separate multiple ones with commas</p>
					
					<input type="text" name="wTags" id="wTags" value="<?php echo $wTags; ?>" tabindex="5"  />
				</fieldset>
				
				<fieldset>
						<label for="wText"><?php _e('Caption', 'baskerville') ?></label>
						<p>Enter a descriptive caption to include with the audio.  </p>
						<textarea name="wText" id="wText" rows="15"  tabindex="6"><?php echo stripslashes( $wText );?></textarea>

				</fieldset>				

				<fieldset>
					<label for="wAuthor"><?php _e('Who is Uploading the Audio? (This would be You)', 'baskerville' ) ?></label><br />
					<p>Take credit for sharing this audio by entering your name, twitter handle, secret agent name, or remain "Anonymous".</p>
					<input type="text" name="wAuthor" id="wAuthor" class="required" value="<?php echo $wAuthor; ?>" tabindex="7" />
				</fieldset>	
				
				<fieldset>
					<label for="wCredit"><?php _e('Creator Name', 'baskerville' ) ?></label><br />
					<p>Enter a name of a person, web site, etc to give credit for the audio.</p>
					<input type="text" name="wCredit" id="wCredit" class="required" value="<?php echo $wCredit; ?>" tabindex="8" />
				</fieldset>					
				
				<fieldset>
					<label for="wLicense"><?php _e('License for Reuse', 'baskerville' ) ?></label><br />
					<p>If found online, indicate the license attached to it. If this is an original audio, then select a license to attach to it.</p>
					<select name="wLicense" id="wLicense" tabindex="9" />
					<option value="--">Select a License</option>
					<?php
						foreach ($all_licenses as $key => $value) {
							$selected = ( $key == $wLicense ) ? ' selected' : '';
							echo '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
						}
					?>
					
					</select>
				</fieldset>					


			
				<fieldset>
				
				<?php  wp_nonce_field( 'trusounder_form_make', 'trusounder_form_make_submitted' ); ?>
				
				<input type="submit" class="pretty-button pretty-button-green" value="Share Audio" id="makeit" name="makeit" tabindex="12">
				</fieldset>
			
						
		</form>
	<?php endif?>
				    
						<div class="clear"></div>
															            			                        
					</div> <!-- /post-content -->
					
									
				</div> <!-- /post -->
			
			<?php endwhile; else: ?>
			
				<p><?php _e("We couldn't find any posts that matched your query. Please try again.", "baskerville"); ?></p>
		
			<?php endif; ?>
		
			<div class="clear"></div>
			
		</div> <!-- /content -->
		
		
		<div class="clear"></div>
	
	</div> <!-- /section-inner -->

</div> <!-- /wrapper -->
								
<?php get_footer(); ?>