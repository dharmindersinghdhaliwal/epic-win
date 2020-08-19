<?php

class WPUEP_Template_Container extends WPUEP_Template_Block {

    public $editorField = 'container';

    public function __construct( $type = 'container' )
    {
        parent::__construct( $type );

        // This is always the starting point of the template
        $this->parent = -1;
        $this->row = 0;
        $this->column = 0;
        $this->order = 0;
    }

    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) ) return '';

        // Default arguments
        $args['desktop'] = true;
        $args['max_width'] = 9999;
        $args['max_height'] = 9999;

        $args['max_width'] = $this->max_width && $args['max_width'] > $this->max_width ? $this->max_width : $args['max_width'];
        $args['max_height'] = $this->max_height && $args['max_height'] > $this->max_height ? $this->max_height : $args['max_height'];

        if( isset( $args['classes'] ) ) {
            $this->classes = $args['classes'];
        }

        if( isset( $args['wp-eultimate-post-grid'] ) ) {
            $this->add_style( 'position', '' );
        }

        $image = get_post_thumbnail_id( $exercise->ID() );
        $image_url = $image ? wp_get_attachment_url( $image ) : '';

        $meta = $args['template_type'] == 'exercise' || $args['template_type'] == 'metadata' ? ' itemscope itemtype="http://schema.org/Exercise"' : '';

        $output = $this->before_output();

        ob_start();
?>
<div<?php echo $meta; ?> id="wpuep-container-exercise-<?php echo $exercise->ID(); ?>" data-permalink="<?php echo $exercise->link(); ?>" data-image="<?php echo $image_url; ?>" data-servings-original="<?php echo $exercise->servings_normalized(); ?>"<?php echo $this->style(); ?>>

<?php if( $args['template_type'] == 'exercise' || $args['template_type'] == 'metadata' ) { ?>
    <meta itemprop="url" content="<?php echo esc_attr( $exercise->link() ); ?>" />
    <meta itemprop="author" content="<?php echo esc_attr( $exercise->author() ); ?>">
    <meta itemprop="datePublished" content="<?php echo esc_attr( $exercise->date() ); ?>">
    <meta itemprop="exerciseYield" content="<?php echo esc_attr( $exercise->servings() ) . ' ' . esc_attr( $exercise->servings_type() ); ?>">

    <?php if( WPUltimateExercise::option( 'output_yandex_metadata', '0' ) == '1' ) { ?>
    <link itemprop="resultPhoto" href="<?php echo esc_attr( $exercise->image_url( 'full' ) ); ?>">
    <?php } ?>

    <?php
    // Ratings metadata
    $show_rating = false;
    $count = null;
    $rating = null;

    // Check user ratings
    if( WPUltimateExercise::is_addon_active( 'user-ratings' ) && WPUltimateExercise::option( 'user_ratings_enable', 'everyone' ) != 'disabled' ) {
        $rating_data = WPUEP_User_Ratings::get_exercise_rating( $exercise->ID() );

        $count = $rating_data['votes'];
        $rating = $rating_data['rating'];

        // Optional rounding
        $rounding = WPUltimateExercise::option( 'user_ratings_rounding', 'disabled' );

        if( $rounding == 'half' ) {
            $rating = ceil( $rating * 2 ) / 2;
        } else if ( $rounding == 'integer' ) {
            $rating = ceil( $rating );
        }

        // Do we have the minimum # of votes?
        $minimum_votes = intval( WPUltimateExercise::option( 'user_ratings_minimum_votes', '1' ) );
        $show_rating = $count >= $minimum_votes ? true : false;
    }

    // Use the author rating if we don't already have a rating to display
    if( !$show_rating ) {
        $count = 1;
        $rating = $exercise->rating_author();
        if( $rating != 0 ) $show_rating = true;
    }
	
	?>
	<div id="hyperlinking_reference?"></div>	
	<?php

    if( $show_rating ) { ?>
    <div class="wpuep-meta" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
        <meta itemprop="ratingValue" content="<?php echo $rating; ?>">
        <meta itemprop="reviewCount" content="<?php echo $count; ?>">
    </div>
    <?php } ?>

    <?php
    // Nutritional Metadata
    if( WPUltimateExercise::is_addon_active( 'nutritional-information' ) ) {
        $nutritional_meta = '<div class="wpuep-meta" itemprop="nutrition" itemscope itemtype="http://schema.org/NutritionInformation">';

        $nutritional = $exercise->nutritional();

        $mapping = array(
            'calories' => 'calories',
            'fat' => 'fatContent',
            'saturated_fat' => 'saturatedFatContent',
            'unsaturated_fat' => 'unsaturatedFatContent',
            'trans_fat' => 'transFatContent',
            'carbohydrate' => 'carbohydrateContent',
            'sugar' => 'sugarContent',
            'fiber' => 'fiberContent',
            'protein' => 'proteinContent',
            'cholesterol' => 'cholesterolContent',
            'sodium' => 'sodiumContent',
        );

        // Unsaturated Fat = mono + poly
        if( isset( $nutritional['monounsaturated_fat'] ) && $nutritional['monounsaturated_fat'] !== '' ) {
            $nutritional['unsaturated_fat'] = floatval( $nutritional['monounsaturated_fat'] );
        }

        if( isset( $nutritional['polyunsaturated_fat'] ) && $nutritional['polyunsaturated_fat'] !== '' ) {
            $mono = isset( $nutritional['unsaturated_fat'] ) ? $nutritional['unsaturated_fat'] : 0;
            $nutritional['unsaturated_fat'] = $mono + floatval( $nutritional['polyunsaturated_fat'] );
        }

        // Output metadata
        $nutritional_meta_set = false;
        foreach( $mapping as $field => $meta_field ) {
            if( isset( $nutritional[$field] ) && $nutritional[$field] !== '' ) {
                $nutritional_meta_set = true;
                $nutritional_meta .= '<meta itemprop="' . $meta_field . '" content="' . floatval( $nutritional[$field] ). '">';
            }
        }

        $nutritional_meta .= '</div>';

        if( $nutritional_meta_set ) {
            echo $nutritional_meta;
        }
    }
    ?>
<?php } ?>

<?php if( $args['template_type'] == 'metadata' ) { ?>

    <meta itemprop="image" content="<?php echo esc_attr( $exercise->image_url( 'full' ) ); ?>">
    <meta itemprop="name" content="<?php echo esc_attr( $exercise->title() ); ?>">
    <meta itemprop="description" content="<?php echo esc_attr( $exercise->description() ); ?>">
    <?php if( $exercise->prep_time_meta() ) { ?><meta itemprop="prepTime" content="<?php echo $exercise->prep_time_meta(); ?>"><?php } ?>
    <?php if( $exercise->cook_time_meta() ) { ?><meta itemprop="cookTime" content="<?php echo $exercise->cook_time_meta(); ?>"><?php } ?>
    <?php
    // Ingredients metadata (done here to avoid doubles)
    if( $exercise->has_ingredients() ) {
        $previous_group = null;
        foreach( $exercise->ingredients() as $ingredient ) {
            $group = isset( $ingredient['group'] ) ? $ingredient['group'] : '';

            if( $group !== $previous_group ) {
                if( !is_null( $previous_group ) ) {
                    echo '</div>';
                }
                echo '<div class="wpuep-meta wpuep-meta-ingredient-group" content="' . esc_attr( $group ) . '">';
                $previous_group = $group;
            }

            $meta = $ingredient['amount'] . ' ' . $ingredient['unit'] . ' ' . $ingredient['ingredient'];
            if( trim( $ingredient['notes'] ) !== '' ) {
                $meta .= ' (' . $ingredient['notes'] . ')';
            }
            echo '<meta itemprop="ingredients" content="' . esc_attr( $meta ). '">';
        }
        echo '</div>';
    }

    // Instructions metadata
    if( $exercise->has_instructions() ) {
        $previous_group = null;
        foreach( $exercise->instructions() as $instruction ) {
            $group = isset( $instruction['group'] ) ? $instruction['group'] : '';

            if( $group !== $previous_group ) {
                if( !is_null( $previous_group ) ) {
                    echo '</div>';
                }
                echo '<div class="wpuep-meta wpuep-meta-instruction-group" content="' . esc_attr( $group ) . '">';
                $previous_group = $group;
            }

            echo '<meta itemprop="exerciseInstructions" content="' . esc_attr( $instruction['description'] ) . '">';
        }
        echo '</div>';
    }
    ?>
<?php } ?>

    <?php	$this->output_children( $exercise, 0, 0, $args ) ?>
</div>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $this->after_output( $output, $exercise );
    }
}