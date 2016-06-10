<?php 

	get_header(); 
	
	$format = get_post_format();
	
?>

<div class="wrapper section medium-padding">
										
	<div class="section-inner">
	
		<div class="content fleft">
												        
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
						
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				
					<?php if ($format == 'quote' || $format == 'link' || $format == 'audio' || $format == 'status' || $format == 'chat') : ?>
					
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
					
					<?php 

					if ($format == 'video') : ?> 
					
						<?php $video_url = get_post_meta($post->ID, 'video_url', true); ?>

						<div class="featured-media">
						
							<?php if (strpos($video_url,'.mp4') !== false) : ?>
								
								<video controls>
								  <source src="<?php echo $video_url; ?>" type="video/mp4">
								</video>
																						
							<?php else : ?>
								
								<?php 
								
									$embed_code = wp_oembed_get($video_url); 
									
									echo $embed_code;
									
								?>
									
							<?php endif; ?>
							
						</div>
						
					<?php elseif ($format == 'audio') : ?>
					
						<?php $audio_url = get_post_meta($post->ID, 'audio_url', true); ?>
	
						<div class="post-audio">
						
							<audio controls="controls" id="audio-player">
							
								<source src="<?php echo $audio_url; ?>" />
								
							</audio>
						
						</div> <!-- /post-audio -->
					
					<?php elseif ($format == 'quote') : ?> 
					
						<?php $quote_content = get_post_meta($post->ID, 'quote_content', true); ?>
						<?php $quote_attribution = get_post_meta($post->ID, 'quote_attribution', true); ?>
					
						<div class="post-quote">

							<blockquote><?php echo $quote_content; ?></blockquote>
							
							<?php if ( $quote_attribution != '' ) : ?>
							
								<cite><?php echo $quote_attribution; ?></cite>
							
							<?php endif; ?>
						
						</div> <!-- /post-quote -->
						
					<?php elseif ($format == 'link') : ?> 
					
						<?php $link_url = get_post_meta($post->ID, 'link_url', true); ?>
						<?php $link_title = get_post_meta($post->ID, 'link_title', true); ?>
					
						<div class="post-link">
						
							<p><?php echo $link_title; ?></p>
							
							<a href="<?php echo $link_url; ?>" title="<?php echo $link_title; ?>"><?php echo url_to_domain( $link_url ); ?></a>
						
						</div> <!-- /post-link -->
						
					<?php elseif ($format == 'gallery') : ?> 
					
						<div class="featured-media">

							<?php baskerville_flexslider('post-image'); ?>
											
						</div> <!-- /featured-media -->
				
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
								
						<?php wp_link_pages(); ?>
						
						<div class="clear"></div>
									        
					</div> <!-- /post-content -->
					            					
					<div class="post-meta-container">
						
						<div class="post-author">
						
							<div class="post-author-content">
							
								<h4><?php the_author_meta('display_name'); ?></h4>
								
								<p><?php the_author_meta('description'); ?></p>
								
								<?php
									if(isset($_GET['author_name'])) :
										$curauth = get_userdatabylogin($author_name);
									else :
										$curauth = get_userdata(intval($author));
									endif;
								?>
								
								<div class="author-links">
									
									<a class="author-link-posts" title="<?php _e('Author archive','baskerville'); ?>" href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>"><?php _e('Author archive','baskerville'); ?></a>
									
									<?php $author_url = get_the_author_meta('user_url'); 
								
									$author_url = preg_replace('#^https?://#', '', rtrim($author_url,'/'));
																	
									if (!empty($author_url)) : ?>
									
										<a class="author-link-website" title="<?php _e('Author website','baskerville'); ?>" href="<?php the_author_meta('user_url'); ?>"><?php _e('Author website','baskerville'); ?></a>
										
									<?php endif;
									
									$author_mail = get_the_author_meta('email'); 
									
									$show_mail = get_the_author_meta('showemail');
																	
									if ( !empty($author_mail) && ($show_mail == "yes") ) : ?>
									
										<a class="author-link-mail" title="<?php echo $author_mail; ?>" href="mailto:<?php echo $author_mail ?>"><?php echo $author_mail; ?></a>
										
									<?php endif;
									
									$author_twitter = get_the_author_meta('twitter'); 
																	
									if (!empty($author_twitter)) : ?>
									
										<a class="author-link-twitter" title="<?php echo '@' . $author_twitter . ' '; ?><?php _e('on Twitter','baskerville'); ?>" href="http://www.twitter.com/<?php echo $author_twitter ?>"><?php echo '@' . $author_twitter . ' '; ?><?php _e('on Twitter','baskerville'); ?></a>
										
									<?php endif; ?>
									
								</div> <!-- /author-links -->
							
							</div> <!-- /post-author-content -->
						
						</div> <!-- /post-author -->
						
						<div class="post-meta">
						
							<p class="post-date"><?php the_time( get_option( 'date_format' ) ); ?></p>
							
							<?php if( function_exists('zilla_likes') ) zilla_likes(); ?>
							
							<p class="post-categories"><?php the_category(', '); ?></p>
							
							<?php if (has_tag()) : ?>
							
								<p class="post-tags"><?php the_tags('', ', '); ?></p>
							
							<?php endif; ?>
							
							<div class="clear"></div>
							
							<div class="post-nav">
							
								<?php
								$prev_post = get_previous_post();
								if (!empty( $prev_post )): ?>
								
									<a class="post-nav-prev" title="<?php _e('Previous post:', 'baskerville'); echo ' ' . esc_attr( get_the_title($prev_post) ); ?>" href="<?php echo get_permalink( $prev_post->ID ); ?>"><?php _e('Previous post', 'baskerville'); ?></a>
							
								<?php endif; ?>
								
								<?php
								$next_post = get_next_post();
								if (!empty( $next_post )): ?>
									
									<a class="post-nav-next" title="<?php _e('Next post:', 'baskerville'); echo ' ' . esc_attr( get_the_title($next_post) ); ?>" href="<?php echo get_permalink( $next_post->ID ); ?>"><?php _e('Next post', 'baskerville'); ?></a>
							
								<?php endif; ?>
								
								<?php edit_post_link( __('Edit post', 'baskerville')); ?>
									
								<div class="clear"></div>
							
							</div>
						
						</div> <!-- /post-meta -->
						
						<div class="clear"></div>
							
					</div> <!-- /post-meta-container -->
																		
					<?php comments_template( '', true ); ?>
												                        
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