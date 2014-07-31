<?php

/* --------------------------------------------
    Walker classes
-------------------------------------------- */

// Using the Walker class to add the dropdown class to sub-menus per Foundation requirements

class HQ_walker extends Walker_Nav_Menu {
	// setting the childred to true or false.. if there are child elements then we are going to
	// call the class below and make sure to add class of has-dropdown
	function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ){
		$GLOBALS['dd_children'] = ( isset($children_elements[$element->ID]) )? 1:0;
		$GLOBALS['dd_depth'] = (int) $depth;
		parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}
	// add the class of dropdown to sub-level ul
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= $indent ."<ul class=\"dropdown\">";
	}

}


// walker class to turn nav menus into grid elements

/*
*    to display this on the front end you need to add this
*    $largetiles variable sets how many tiles you want
*    $smalltiles sets how many tiles for the small screen
*/
// $largetiles = '4';
// $smalltiles = $largetiles/2;
// wp_nav_menu( array(
//     'theme_location' => 'grid',
//     'menu_class' => 'large-block-grid-'. $largetiles . ' small-block-grid-'. $smalltiles,
//     'walker' => new tile_walker()
//  ) );

// Add class to the tile navigation for blocks
class tile_walker extends Walker_Nav_Menu {

	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		// These varibles may not need to be changeds after all
		$largeGrid = 'largegrid';
		$smallGrid = 'smallgrid';

		// add 'tight' to variable to add the smaller margin class
		$tight = '';


		$attributes  = '';
		/*
					// the following code is just like writing it this way
					if(!empty($item-<attr_title)){
						$attribues .= 'title="' . esc_attr($item->attr_title) . '"';
					}
					// or even this
					if(!empty($item->attr_title))
						$attributes .= 'title="' . esc_attr($item->attr_title) .'"';
		*/
		!empty( $item->attr_title ) and $attributes .= ' title="'  . esc_attr( $item->attr_title ) .'"';
		!empty( $item->target ) and $attributes .= ' target="' . esc_attr( $item->target ) .'"';
		!empty( $item->xfn ) and $attributes .= ' rel="'    . esc_attr( $item->xfn ) .'"';
		!empty( $item->url ) and $attributes .= ' href="'   . esc_attr( $item->url ) .'"';
		$classes = empty($item->classes) ? array () : (array) $item->classes;
		$class_names = join(' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
		!empty ( $class_names ) and $class_names = ' class="'. $largeGrid . '  ' . $smallGrid . ' ' . $tight .'"';

		$output .= '<li id="ms-menu-item-'. $item->ID .'" class="'. $class_names .'" data-height-watch>';

		$output .= '<a' . $attributes . ' class="tile-menu-item">';
		$output .= '<div class="panel table" data-height-watch>';

		$title = apply_filters( 'the_title', $item->title, $item->ID );
		$item_output = $args->before
			. "<h2>"
			. $args->link_before
			. $title
			. '</h2></div></a></li>'
			. $args->link_after
			. $args->after;
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}
//Add class to parent pages to show they have subpages (only for Automattic wp_nav_menu)

function add_parent_css($classes, $item){
	global $dd_depth, $dd_children;

	$classes[] = 'depth'.$dd_depth;

	if($dd_children) {
		$classes[] = 'has-dropdown';
	}

	return $classes;
}
add_filter( 'nav_menu_css_class', 'add_parent_css', 10, 2 );

function add_parent_class( $css_class, $page, $depth, $args ) {
	if ( ! empty( $args['has_children'] ) ) {
		$css_class[] = 'has-dropdown';
	}

	return $css_class;
}
add_filter( 'page_css_class', 'add_parent_class', 10, 4 );

// allows you to call my_excerpt() with a choice of predefined lengths
// ie my_excerpt('long');
class HQ_Excerpt {

	// Default length (by WordPress)
	public static $length = 55;

	// So you can call: my_excerpt('short');
	public static $types = array(
		'short' => 25,
		'med' => 40,
		'regular' => 55,
		'long' => 100
	);

	/**
	 * Sets the length for the excerpt,
	 * then it adds the WP filter
	 * And automatically calls the_excerpt();
	 *
	 * @param string $new_length
	 * @return void
	 * @author Baylor Rae'
	 */
	public static function length($new_length = 55) {
		HQ_Excerpt::$length = $new_length;

		add_filter('excerpt_length', 'HQ_Excerpt::new_length');

		HQ_Excerpt::output();
	}

	// Tells WP the new length
	public static function new_length() {
		if( isset(HQ_Excerpt::$types[HQ_Excerpt::$length]) )
			return HQ_Excerpt::$types[HQ_Excerpt::$length];
		else
			return HQ_Excerpt::$length;
	}

	// Echoes out the excerpt
	public static function output() {
		the_excerpt();
	}

}

// An alias to the class
function hq_excerpt($length = 55) {
	HQ_Excerpt::length($length);
}

function hq_wp_trim_excerpt($text) {
	$raw_excerpt = $text;
	if ( '' == $text ) {
		//Retrieve the post content.
		$text = get_the_content('');

		//Delete all shortcode tags from the content.
		$text = strip_shortcodes( $text );

		$text = apply_filters('the_content', $text);
		$text = str_replace(']]>', ']]&gt;', $text);

		$allowed_tags = '<p>'; /*** MODIFY THIS. Add the allowed HTML tags separated by a comma.***/
		$text = strip_tags($text, $allowed_tags);

		$excerpt_word_count = 55; /*** MODIFY THIS. change the excerpt word count to any integer you like.***/
		$excerpt_length = apply_filters('excerpt_length', $excerpt_word_count);

		$excerpt_end = '[...]'; /*** MODIFY THIS. change the excerpt endind to something else.***/
		$excerpt_more = apply_filters('excerpt_more', ' ' . $excerpt_end);

		$words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
		if ( count($words) > $excerpt_length ) {
			array_pop($words);
			$text = implode(' ', $words);
			$text = $text . $excerpt_more;
		} else {
			$text = implode(' ', $words);
		}
	}
	return apply_filters('wp_trim_excerpt', $text, $raw_excerpt);
}
remove_filter( 'get_the_excerpt', 'wp_trim_excerpt' );
add_filter( 'get_the_excerpt', 'hq_wp_trim_excerpt' );
