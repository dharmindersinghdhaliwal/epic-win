<?php

class WPUEP_Template_Exercise_Stars extends WPUEP_Template_Block {

    public $editorField = 'exerciseStars';

    public function __construct( $type = 'exercise-stars' )
    {
        parent::__construct( $type );
    }

    // TODO Better integration with user ratings
    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) ) return '';

        if( WPUltimateExercise::is_addon_active( 'user-ratings' ) && WPUltimateExercise::option( 'user_ratings_enable', 'everyone' ) != 'disabled' ) {
            $stars =  WPUltimateExercise::addon( 'user-ratings' )->output( $exercise );
        } else {
            $stars =  $this->stars_author( $exercise );
        }

        $output = $this->before_output();
        $output .= '<span' . $this->style() . '>' . $stars . '</span>';

        return $this->after_output( $output, $exercise );
    }

    private function stars_author( $exercise )
    {
        $star_full = '<img src="' . WPUltimateExercise::get()->coreUrl . '/img/star.png" width="15" height="14">';
        $star_empty = '<img src="' . WPUltimateExercise::get()->coreUrl . '/img/star_grey.png" width="15" height="14">';

        $rating = $exercise->rating_author();
        $stars = '';

        if( $rating != 0 )
        {
            for( $i = 1; $i <= 5; $i++ )
            {
                if( $i <= $rating ) {
                    $stars .= $star_full;
                } else {
                    $stars .= $star_empty;
                }
            }
        }

        return $stars;
    }
}