<?php
	/*
		Plugin Name: My Menus
		Description: Allows you to create restaurant menu items with categories and tags, which can then be queried in your template files.
		
		Author: David Posey
		Author URI: http://www.davidgposey.com
		
		Version: 0.1
	*/
	
	
	
	
	/*** Add to settings page ***/
	add_action("admin_init", "menu_add_settings");
	function menu_add_settings(){
		add_settings_field("menu_settings_field_currency", "Currency Symbol", "menu_settings_field_currency_callback", "general", "default");
		register_setting("general", "menu_settings_field_currency");
	}
	
	function menu_settings_field_currency_callback(){
		?>
			<input type="text" class="regular-text" name="menu_settings_field_currency" id="menu_settings_field_currency" value="<?php echo get_option("menu_settings_field_currency"); ?>" />
		<?php
	}
	
	
	
	/*** Custom Taxonomy: Menu Categories ***/
	add_action('init', 'register_taxonomy_menu_categories');
	function register_taxonomy_menu_categories() {
		$labels = array(
			'name' => _x( 'Menu Categories', 'menu_categories' ),
			'singular_name' => _x( 'Menu Category', 'menu_categories' ),
			'search_items' => _x( 'Search Menu Categories', 'menu_categories' ),
			'popular_items' => _x( 'Popular Menu Categories', 'menu_categories' ),
			'all_items' => _x( 'All Menu Categories', 'menu_categories' ),
			'parent_item' => _x( 'Parent Menu Category', 'menu_categories' ),
			'parent_item_colon' => _x( 'Parent Menu Category:', 'menu_categories' ),
			'edit_item' => _x( 'Edit Menu Category', 'menu_categories' ),
			'update_item' => _x( 'Update Menu Category', 'menu_categories' ),
			'add_new_item' => _x( 'Add New Menu Category', 'menu_categories' ),
			'new_item_name' => _x( 'New Menu Category', 'menu_categories' ),
			'separate_items_with_commas' => _x( 'Separate menu categories with commas', 'menu_categories' ),
			'add_or_remove_items' => _x( 'Add or remove menu categories', 'menu_categories' ),
			'choose_from_most_used' => _x( 'Choose from the most used menu categories', 'menu_categories' ),
			'menu_name' => _x( 'Menu Categories', 'menu_categories' ),
		);
		$args = array(
			'labels' => $labels,
			'public' => true,
			'show_in_nav_menus' => true,
			'show_ui' => true,
			'show_tagcloud' => false,
			'hierarchical' => true,
			'rewrite' => false,
			'query_var' => true
		);
		register_taxonomy('menu_categories', array('menu_item'), $args);
	}
	
	/*** Custom Taxonomy: Menu Attributes ***/
	add_action('init', 'register_taxonomy_menu_attributes');
	function register_taxonomy_menu_attributes(){
		$labels = array(
			'name' => _x( 'Menu Attributes', 'menu_attributes' ),
			'singular_name' => _x( 'Menu Attribute', 'menu_attributes' ),
			'search_items' => _x( 'Search Menu Attributes', 'menu_attributes' ),
			'popular_items' => _x( 'Popular Menu Attributes', 'menu_attributes' ),
			'all_items' => _x( 'All Menu Attributes', 'menu_attributes' ),
			'parent_item' => _x( 'Parent Menu Attribute', 'menu_attributes' ),
			'parent_item_colon' => _x( 'Parent Menu Attribute:', 'menu_attributes' ),
			'edit_item' => _x( 'Edit Menu Attribute', 'menu_attributes' ),
			'update_item' => _x( 'Update Menu Attribute', 'menu_attributes' ),
			'add_new_item' => _x( 'Add New Menu Attribute', 'menu_attributes' ),
			'new_item_name' => _x( 'New Menu Attribute', 'menu_attributes' ),
			'separate_items_with_commas' => _x( 'Separate menu attributes with commas', 'menu_attributes' ),
			'add_or_remove_items' => _x( 'Add or remove Menu Attributes', 'menu_attributes' ),
			'choose_from_most_used' => _x( 'Choose from most used Menu Attributes', 'menu_attributes' ),
			'menu_name' => _x( 'Menu Attributes', 'menu_attributes' ),
		);
		$args = array(
			'labels' => $labels,
			'public' => true,
			'show_in_nav_menus' => true,
			'show_ui' => true,
			'show_tagcloud' => true,
			'hierarchical' => false,
			'rewrite' => true,
			'query_var' => true
		);
		register_taxonomy('menu_attributes', array('menu_item'), $args);
	} 
	
	
	
	/*** Custom Post Type ***/
	add_action('init', 'register_cpt_menu_item');
	function register_cpt_menu_item() {
		$labels = array(
			'name' => _x( 'Menu Items', 'menu_item' ),
			'singular_name' => _x( 'Menu Item', 'menu_item' ),
			'add_new' => _x( 'Add New', 'menu_item' ),
			'add_new_item' => _x( 'Add New Menu Item', 'menu_item' ),
			'edit_item' => _x( 'Edit Menu Item', 'menu_item' ),
			'new_item' => _x( 'New Menu Item', 'menu_item' ),
			'view_item' => _x( 'View Menu Item', 'menu_item' ),
			'search_items' => _x( 'Search Menu', 'menu_item' ),
			'not_found' => _x( 'No menu items found', 'menu_item' ),
			'not_found_in_trash' => _x( 'No menu items found in Trash', 'menu_item' ),
			'parent_item_colon' => _x( 'Parent Menu Item:', 'menu_item' ),
			'menu_name' => _x( 'Menu Items', 'menu_item' ),
		);
		$args = array(
			'labels' => $labels,
			'hierarchical' => true,
			'supports' => array('title', 'editor', 'page-attributes'),
			'taxonomies' => array('menu_categories', 'menu_attributes'),
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_icon' => get_bloginfo("wpurl") . '/wp-content/plugins/my-menus/images/icon.png',
			'show_in_nav_menus' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'has_archive' => true,
			'query_var' => true,
			'can_export' => true,
			'rewrite' => true,
			'capability_type' => 'post'
		);
		register_post_type('menu_item', $args);
	}
	
	/*** Custom Meta Fields ***/
	add_action("add_meta_boxes", "add_price_field");
	function add_price_field(){
		add_meta_box(
			"menu_item_price",
			"<label for='menu_item_price'>Price</label>",
			"price_field_html",
			"menu_item",
			"normal",
			"default"
		);
	}
	function price_field_html($post){
		?>
			<?php the_currency(); ?><input type="text" class="regular-text" name="menu_item_price" id="menu_item_price" value="<?php echo get_post_meta($post->ID, "menu_item_price", true); ?>" />
		<?php
	}
	
	add_action("save_post", "save_menu_item_fields", 10, 2);
	function save_menu_item_fields($post_id, $post){
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		
		update_post_meta($post_id, "menu_item_price", $_POST["menu_item_price"]);
	}
	
	/*** Template Tags ***/
	function get_the_price($id){
		return get_post_meta($id, "menu_item_price", true);
	}
	
	function the_price(){
		global $post;
		echo get_the_price($post->ID);
	}
	
	function get_the_currency(){
		return get_option("menu_settings_field_currency");
	}
	
	function the_currency(){
		echo get_the_currency();
	}
	
?>