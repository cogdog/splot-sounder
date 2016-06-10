<?php $audio_url = get_post_meta($post->ID, 'audio_url', true); ?>

<div class="post-header">
	
    <h2 class="post-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
    
    <?php if( is_sticky() ) { ?> <span class="sticky-post"><?php _e('Sticky post', 'baskerville'); ?></span> <?php } ?>
    
</div> <!-- /post-header -->

<?php if ( $audio_url != '' ) : ?>
	
	<div class="post-audio">
	
		<audio controls="controls" class="audio-player">
		
			<source src="<?php echo $audio_url; ?>" />
			
		</audio>
	
	</div> <!-- /post-audio -->
		
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
		
		if ( $audio_url  ) {
			echo '<a class="post-download-link" href="' . $audio_url . '" download>Download</a>';
		}
		
		edit_post_link(); 
	
	?>
		
	<div class="clear"></div>

</div>
            
<div class="clear"></div>