<?php
class WPUPG_Egrid {

    private $post;
    private $meta;
    private $fields = array(
        'wpupg_ecentered',
        'wpupg_eimages_only',
        'wpupg_efilter_match_parents',
        'wpupg_efilter_multiselect',
        'wpupg_efilter_multiselect_type',
        'wpupg_efilter_type',
        'wpupg_epagination_type',
        'wpupg_epost_types',
        'wpupg_elayout_mode',
        'wpupg_elimit_posts',
        'wpupg_elink_target',
        'wpupg_elink_type',
        'wpupg_eorder_by',
        'wpupg_eorder',
        'wpupg_etemplate',
    );

    // Pagination fields with defaults
    private $pagination_fields = array(
        'pages' => array(
            'posts_per_page'    => 20,
        ),
        'load_more' => array(
            'initial_posts'     => 20,
            'posts_on_click'    => 20,
            'button_text'       => 'Load More',
        ),
    );

    private $pagination_style_fields = array(
        'background_color'          => '#2E5077',
        'background_active_color'   => '#1C3148',
        'background_hover_color'    => '#1C3148',
        'text_color'                => '#FFFFFF',
        'text_active_color'         => '#FFFFFF',
        'text_hover_color'          => '#FFFFFF',
        'border_color'              => '#1C3148',
        'border_active_color'       => '#1C3148',
        'border_hover_color'        => '#1C3148',
        'border_width'              => '1',
        'margin_vertical'           => '5',
        'margin_horizontal'         => '5',
        'padding_vertical'          => '5',
        'padding_horizontal'        => '10',
        'alignment'                 => 'left',
    );

    // Filter style fields with defaults
    private $filter_style_fields = array(
        'isotope' => array(
            'background_color'          => '#2E5077',
            'background_active_color'   => '#1C3148',
            'background_hover_color'    => '#1C3148',
            'text_color'                => '#FFFFFF',
            'text_active_color'         => '#FFFFFF',
            'text_hover_color'          => '#FFFFFF',
            'border_color'              => '#1C3148',
            'border_active_color'       => '#1C3148',
            'border_hover_color'        => '#1C3148',
            'border_width'              => '1',
            'margin_vertical'           => '5',
            'margin_horizontal'         => '5',
            'padding_vertical'          => '5',
            'padding_horizontal'        => '10',
            'alignment'                 => 'left',
            'all_button_text'           => 'Check Constructor',
        ),
    );

    public function __construct( $post )
    {
        // Get associated post
        if( is_object( $post ) && $post instanceof WP_Post ) {
            $this->post = $post;
        } else if( is_numeric( $post ) ) {
            $this->post = get_post( $post );
        } else {
            throw new InvalidArgumentException( 'Egrids can only be instantiated with a Post object or Post ID.' );
        }

        // Get metadata
        $this->meta = get_post_custom( $this->post->ID );
		
        // Defaults with expressions
        $this->filter_style_fields['isotope']['all_button_text'] = __( 'All', 'wp-eultimate-post-grid' );
    }

    public function is_present( $field )
    {
        switch( $field ) {
            default:
                $val = $this->meta( $field );
                return isset( $val ) && trim( $val ) != '';
        }
    }

    public function meta( $field )
    {		
        if( isset( $this->meta[$field] ) ) {			
            return $this->meta[$field][0];
        }

        return null;
    }

    public function fields()
    {
        return $this->fields;
    }

    public function filter_style_fields()
    {
        return $this->filter_style_fields;
    }

    public function pagination_fields()
    {
        return $this->pagination_fields;
    }

    public function pagination_style_fields()
    {
        return $this->pagination_style_fields;
    }

    /**
     * Egrid fields
     */

    public function centered()
    {
        return $this->meta( 'wpupg_ecentered' );
    }

    public function filter()
    {
        return $this->meta( 'wpupg_efilter' );
    }

    public function filter_taxonomies()
    {
        $filter_taxonomies = $this->meta( 'wpupg_efilter_taxonomies' );
        $filter_taxonomies = is_null( $filter_taxonomies ) ? null : unserialize( $filter_taxonomies );
        return is_array( $filter_taxonomies ) ? $filter_taxonomies : array();
    }

    public function filter_style()
    {
        $filter_style = $this->meta( 'wpupg_efilter_style' );
        $filter_style = is_null( $filter_style ) ? null : unserialize( $filter_style );
        $filter_style = is_array( $filter_style ) ? $filter_style : array();

        // Set defaults
        foreach( $this->filter_style_fields() as $type => $defaults ) {
            $filter_style[$type] = isset( $filter_style[$type] ) ? $filter_style[$type] + $defaults : $defaults;
        }

        return $filter_style;
    }

    public function filter_match_parents()
    {
        return $this->meta( 'wpupg_efilter_match_parents' );
    }

    public function filter_multiselect()
    {
        if( WPUltimateEPostGrid::is_premium_active() ) {
            return $this->meta( 'wpupg_efilter_multiselect' );
        } else {
            return false;
        }
    }

    public function filter_multiselect_type()
    {
        return $this->meta( 'wpupg_efilter_multiselect_type' );
    }

    public function filter_type()
    {
		//	echo '<pre>';	 print_r( $this->meta ); exit; 
        return $this->meta( 'wpupg_efilter_type' );
    }

    public function ID()
    {
        return $this->post->ID;
    }

    public function images_only()
    {
        return $this->meta( 'wpupg_images_only' );
    }

    public function layout_mode()
    {
        $layout_mode = $this->meta( 'wpupg_layout_mode' );
        return $layout_mode ? $layout_mode : 'masonry';
    }

    public function limit_posts()
    {
        return $this->meta( 'wpupg_limit_posts' );
    }

    public function limit_rules()
    {
        $limit_rules = $this->meta( 'wpupg_limit_rules' );
        $limit_rules = is_null( $limit_rules ) ? null : unserialize( $limit_rules );
        return is_array( $limit_rules ) ? $limit_rules : array();
    }

    public function link_target()
    {
        $link_target = $this->meta( 'wpupg_link_target' );
        return $link_target ? $link_target : 'post';
    }

    public function link_type()
    {
        $link_type = $this->meta( 'wpupg_link_type' );
        return $link_type ? $link_type : '_self';
    }

    public function order()
    {
        return $this->meta( 'wpupg_order' );
    }

    public function order_by()
    {
        return $this->meta( 'wpupg_order_by' );
    }

    public function pagination()
    {
        $pagination = $this->meta( 'wpupg_epagination' );
        $pagination = is_null( $pagination ) ? null : unserialize( $pagination );
        $pagination = is_array( $pagination ) ? $pagination : array();

        // Set defaults
        foreach( $this->pagination_fields() as $type => $defaults ) {
            $pagination[$type] = isset( $pagination[$type] ) ? $pagination[$type] + $defaults : $defaults;
        }

        return $pagination;
    }

    public function pagination_style()
    {
        $pagination_style = $this->meta( 'wpupg_epagination_style' );
        $pagination_style = is_null( $pagination_style ) ? null : unserialize( $pagination_style );
        $pagination_style = is_array( $pagination_style ) ? $pagination_style : array();

        // Set defaults
        $pagination_style = $pagination_style + $this->pagination_style_fields();

        return $pagination_style;
    }

    public function pagination_type()
    {
        return $this->meta( 'wpupg_epagination_type' );
    }

    public function posts()
    {					
        $posts = $this->meta( 'wpupg_eposts' );						
        $posts = is_null( $posts ) ? null : unserialize( $posts );
        return is_array( $posts ) ? $posts : array();
    }

    public function post()
    {
        return $this->post;
    }

    public function post_status() // TODO
    {
        return 'publish';
    }

    public function post_types()
    {	
        $post_types = $this->meta( 'wpupg_epost_types' );
        $post_types = is_null( $post_types ) ? null : unserialize( $post_types );		
        return is_array( $post_types ) ? $post_types : array();
    }

    public function slug()
    {
        return $this->post->post_name;
    }

    public function template()
    {
        return WPUltimateEPostGrid::addon( 'custom-templates' )->get_template( $this->template_id() );
    }
	
    public function template_id()
    {
        return $this->meta( 'wpupg_etemplate' );
    }

    public function title()
    {
        return $this->post->post_title;
    }

    /**
     * Helper functions
     */

    public function set_dynamic_rules( $dynamic_rules )
    {
        if( WPUltimateEPostGrid::is_premium_active() ) {			
            $this->meta['wpupg_limit_posts'][0] = 'on';

            $limit_rules = $this->meta( 'wpupg_limit_rules' );
            $limit_rules = is_null( $limit_rules ) ? null : unserialize( $limit_rules );
            $limit_rules = is_array( $limit_rules ) ? $limit_rules : array();

            $new_rules = array_merge( $limit_rules, $dynamic_rules );
            $this->meta['wpupg_limit_rules'][0] = serialize( $new_rules );

            $generated = WPUltimateEPostGrid::get()->helper( 'grid_cache' )->dynamic_generate( $this );

            $this->meta['wpupg_eposts'][0] = serialize( $generated['cache'] );
            $this->meta['wpupg_efilter'][0] = $generated['filter'];
        }
    }

    public function get_posts( $page = 0 )
    {
        $egrid_posts = $this->posts();		
        $post_ids = $egrid_posts['all'];

        if( count( $post_ids ) == 0 ) return array();

        $posts_per_page = -1;

        if( $this->pagination_type() == 'pages' ) {
            $pagination = $this->pagination();
            $posts_per_page = $pagination['pages']['posts_per_page'];
        }

        $offset = 0;
        if( $page > 0 ) {
            $offset = $page * $posts_per_page;
        }

        $args = array(
            'post_type' => 'any',
            'orderby' => $this->order_by(),
            'order' => $this->order(),
            'posts_per_page' => $posts_per_page,
            'offset' => $offset,
            'post__in' => $post_ids,
            'ignore_sticky_posts' => true,
        );

        $args = apply_filters( 'wpupg_get_posts_args', $args, $page, $this );

        if( $args['posts_per_page'] == -1 ) {
            $args['nopaging'] = true;
        }

        $query = new WP_Query( $args );
        $posts = $query->have_posts() ? $query->posts : array();

        return $posts;
    }

    public function draw_posts( $page = 0 )
    {
        $output 		=	'';
        $egrid_posts	=	$this->posts();
        $posts			=	$this->get_posts( $page );		
		
        foreach( $posts as $post ) {
            $post_id = $post->ID;

            $classes = array(
                'wpupg-item',
                'wpupg-page-' . $page,
                'wpupg-post-' . $post_id,
                'wpupg-type-' . $post->post_type,
            );

            if( isset( $egrid_posts['terms'][$post_id] ) ) {
                foreach( $egrid_posts['terms'][$post_id] as $taxonomy => $terms ) {
                    foreach( $terms as $term ) {
                        $classes[] = 'wpupg-tax-' . $taxonomy . '-' . $term;
                    }
                }
            }
			
            $classes	=	apply_filters( 'wpupg_eoutput_grid_classes', $classes, $this );			
            $template	=	apply_filters( 'wpupg_eoutput_grid_template', $this->template() , $this );									
            $post		=	 apply_filters( 'wpupg_eoutput_grid_post', $post, $this );								
			
            $output .= apply_filters( 'wpupg_eoutput_grid_html', $template->output_string( $post, $classes ), $template, $post, $classes );
        }

        return $output;
    }

    public function draw_pagination()
    {
        $output = '';

        $egrid_posts = $this->posts();
        $nbr_posts = count( $egrid_posts['all'] );

        $pagination = $this->pagination();
        $pagination_type = $this->pagination_type();

        $pagination = $pagination[$pagination_type];

        if( $pagination_type == 'pages' ) {
            $nbr_pages = ceil( $nbr_posts / floatval( $pagination['posts_per_page'] ) );

            for( $page = 0; $page < $nbr_pages; $page++ ) {
                $active = $page == 0 ? ' active' : '';
                $output .= '<div class="wpupg-pagination-term wpupg-page-' . $page . $active . '" data-page="' . $page . '">' . ($page+1) . '</div>';
            }
        }
        return $output;
    }
}