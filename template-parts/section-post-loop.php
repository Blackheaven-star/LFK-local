<?php
$posts_per_page = 8;
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

// Detect if we're on a tag page
if (is_tag()) {
    $tag = get_queried_object();

    $args = [
        'post_type'      => 'post',
        'posts_per_page' => $posts_per_page,
        'paged'          => $paged,
        'tag_id'         => $tag->term_id, // ✅ filter by current tag
    ];
} else {
    $args = [
        'post_type'      => 'post',
        'posts_per_page' => $posts_per_page,
        'paged'          => $paged,
    ];
}

$blog_query = new WP_Query($args);
?>

<div class='main-mid'>
	<div class='_maxwrap'>
	
		<div class="blog-wrap">

			<?php if (is_tag()) : ?>

				<?php $tag = get_queried_object(); ?>
				<p class='tag-header'>Below you'll find a list of all posts that have been tagged as <strong>“<?php echo $tag->name; ?>”</strong></p>

			<?php endif; ?>

			<?php if ($blog_query->have_posts()) : ?>
				<?php while ($blog_query->have_posts()) : $blog_query->the_post(); ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class('blog-item'); ?>>
			            
			            <div class='article-wrap'>

				            <?php if (has_post_thumbnail()) : ?>
				                <div class="blog-thumbnail">
				                    <a href="<?php the_permalink(); ?>">
				                        <?php the_post_thumbnail('full'); ?>
				                    </a>
				                </div>
				            <?php endif; ?>
				            
				            <div class="blog-content">
				                <h2 class="blog-title">
				                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				                </h2>
				                <div class="excerpt">
				                    <?php the_excerpt(); ?>
				                </div>
				                <a class="read-more _btn" href='<?php the_permalink(); ?>'>Read More</a>
				            </div>
				            
			        	</div>

				        <div class="post-tags">
				            <?php $post_tags = get_the_tags(); ?>
				            <?php if ( $post_tags ) : ?>
				                <ul class="tags">
				                <?php foreach ( $post_tags as $tag ) : ?>
				                    <li>
				                    	<a href="<?php echo get_tag_link( $tag->term_id ); ?>"><?php echo $tag->name; ?></a>
									</li>
				                <?php endforeach; ?>
				                </ul>
				            <?php endif; ?>
				        </div>
			            
			        </article>

		        <?php endwhile; ?>
		    <?php endif; ?>

			<div class="pagination">
			    <?php 
			    $big = 999999999;
			    echo paginate_links([
			        'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
			        'format'    => '?paged=%#%',
			        'current'   => max(1, $paged),
			        'total'     => $blog_query->max_num_pages,
			        'prev_text' => '←',
			        'next_text' => '→',
			    ]);
			    ?>
			</div>

		    <?php wp_reset_postdata(); ?>

		</div>

	</div>
</div>