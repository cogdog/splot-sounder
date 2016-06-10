<div class="post-header">
	
    <h2 class="post-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
    
    <?php if( is_sticky() ) { ?> <span class="sticky-post"><?php _e('Sticky post', 'baskerville'); ?></span> <?php } ?>
    
</div> <!-- /post-header -->

<?php $video_url = get_post_meta($post->ID, 'video_url', true); if ( $video_url != '' ) : ?>

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

<?php endif; ?>

<?php if($post->post_content != "") : ?>
									                                    	    
	<div class="post-excerpt">
		    		            			            	                                                                                            
		<?php the_excerpt('100'); ?>
	
	</div> <!-- /post-excerpt -->

<?php endif; ?>
									                                    	    
<div class="post-meta">

	<a class="post-date" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_time( 'Y/m/d' ); ?></a>
	
	<?php
	
		if( function_exists('zilla_likes') ) zilla_likes(); 
	
		if ( comments_open() ) {
			comments_popup_link( '0', '1', '%', 'post-comments' );
		}
		
		edit_post_link(); 
	
	?>
	
	<div class="clear"></div>

</div>
            
<div class="clear"></div>