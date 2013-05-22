<?php
	/*
	Plugin Name: B09 Link to Existing Content
	Description: Seamless integration of the "Link to existing Content"-Functionality in Wordpress with the plugin "Search Everything". Also automatically adds a shortcode for internal links, with id, linktext and target. (deactivatable)
	Version: 1.4.0
	Author: BASICS09
	Author URI: http://www.basics09.de
	
	///////////////
	
	Control the plugins behaviour by copying the following snippets in the functions.php of your theme:
	
	// Control which post types should be searched 
	
	add_filter("link_to_existing_content_post_types", "my_link_to_existing_content_post_types");
	function my_link_to_existing_content_post_types($post_types) {
		$post_types = array("post");
		return $post_types;
	}
	
	Control which taxonomies should be searched
	
	add_filter("link_to_existing_content_taxonomies", "my_link_to_existing_content_taxonomies");
	function my_link_to_existing_content_taxonomies($taxonomies){
		$taxonomies = array("category", "genre");
		return $taxonomies;
	}
	
	// Enable shortcode functionality completely:
	
	add_filter("link_to_existing_content_use_shortcode", "my_link_to_existing_content_use_shortcode");
	function my_link_to_existing_content_use_shortcode(){
		return true;
	}
	
	// Overwrite the default shortcode handling:
	
	remove_shortcode("link");
	add_shortcode("link", "render_internal_link");
	function render_internal_link($atts){
		return "...";
	}
	
	*	
	*	
	*/
	

	/*
	*  Class instantiation
	*
	*/
	$b09_link_to_existing_content = new B09_Link_To_Existing_Content();
	
	/*
	* 	Class B09_Link_To_Existing_Content
	*
	* 	@param: none
	*	@returns: nothing
	*
	*/
	
	class B09_Link_To_Existing_Content {
		
		var $path;
		var $dir;
		var $nonce;
		var $shortcode_name = "link";
		var $use_shortcode = false;
		
		function B09_Link_To_Existing_Content(){
		
			$this->path = dirname(__FILE__).'';
			$this->dir = plugins_url('',__FILE__);
			
			// Hack: Overwrite the $_SERVER["SCRIPT_NAME"], so that Search Everything can add it's filters
			
			if (basename($_SERVER["SCRIPT_NAME"]) == "admin-ajax.php") {	
				$_SERVER["SCRIPT_NAME"] = $this->path . "/b09.link-to-existing-content.php";
			}
			
			add_action("init", array($this, "init"));
			add_action('wp_ajax_b09-link-ajax', array($this, "ajax_link_action") );
			
			
			// Add the plugin scripts
			global $pagenow;
			if(is_admin() && in_array($pagenow, array("post.php", "post-new.php", "media.php"))){
				add_action("admin_enqueue_scripts", array($this, "plugin_scripts") );
			}
		}
		
		function init(){
			// detect if the shortcode functionality should be used or not.
			$this->use_shortcode = apply_filters("link_to_existing_content_use_shortcode", $this->use_shortcode);

			// Add the shortcode hook
			add_shortcode($this->shortcode_name, array($this, "render_internal_link_shortcode"));
		}
		
		/*
		* 	Function render_internal_link
		*
		*	@description: render the shortcodes for display in frontend
		* 	@param: $atts => array(id, text, target)
		*	@returns: html string
		*
		*/
		
		function render_internal_link_shortcode($atts){
			$id = isset($atts["id"]) ? intval($atts["id"]) : false;
			$text = isset($atts["text"]) ? $atts["text"] : false;
			$taxonomy = isset($atts["tax"]) ? $atts["tax"] : false;
			$title = isset($atts["title"]) ? $atts["title"] : false;
			$target = isset($atts["target"]) ? " target='{$atts['target']}'" : false;
			
			
			// if there is no ID, return only the text
			if( !$id ) 
				return $text;
			
			// if it is a taxonomy link
			if($taxonomy) {
				$link = get_term_link($id, $taxonomy);
				
				// if there is no link (for example, term doesn't exist anymore)
				if( is_wp_error($link) ) 
					return $text;
				
				// If there is no title
				if(!$title) {
					$term = get_term($id, $taxonomy);
					$title = $term->name;
					$title = sprintf(__("Archive for %s"), $title);
				}
				
			} else {
				// if it is a post link
				$link = get_permalink($id);
				
				// if there is no link (for example, post doesn't exist anymore)
				if(!$link)
					return $text;
				
				// if there is no title
				if(!$title)
					$title = get_post($id)->post_title;
					
				// if there is no text, default to the post title
				if(!$text)
					$text = $title;
			}
			
			$out = "<a class='internal-link' title='{$title}' href='{$link}' {$target}>{$text}</a>";
			return $out;
		}
		
		/*
		* 	Function plugin_scripts
		*
		*	@description: overwrite and load the necessary scripts
		* 	@param: none
		*	@returns: nothing
		*
		*/
		
		function plugin_scripts(){
		
			// deregister the default wordpress script
			wp_deregister_script("wplink");
			
			// replace it with the plugins script version
			wp_enqueue_script("b09-wplink-script", $this->dir . "/b09.wplink.js",array('jquery'), false, true);
			wp_enqueue_style("b09-wplink-style", $this->dir . "/css/b09.wplink.css");
			

			// localize the script
			$ltec_localized = array(
				'wpLinkL10n' => array(
					'title' => __('Insert/edit link'),
					'update' => __('Update'),
					'save' => __('Add Link'),
					'noTitle' => __('(no title)'),
					'noMatchesFound' => __('No matches found.'),
					'saveShortcode' => __('Add Shortcode'),
					'shortcodeLabel' => __('Shortcode'),
					'titlePlaceholder' => __('Automatic, type here to customize...'),
					'searchIn' => __('Search in')
				),
				'ajaxAction' => 'b09-link-ajax',
				'useShortcode' => $this->use_shortcode ? 1 : 0,
				'shortcodeName' => $this->shortcode_name,
				
			);
						
			wp_localize_script("b09-wplink-script", "linkToExistingContent", $ltec_localized);

		}
		
		/*
		* 	Function ajax_link_action
		*
		*	@description: The Ajax function for searching posts
		* 	@param: none
		*	@returns: dies with response
		*
		*/
		
		function ajax_link_action(){
			
			// Parse the given arguments
			$args["pagenum"] = $_POST["page"];
			$args["s"] = $_POST["search"];
			
			
			$args["objectType"] = isset($_POST["objectType"]) ? $_POST["objectType"] : "posts";
			
			if($args["objectType"] == "taxonomies"){
				// Get all registered public taxonomies
				$taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );
				$tax_names = array_keys( $taxonomies );
	
				
				// Filter the taxonomies before searching their terms
				$tax_names = apply_filters("link_to_existing_content_taxonomies", $tax_names);
				
				if(!is_array($tax_names) || !count($tax_names))
					die("false");
				
				
				$query = array(
					//"hide_empty" => false,
					'number' => 20
				);
				
				// Add the search string to the query if any was given
				if ( isset( $args['s'] ) )
					$query['search'] = $args['s'];
				
				// Normalize the page number
				$args['pagenum'] = isset( $args['pagenum'] ) ? absint( $args['pagenum'] ) : 1;
				
				// Calculate the offset
				$query['offset'] = $args['pagenum'] > 1 ? $query['number'] * ( $args['pagenum'] - 1 ) : 0;
				
				
				$terms = get_terms ($tax_names, $query);
				
				if ( !count($terms) )
					die("false");
				
				$results = array();
				
				foreach($terms as $term){
					
					$info = $taxonomies[$term->taxonomy]->labels->singular_name;
					
					$results[] = array(
						'ID' => $term->term_id,
						'title' => trim( esc_html( $term->name ) ),
						'permalink' => get_term_link( $term ),
						'info' => $info,
						'taxName' => $taxonomies[$term->taxonomy]->name
					);
					
				}
				die(json_encode($results));
				// End Taxonomies
			} else {
			
				// Get all registered public post types
				$pts = get_post_types( array( 'public' => true ), 'objects' );
				$pt_names = array_keys( $pts );
				
				// Filter the post types, that should be included in the search.
				$pt_names = apply_filters("link_to_existing_content_post_types", $pt_names);
				
				if(!is_array($pt_names) || !count($pt_names))
					die("false");
				
				
				// Build the default query parameters, with suppress_filters set to false for advanced searching capabilities
				$query = array(
					'post_type' => $pt_names,
					'suppress_filters' => false,
					'update_post_term_cache' => false,
					'update_post_meta_cache' => false,
					'post_status' => 'publish',
					'order' => 'DESC',
					'orderby' => 'post_date',
					'posts_per_page' => 20
				);
				
				// Add the search string to the query if any was given
				if ( isset( $args['s'] ) )
					$query['s'] = $args['s'];
				
				// Normalize the page number
				$args['pagenum'] = isset( $args['pagenum'] ) ? absint( $args['pagenum'] ) : 1;
				
				// Calculate the offset
				$query['offset'] = $args['pagenum'] > 1 ? $query['posts_per_page'] * ( $args['pagenum'] - 1 ) : 0;
				
				// Do main query.
				$get_posts = new WP_Query;
				$posts = $get_posts->query( $query );
				
				// Check if any posts were found.
				if ( ! $get_posts->post_count )
					die("false");
					
				
				// Build results.
				$results = array();
				foreach ( $posts as $post ) {
					if ( 'post' == $post->post_type )
						$info = mysql2date( __( 'Y/m/d' ), $post->post_date );
					else
						$info = $pts[ $post->post_type ]->labels->singular_name;
			
					$results[] = array(
						'ID' => $post->ID,
						'title' => trim( esc_html( strip_tags( get_the_title( $post ) ) ) ),
						'permalink' => get_permalink( $post->ID ),
						'info' => $info,
					);
				}
			
				die (json_encode($results));
			}
			
			die("false");
		}
		
	}
?>