<?php
/*
Template Name: Contributors template
*/

$allUsers = get_users('orderby=post_count&order=DESC');

$user_num = 0;
$users = array();

foreach ( $allUsers as $currentUser ) {
	if ( !in_array( 'subscriber', $currentUser->roles ) // If the user isn't a subscriber...
	&& ( !count_user_posts( $currentUser->ID ) == 0 )  // ...and the user has published one post or more...
	&& ( !$currentUser->hideauthor == "yes" ) ) { // ...and the user hasn't been checked as hidden on the user profile...
		
		$users[] = $currentUser; // ...add the user to the array of users that is going to be displayed
			
	}
} ?>

<?php get_header(); ?>

<div class="wrapper section medium-padding">						

	<div class="section-inner">
	
		<div class="content fleft">
					
			<div <?php post_class('single post'); ?>>
			
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
																		
					<div class="post-header">
												
					    <h2 class="post-title"><?php the_title(); ?></h2>
					    				    
				    </div> <!-- /post-header -->
				    
					<?php if($post->post_content != "") : ?>
				   				        			        		                
						<div class="post-content">
									                                        
							<?php the_content(); ?>
																            			                        
						</div> <!-- /post-content -->
					
					<?php endif; ?>
					
					<div class="contributors-container">
					
						<?php 
						
						$i = 0;
						
						foreach($users as $user) {
						
							if($i%2 == 0) {
								echo $i > 0 ? "<div class='clear'></div></div>" : ""; // close div if it's not the first
								echo "<div class='authors-row row'>";
							}
							?>
														
							<div class="one-half author-info">
							
								<a href="<?php echo get_author_posts_url( $user->ID ); ?>" class="author-avatar"><?php echo get_avatar( $user->user_email, '256' ); ?></a>
							
								<h4><a href="<?php echo get_author_posts_url( $user->ID ); ?>"><?php echo $user->display_name; ?></a></h4>
								
								<h5>
								
									<a href="<?php echo get_author_posts_url( $user->ID ); ?>">
									
										<?php echo count_user_posts( $user->ID ); ?>
									
										<?php if ( count_user_posts( $user->ID ) == 1 ) { echo ' post'; } else { echo ' posts'; } ?>
										
									</a>
									
								</h5>
								
								<p class="author-description"><?php echo get_user_meta($user->ID, 'description', true); ?></p>
			
								<div class="author-links">
								
									<?php if ( !empty($user->user_email) ) : ?><a class="author-link-mail" href="mailto:<?php echo $user->user_email; ?>"><?php _e('E-mail','baskerville'); ?></a><?php endif; ?>
									
									<?php if ( !empty($user->user_url) ) : ?><a class="author-link-website" href="<?php echo $user->user_url; ?>"><?php _e('Website', 'baskerville') ?></a><?php endif; ?>
									<?php if ( !empty($user->twitter) ) : ?><a class="author-link-twitter" href="http://www.twitter.com/<?php echo $user->twitter; ?>"><?php _e('Twitter', 'baskerville'); ?></a><?php endif; ?>
									
								</div> <!-- /author-links -->
								
							</div> <!-- /author-info -->
							
							<?php $i++; ?>
															
						<?php } ?>
						
						<div class="clear"></div>
																		
					</div> <!-- /authors-row -->
					
				</div> <!-- /contributors-container -->
				
				<?php comments_template( '', true ); ?>
						
				<?php endwhile; endif; ?>
	
			</div> <!-- /post -->
				
		</div> <!-- /content -->
		
		<?php get_sidebar(); ?>
			
		<div class="clear"></div>
	
	</div> <!-- /section-inner -->
	
</div> <!-- /wrapper -->
								
<?php get_footer(); ?>