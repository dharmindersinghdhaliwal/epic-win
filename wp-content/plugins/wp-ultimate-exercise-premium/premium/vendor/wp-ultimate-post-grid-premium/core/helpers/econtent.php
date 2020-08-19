<?php

class WPUPG_Econtent {

    public function __construct()
    {
        add_filter( 'the_content', array( $this, 'content_filter' ), 10 );
    }

    public function content_filter( $content )
    {
        $ignore_query = !is_main_query();
        if ( apply_filters( 'wpupg_content_loop_check', $ignore_query ) ) {
            return $content;
        }

        if ( get_post_type() == WPUPG_EPOST_TYPE ) {
            remove_filter( 'the_content', array( $this, 'content_filter' ), 10 );

            $egrid = new WPUPG_Egrid( get_post() );

            $content .= '[wpupg-efilter id="' . $egrid->slug() . '"]';
            $content .= '[wpupg-egrid id="' . $egrid->slug() . '"]';

            add_filter( 'the_content', array( $this, 'content_filter' ), 10 );
        }

        return $content;
    }
}