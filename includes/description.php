<?php




	// Add post thumbnail theme support

	add_theme_support( 'post-thumbnails' ); 




	// Allow excerpt for pages and posts

	add_post_type_support( 'page', 'excerpt' );
	add_post_type_support( 'post', 'excerpt' );





	// Prepare description

	function fvw_prepdesc( $desc ) {

		return esc_attr( trim( str_replace( PHP_EOL, ' ', strip_tags( $desc ) ) ) );

	};



	// Add meta description

	add_action( 'wp_head', 'fvw_framework_meta_description' );

	function fvw_framework_meta_description() {

		echo "\n\n";

		if( is_singular() ):

			echo '<meta name="description" content="' . fvw_prepdesc( get_the_excerpt() ) . '">' . "\n";

		elseif( is_category() ):

			global $cat;

			echo '<meta name="description" content="' . fvw_prepdesc( category_description( $cat ) ) . '">' . "\n";

		endif;

		echo "\n\n";

	}



	// Add open graph tags to html head

	add_action( 'wp_head', 'fvw_framework_meta_opengraph' );

	function fvw_framework_meta_opengraph() {

		echo "\n\n";

		if( is_singular() ):

			echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'title' ) ) . '"/>' . "\n";
			echo '<meta property="og:url" content="' . esc_attr( get_permalink() ) . '" />' . "\n";
			echo '<meta property="og:title" content="' . esc_attr( get_the_title() ) . '" />' . "\n";
			echo '<meta property="og:description" content="' . fvw_prepdesc( get_the_excerpt() ) . '"/>' . "\n";
			echo '<meta property="og:image" content="' . esc_attr( get_the_post_thumbnail() ) . '"/>' . "\n";
			echo '<meta property="og:updated_time" content="' . esc_attr( get_the_modified_time( 'U' ) ) . '"/>' . "\n";

		elseif( is_category() ):

			global $cat;

			echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'title' ) ) . '"/>' . "\n";
			echo '<meta property="og:url" content="' . esc_attr( get_category_link( $cat ) ) . '" />' . "\n";
			echo '<meta property="og:title" content="' . esc_attr( get_cat_name( $cat ) ) . '" />' . "\n";
			echo '<meta property="og:description" content="' . fvw_prepdesc( category_description( $cat ) ) . '"/>' . "\n";

		endif;

		echo "\n\n";

	}




?>