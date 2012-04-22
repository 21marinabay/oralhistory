<?php get_header(); ?>
<?php $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); ?>
<div id="container">
    <div id="content" role="main">

		<h1 class="page-title"><?php echo $term->name; ?> Archives</h1>
		
		<?php 
			echo $mc_var->get_category_archive($term->term_id);
		?>

    </div>
</div>
<?php get_footer(); ?>