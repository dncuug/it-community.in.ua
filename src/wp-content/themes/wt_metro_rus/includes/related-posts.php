<?php
$tags = wp_get_post_tags($post->ID);
if ($tags) {
	$tag_ids = array();
	foreach($tags as $single_tag) $tag_ids[] = $single_tag->term_id;

	$args=array(
		'tag__in' => $tag_ids,
		'post__not_in' => array($post->ID),
		'showposts'=> 4, //number of related posts
		'ignore_sticky_posts'=> 1
	);	
	
} else {

	$categories = get_the_category($post->ID);
	
	if ($categories) {
		$category_ids = array();
		foreach($categories as $single_category) $category_ids[] = $single_category->term_id;

		$args=array(
			'category__in' => $category_ids,
			'post__not_in' => array($post->ID),
			'showposts'=> 3,  //number of related posts
			'ignore_sticky_posts'=>1
		);
		
	}
}

if($args){

	$my_query = new wp_query($args);
	
	if( $my_query->have_posts() ) {
		echo '<div class="related-posts"><h3>Related Posts</h3><ul>';
		
		
		while ($my_query->have_posts()) {
			$my_query->the_post();			
     		?>
			<li>
				<a href="<?php the_permalink() ?>"><?php the_post_thumbnail('wt-medium-thumb', array('title' => ''.get_the_title().'' )); ?></a>
				<h4><a href="<?php the_permalink() ?>">
						<?php 
							//display only first 40 characters in the title.	
							$short_title = mb_substr(the_title('','',FALSE),0, 40);
							echo $short_title; 
							if (strlen($short_title) > 29){ 
								echo '...'; 
							} 
						?>	
				</a>
				</h4>
				<div class="date"><?php the_time('F j, Y'); ?></div>				
			</li>
		<?php
		}
		echo '</ul></div>';
	}
	 wp_reset_query();
	
}

?>