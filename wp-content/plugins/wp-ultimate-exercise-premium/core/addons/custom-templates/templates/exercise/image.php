<?php

class WPUEP_Template_Exercise_Image extends WPUEP_Template_Block {

    public $editorField = 'exerciseImage';
    public $thumbnail;
    public $crop = false;

    public function __construct( $type = 'exercise-image' )
    {
        parent::__construct( $type );
    }

    public function thumbnail( $thumbnail )
    {
        $this->thumbnail = $thumbnail;
        return $this;
    }

    public function crop( $crop )
    {
        $this->crop = $crop;
        return $this;
    }

    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) ) return '';
        if( !isset( $this->thumbnail ) ) $this->thumbnail = 'full';

        $thumb = wp_get_attachment_image_src( $exercise->image_ID(), $this->thumbnail );

        if(!$thumb) return ''; // No exercise image found

        $image_url = $thumb[0];
        if( is_null( $image_url ) ) return '';

        // Check image size unless a specific thumbnail was specified
        if( is_array( $this->thumbnail ) ) {

            $new_width = $this->thumbnail[0];
            $new_height = $this->thumbnail[1];

            if( $thumb[1] && $thumb[2] ) { // Use image size if passed along
                $width = $thumb[1];
                $height = $thumb[2];
            } else { // Or look it up for ourselves otherwise
                $size = getimagesize( $image_url );
                $width = $size[0];
                $height = $size[1];
            }

            // Don't distort the image
            $undistored_height = floor( $new_width * ( $height / $width ) );
            $this->add_style( 'height', $undistored_height.'px' );

            // Get correct thumbnail size
            $correct_thumb = array(
                $new_width,
                $undistored_height
            );

            $thumb = wp_get_attachment_image_src( $exercise->image_ID(), $correct_thumb );
            $image_url = $thumb[0];

            // Cropping the image
            if( $this->crop ) {
                $this->add_style( 'overflow', 'hidden', 'outer' );
                $this->add_style( 'max-width', $new_width.'px', 'outer' );
                $this->add_style( 'max-height', $new_height.'px', 'outer' );

                if( $new_height < $undistored_height ) {
                    $margin = -1 * ( 1 - $new_height / $undistored_height ) * 100/2;
                    $this->add_style( 'margin-top', $margin.'%' );
                    $this->add_style( 'margin-bottom', $margin.'%' );

                    $this->add_style( 'width', '100%' );
                    $this->add_style( 'height', 'auto' );
                } elseif ( $new_height > $undistored_height ) {
                    // We need a larger image
                    $larger_width = $new_height * ($new_width / $undistored_height);
                    $larger_thumb = array(
                        $larger_width,
                        $new_height
                    );

                    $thumb = wp_get_attachment_image_src( $exercise->image_ID(), $larger_thumb );
                    $image_url = $thumb[0];

                    $margin = ( $new_width - $larger_width ) / 2;
                    $this->add_style( 'margin-left', $margin.'px' );
                    $this->add_style( 'margin-right', $margin.'px' );
                    $this->add_style( 'width', $larger_width.'px' );
                    $this->add_style( 'max-width', $larger_width.'px' );
                    $this->add_style( 'height', $new_height.'px' );

                }
            }
        } else if( $this->thumbnail == 'full' ) {
            // Get better thumbnail size based on max possible block size
            $correct_thumb = array(
                $args['max_width'],
                $args['max_height']
            );

            $thumb = wp_get_attachment_image_src( $exercise->image_ID(), $correct_thumb );
            $image_url = $thumb[0];
        }

        $full_image_url = $exercise->image_url( 'full' );

        $args['desktop'] = $args['desktop'] && $this->show_on_desktop;
        $meta = $args['template_type'] == 'exercise' && $args['desktop'] ? true : false;

        $title_tag = WPUltimateExercise::option( 'exercise_image_title', 'attachment' ) == 'attachment' ? esc_attr( get_the_title( $exercise->image_ID() ) ) : esc_attr( $exercise->title() );

        $output = $this->before_output();

        ob_start();
?>
<?php if( $meta ) { ?>
    <div itemprop="image" itemscope itemtype="http://schema.org/ImageObject"<?php echo $this->style( 'outer' ); ?>>
        <?php if( WPUltimateExercise::option( 'exercise_images_clickable', '0' ) == 1 ) { ?>
            <a href="<?php echo $full_image_url; ?>" itemprop="contentUrl" rel="lightbox" title="<?php echo $title_tag; ?>">
                <img src="<?php echo $image_url; ?>" itemprop="thumbnailUrl" alt="<?php echo esc_attr( get_post_meta( $exercise->image_ID(), '_wp_attachment_image_alt', true) ); ?>" title="<?php echo $title_tag; ?>"<?php echo $this->style(); ?> />
            </a>
        <?php } else { ?>
            <meta itemprop="contentUrl" content="<?php echo $full_image_url; ?>">
            <img src="<?php echo $image_url; ?>" itemprop="thumbnailUrl" alt="<?php echo esc_attr( get_post_meta( $exercise->image_ID(), '_wp_attachment_image_alt', true) ); ?>" title="<?php echo $title_tag; ?>"<?php echo $this->style(); ?> />
        <?php } ?>
    </div>
<?php } else { ?>
    <div<?php echo $this->style( 'outer' ); ?>>
        <?php if( WPUltimateExercise::option( 'exercise_images_clickable', '0' ) == 1 ) { ?>
        <a href="<?php echo $full_image_url; ?>" rel="lightbox" title="<?php echo $title_tag; ?>">
            <img src="<?php echo $image_url; ?>" alt="<?php echo esc_attr( get_post_meta( $exercise->image_ID(), '_wp_attachment_image_alt', true) ); ?>" title="<?php echo $title_tag; ?>"<?php echo $this->style(); ?> />
        </a>
        <?php } else { ?>
        <img src="<?php echo $image_url; ?>" alt="<?php echo esc_attr( get_post_meta( $exercise->image_ID(), '_wp_attachment_image_alt', true) ); ?>" title="<?php echo $title_tag; ?>"<?php echo $this->style(); ?> />
        <?php } ?>
    </div>
<?php } ?>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $this->after_output( $output, $exercise );
    }
}