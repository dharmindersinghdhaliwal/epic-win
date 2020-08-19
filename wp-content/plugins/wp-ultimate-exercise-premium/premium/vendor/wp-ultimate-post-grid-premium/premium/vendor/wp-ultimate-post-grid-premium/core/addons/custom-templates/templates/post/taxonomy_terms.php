<?php

class WPUPG_Etemplate_Post_Taxonomy_Terms extends WPUPG_Etemplate_Block  {

    public $taxonomy;
    public $separator;
    public $editorField = 'postTaxonomyTerms';

    public function __construct( $type = 'post-taxonomy-terms' )
    {
        parent::__construct( $type );
    }

    public function taxonomy( $taxonomy )
    {
        $this->taxonomy = $taxonomy;
        return $this;
    }

    public function separator( $separator )
    {
        $this->separator = $separator;
        return $this;
    }

    public function output( $post, $args = array() )
    {
        if( !$this->output_block( $post, $args ) ) return '';

        $taxonomy = explode( ';', $this->taxonomy );
        $taxonomy_terms = wp_get_post_terms( $post->ID, $taxonomy );

        $terms = array();
        foreach( $taxonomy_terms as $term ) {
            $terms[] = $term->name;
        }
        $terms = implode( $this->separator, $terms );

        $output = $this->before_output();

        $output .= '<span' . $this->style() . '>' . $terms . '</span>';

        return $this->after_output( $output, $post );
    }
}