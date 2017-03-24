<?php
/**
 * Class: BoldGrid_Framework_Pagination
 *
 * This is the primary class that provides the customized
 * pagination lists used throughout the framework.
 *
 * @since      1.4.1
 * @package    Boldgrid_Framework
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Class: BoldGrid_Framework_Pagination
 *
 * This is the primary class that provides the customized
 * pagination lists used throughout the framework.
 *
 * @since      1.4.1
 */
class BoldGrid_Framework_Pagination {

	/**
	 * Creates our custom pagination markup to use.
	 *
	 * @global $wp_query WordPress query global.
	 */
	public function create() {
		global $wp_query;
		$big = 999999999;
		$pages = paginate_links(
			array(
				'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format' => '?page=%#%',
				'current' => max( 1, get_query_var( 'paged' ) ),
				'total' => $wp_query->max_num_pages,
				'type' => 'array',
				'prev_next' => TRUE,
				'prev_text' => '&larr; Previous',
				'next_text' => 'Next &rarr;',
			)
		);
		$output = '';
		if ( is_array( $pages ) ) {
			$paged = ( get_query_var( 'paged' ) === 0 ) ? 1 : get_query_var( 'paged' );

			$output .=  '<ul class="pagination">';

			foreach ( $pages as $i => $page ) {
				if ( $paged === 1 && $i === 0 ) {
					$output .= '<li class="active">' . $page . '</li>';
				} else {
					if ( $paged !== 1 && $paged === $i ) {
						$output .= '<li class="active">' . $page . '</li>';
					} else {
						$output .= '<li>' . $page . '</li>';
					}
				}
			}

			$output .= '</ul>';

			// Create an instance of DOMDocument
			$dom = new \DOMDocument();

			// Handle UTF-8, otherwise problems will occur with UTF-8 characters.
			$dom->loadHTML( mb_convert_encoding( $output, 'HTML-ENTITIES', 'UTF-8' ) );

			// Create an instance of DOMXpath and all elements with the class 'page-numbers'
			$xpath = new \DOMXpath( $dom );
			$page_numbers = $xpath->query( "//*[contains(concat(' ', normalize-space(@class), ' '), ' page-numbers ')]" );

			// Iterate over the $page_numbers node.
			foreach ( $page_numbers as $page_numbers_item ) {
				// Add to class 'page-numbers' with bgtfw custom color classes for standard links.
				$page_numbers_item->attributes->item(0)->value = str_replace(
					'page-numbers',
					'page-numbers color1-background-color color1-border-color color-1-text-contrast color2-background-color-hover color-2-text-contrast-hover',
					$page_numbers_item->attributes->item(0)->value
				);
				// Add to class 'current' with bgtfw custom color classes for active link.
				$page_numbers_item->attributes->item(0)->value = str_replace(
					'current',
					'color2-background-color color1-border-color color-2-text-contrast',
					$page_numbers_item->attributes->item(0)->value
				);
			}

			// Save the updated HTML and output it.
			$output = $dom->saveHTML();
		}

		echo $output;
	}
}
