<?php

class WPUEP_Template_Exercise_Tags extends WPUEP_Template_Block {

    public $editorField = 'exerciseTags';
    public $is_list;
    public $list_style;

    public function __construct( $type = 'exercise-tags' )
    {
        parent::__construct( $type );
    }

    public function is_list( $is_list )
    {
        $this->is_list = $is_list;
        return $this;
    }

    public function list_style( $list_style )
    {
        $this->list_style = $list_style;
        return $this;
    }

    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) ) return '';

        // Backwards compatibility
        if( empty( $this->children ) ) {
            $output = $this->default_output( $exercise );
        } else {

            if( $this->is_list ) {
                if( in_array( $this->list_style, array( 'none', 'circle', 'disc', 'square' ) ) ) {
                    $tag = 'ul';
                } else {
                    $tag = 'ol';
                }

                $sub_tag = 'li';

                $this->add_style( 'list-style', $this->list_style, 'li' );
            } else {
                $tag = 'div';
                $sub_tag = 'div';
            }

            $args['desktop'] = $args['desktop'] && $this->show_on_desktop;
            $output = $this->before_output();

            ob_start();
?>
<<?php echo $tag . $this->style(); ?>>
    <?php
    foreach( $this->tags_list( $exercise ) as $tag_name => $tag_terms ) {
        $slug = strtolower( str_replace( ' ', '-', $tag_name ) );
        ?>
        <<?php echo $sub_tag . $this->style( 'li' ); ?> class="wpuep-exercise-tags-<?php echo $slug; ?>">
            <?php
                $child_args = array_merge( $args, array(
                    'tag_name' => $tag_name,
                    'tag_terms' => $tag_terms,
                ) );

                $this->output_children( $exercise, 0, 0, $child_args );
            ?>
        </<?php echo $sub_tag; ?>>
    <?php
    }
    ?>
</<?php echo $tag; ?>>
<?php
            $output .= ob_get_contents();
            ob_end_clean();
        }

        return $this->after_output( $output, $exercise );
    }

    /**
     * TODO Refactor this.
     */
    private function tags_list( $exercise )
    {
        $tags = array();

        $taxonomies = WPUltimateExercise::get()->tags();
        unset( $taxonomies['ingredient'] );

        foreach( $taxonomies as $taxonomy => $options ) {
            if( !in_array( $taxonomy, WPUltimateExercise::option('exercise_tags_hide_in_exercise', array() ) ) ) {
                $terms = get_the_term_list( $exercise->ID(), $taxonomy, '', ', ' );
                if( !is_wp_error( $terms ) && $terms != '' )
                {
                    $tags[$options['labels']['singular_name']] = $terms;
                }
            }
        }

        // Categories as tags
        if( WPUltimateExercise::is_addon_active('custom-taxonomies') && WPUltimateExercise::option('exercise_tags_show_in_exercise', '0') == '1' )
        {
            $categories = wp_get_post_categories( $exercise->ID() );
            $category_groups = array();

            foreach( $categories as $category ){
                $cat = get_category( $category );

                if( !is_null( $cat->parent ) && $cat->parent != 0 )
                {
                    $category_groups[$cat->parent][] = $cat;
                }
            }

            foreach( $category_groups as $group => $categories )
            {
                $group_category = get_category( $group );
                $group_name = $group_category->name;

                $cats = array();
                foreach( $categories as $cat )
                {
                    $link = get_category_link( $cat->cat_ID );
                    $cats[] = '<a href="'.$link.'">'.$cat->name.'</a>';
                }

                $tags[$group_name] = implode( ', ', $cats );
            }
        }

        return apply_filters( 'wpuep_output_exercise_block_exercise-tags_terms', $tags, $exercise );
    }

    public function default_output( $exercise )
    {
        $this->add_style( 'list-style', 'none' );
        $this->add_style( 'list-style', 'none', 'li' );
        $this->add_style( 'line-height', '1.5em', 'li' );
        $this->add_style( 'display', 'inline-block', 'name' );
        $this->add_style( 'width', '100px', 'name' );
        $this->add_style( 'font-weight', 'bold', 'name' );

        $output = $this->before_output();

        ob_start();
?>
<ul<?php echo $this->style(); ?>>
    <?php
    foreach( $this->tags_list( $exercise) as $tag => $terms ) {
        $slug = strtolower( str_replace( ' ', '-', $tag ) );
        ?>
        <li class="wpuep-exercise-tags-<?php echo $slug; ?>"<?php echo $this->style('li'); ?>>
            <span class="exercise-tag-name"<?php echo $this->style('name'); ?>><?php echo $tag; ?></span>
            <span class="exercise-tags"<?php echo $this->style('terms'); ?>>
                <?php echo $terms; ?>
            </span>
        </li>
    <?php
    }
    ?>
</ul>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $output;
    }
}