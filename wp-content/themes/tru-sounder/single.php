<?php 

	get_header(); 
	
	$format = get_post_format();
	
	$wCredit = get_post_meta( $post->ID, 'credit', 1 );
	$wAuthor = get_post_meta( $post->ID, 'shared_by', 1 );
	$wExtraNotes = get_post_meta( $post->ID, 'extra_notes', 1 );
	$wLicense = get_post_meta( $post->ID, 'license', 1 );
	
	$audio_url = get_post_meta($post->ID, 'audio_url', 1);
	$is_soundcloud = url_is_soundcloud ($audio_url);

	
?>

<div class="wrapper section medium-padding">
										
	<div class="section-inner">
	
		<div class="content fleft">
												        
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
						
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				
					<?php if ($format == 'audio') : ?>
					
						<?php if ( has_post_thumbnail() ) : ?>
					
							<div class="featured-media">
							
								<?php the_post_thumbnail('post-image'); ?>
								
								<?php if ( !empty(get_post(get_post_thumbnail_id())->post_excerpt) ) : ?>
												
									<div class="media-caption-container">
									
										<p class="media-caption"><?php echo get_post(get_post_thumbnail_id())->post_excerpt; ?></p>
										
									</div>
									
								<?php endif; ?>
										
							</div> <!-- /featured-media -->
						
						<?php endif; ?>
					
					<?php endif; ?>
				
					<div class="post-header">

					    <h2 class="post-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
					    
					</div> <!-- /post-header -->
					
						
					<?php if ($format == 'audio') : ?>
					
	
						<div class="post-audio">

						<?php if ( $is_soundcloud  ):?>
						
							<?php echo wp_oembed_get( $audio_url, array('height'=>240) );?>
	
	
						<?php else: ?>
							<audio controls="controls" class="audio-player">
		
								<source src="<?php echo $audio_url; ?>" />
			
							</audio>

						<?php endif ?>
	
						</div> <!-- /post-audio -->

									
					<?php elseif ( has_post_thumbnail() ) : ?>
					
						<div class="featured-media">
						
							<?php the_post_thumbnail('post-image'); ?>
							
							<?php if ( !empty(get_post(get_post_thumbnail_id())->post_excerpt) ) : ?>
											
								<div class="media-caption-container">
								
									<p class="media-caption"><?php echo get_post(get_post_thumbnail_id())->post_excerpt; ?></p>
									
								</div>
								
							<?php endif; ?>
									
						</div> <!-- /featured-media -->
					
					<?php endif; ?>
														                                    	    
					<div class="post-content">
						 		            			            	                                                                                            
						<?php the_content(); ?>


			    	<p>
			    	<?php 
			    		if ( $audio_url and ( !$is_soundcloud  )  ) echo '<a href="' . $audio_url . '" download>Download "' . get_the_title() . '"</a><br />';

			    		if ( $wExtraNotes ) echo '<strong>Extra Notes:</strong> ' .  $wExtraNotes;
			    	?>
			    	
			    	
			    		
			    		<hr />
						<?php $attributions = trusounder_attributor( $wLicense, get_the_title(), get_permalink(), $wCredit);?>
						
						<h4>Copy/Paste Text Attribution</h4>
						<textarea rows="2" onClick="this.select()" style="height:80px;"><?php echo $attributions[0]?></textarea>


						<h4>Copy/Paste HTML Attribution</h4>
						<textarea rows=5" onClick="this.select()" style="height:110px;"><?php echo $attributions[1]?></textarea>
						

			    	</p>

								
						<?php wp_link_pages(); ?>
						
						<div class="clear"></div>
									        
					</div> <!-- /post-content -->
					            					
					<div class="post-meta-container">
						
						<div class="post-author">
						
							<div class="post-author-content">
							
								<h4>Shared by <?php echo $wAuthor?></h4>
								
							<p>
							<?php 
								if ( $wCredit ) echo '<strong>Credit:</strong> ' . $wCredit . '<br />';
								if ( $wSource ) echo '<strong>Source:</strong> ' .  make_links_clickable( $wSource ) . '<br />';
								echo '<strong>URL:</strong> ' .  $audio_url . '<br />';
								echo '<strong>License:</strong> ';
								trusounder_the_license( $wLicense );
								
							?>
			    	</p>

								
								
								
							
							</div> <!-- /post-author-content -->
						
						</div> <!-- /post-author -->
						
						<div class="post-meta">
						
							<p class="post-date"><?php the_time( get_option( 'date_format' ) ); ?></p>
							
							<?php if( function_exists('zilla_likes') ) zilla_likes(); ?>
							
							<p class="post-categories"><?php the_category(', '); ?></p>
							
							<?php if (has_tag()) : ?>
							
								<p class="post-tags"><?php the_tags('', ', '); ?></p>
							
							<?php endif; ?>
							
							<?php 
							if ( $audio_url and !$is_soundcloud  ) {
								echo '<p class="post-download"><a href="' . $audio_url . '" download>Download</a></p>';
							}
							?>
							
							<div class="clear"></div>
							
							<div class="post-nav">
							
								<?php
								$prev_post = get_previous_post();
								if (!empty( $prev_post )): ?>
								
									<a class="post-nav-prev" title="<?php _e('Previous sound:', 'baskerville'); echo ' ' . esc_attr( get_the_title($prev_post) ); ?>" href="<?php echo get_permalink( $prev_post->ID ); ?>"><?php _e('Previous sound', 'baskerville'); ?></a>
							
								<?php endif; ?>
								
								<?php
								$next_post = get_next_post();
								if (!empty( $next_post )): ?>
									
									<a class="post-nav-next" title="<?php _e('Next sound:', 'baskerville'); echo ' ' . esc_attr( get_the_title($next_post) ); ?>" href="<?php echo get_permalink( $next_post->ID ); ?>"><?php _e('Next sound', 'baskerville'); ?></a>
							
								<?php endif; ?>
								
								<?php edit_post_link( __('Edit sound', 'baskerville')); ?>
									
								<div class="clear"></div>
							
							</div>
						
						</div> <!-- /post-meta -->
						
						<div class="clear"></div>
							
					</div> <!-- /post-meta-container -->
																		
					<?php if ( trusounder_option('allow_comments') ) comments_template( '', true ); ?>
												                        
			   	<?php endwhile; else: ?>
			
					<p><?php _e("We couldn't find any posts that matched your query. Please try again.", "baskerville"); ?></p>
				
				<?php endif; ?>    
		
			</div> <!-- /post -->
		
		</div> <!-- /content -->
		
		<?php get_sidebar(); ?>
		
		<div class="clear"></div>
		
	</div> <!-- /section-inner -->

</div> <!-- /wrapper -->
		
<?php get_footer(); ?>