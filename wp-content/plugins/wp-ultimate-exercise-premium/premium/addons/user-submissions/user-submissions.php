<?php

class WPUEP_User_Submissions extends WPUEP_Premium_Addon {

    public function __construct( $name = 'user-submissions' ) {
        parent::__construct( $name );

        add_action( 'init', array( $this, 'eassets' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'scripts_for_image_upload' ), -10 );
        add_action( 'init', array( $this, 'allow_logged_in_uploads' ) );

        add_action( 'wp_ajax_query-attachments', array( $this, 'ajax_restrict_media' ), 1 );
        add_action( 'wp_ajax_nopriv_query-attachments', array( $this, 'ajax_restrict_media' ), 1 );

        add_shortcode( 'wpuep_submissions', array( $this, 'submissions_shortcode' ) ); // For backwards compatibility
        add_shortcode( 'ultimate-exercise-submissions', array( $this, 'submissions_shortcode' ) );
        add_shortcode( 'ultimate-exercise-submissions-current-user-edit', array( $this, 'submissions_current_user_edit_shortcode' ) );
    }

    public function eassets() {
        WPUltimateExercise::get()->helper( 'eassets' )->add(
            array(
                'file' => $this->addonPath . '/css/public.css',
                'premium' => true,
                'public' => true,
                'shortcode' => array( 'wpuep_submissions', 'ultimate-exercise-submissions' ),
                //'setting' => array( 'user_submission_css', '1' ),
            ),
            array(
                'file' => $this->addonPath . '/css/public_base.css',
                'premium' => true,
                'public' => true,
                'shortcode' => array( 'wpuep_submissions', 'ultimate-exercise-submissions' ),
            ),
            array(
                'file' => '/js/exercise_form.js',
                'public' => true,
                'shortcode' => array( 'wpuep_submissions', 'ultimate-exercise-submissions' ),
                'deps' => array(
                    'jquery',
                    'jquery-ui-sortable',
                    'suggest',
                ),
                'data' => array(
                    'name' => 'wpuep_exercise_form',
                    'coreUrl' => WPUltimateExercise::get()->coreUrl,
                )
            ),
            array(
                'name' => 'user-submissions',
                'file' => $this->addonPath . '/js/user-submissions.js',
                'premium' => true,
                'public' => true,
                'shortcode' => 'ultimate-exercise-submissions',
                'deps' => array(
                    'jquery',
                    'select2',
                )
            )
        );
    }

    public function allow_logged_in_uploads() {
        if( is_user_logged_in() && !current_user_can('upload_files') && WPUltimateExercise::option( 'user_submission_enable', 'guests' ) != 'off' && WPUltimateExercise::option( 'user_submission_use_media_manager', '1' ) == '1' ) {
            $user = wp_get_current_user();
            $user->add_cap('upload_files');
        }
    }

    public function scripts_for_image_upload() {

        if( current_user_can( 'upload_files' ) && WPUltimateExercise::option( 'user_submission_enable', 'guests' ) != 'off' && WPUltimateExercise::option( 'user_submission_use_media_manager', '1' ) == '1' && WPUltimateExercise::get()->helper( 'eassets' )->check_for_shortcode( array( 'wpuep_submissions', 'ultimate-exercise-submissions' ) ) )
        {
            if( function_exists( 'wp_enqueue_media' ) ) {
                wp_enqueue_media();
            } else {
                wp_enqueue_style( 'thickbox' );
                wp_enqueue_script( 'media-upload' );
                wp_enqueue_script( 'thickbox' );
            }
        }
    }

    public function ajax_restrict_media()
    {
        if( WPUltimateExercise::option( 'user_submission_restrict_media_access', '1' ) == '1' && !current_user_can( 'edit_others_posts' ) ) {
            exit;
        }
    }

    public function submissions_shortcode() {

        switch( WPUltimateExercise::option( 'user_submission_enable', 'guests' ) ) {

            case 'off':
                return '<p class="errorbox">' . __( 'Sorry, the site administrator has disabled exercise submissions.', 'wp-ultimate-exercise' ) . '</p>';
                break;

            case 'guests':
                if( isset( $_POST['submitexercise'] ) ) {
                    return $this->submissions_process();
                } else {
                    return $this->submissions_form();
                }
                break;

            case 'registered':
                if( !is_user_logged_in() ) {
                    return '<p class="errorbox">' . __( 'Sorry, only registered users may submit exercises.', 'wp-ultimate-exercise' ) . '</p>';
                } else {
                    if( isset( $_POST['submitexercise'] ) ) {
                        return $this->submissions_process();
                    } else {
                        return $this->submissions_form();
                    }
                }
                break;

        }

    }

    public function submissions_current_user_edit_shortcode() {
        $output = '';
        $author = get_current_user_id();

        if( $author !== 0 ) {
            $exercises = WPUltimateExercise::get()->query()->author( $author )->post_status( array( 'publish', 'private', 'pending' ) )->get();

            if( count( $exercises ) !== 0 ) {
                $output .= '<ul class="wpuep-user-submissions-current-user-edit">';
                foreach ( $exercises as $exercise ) {
                    $item = '<li><a href="' . get_permalink() . '?wpuep-edit-exercise=' . $exercise->ID() . '">' . $exercise->title() . '</a></li>';
                    $output .= apply_filters( 'wpuep_user_submissions_current_user_edit_item', $item, $exercise );
                }
                $output .= '</ul>';
            }

            if( isset( $_POST['submitexercise'] ) ) {
                $output .= $this->submissions_process();
            } elseif( isset( $_GET['wpuep-edit-exercise'] ) ) {
                $exercise_id = $_GET['wpuep-edit-exercise'];
                $post = get_post( $exercise_id );

                if( $post->post_author == $author ) {
                    $output .= $this->submissions_form( $exercise_id );
                }
            }
        }

        return $output;
    }

    public function submissions_form( $exercise_ID = false ) {

        if( !$exercise_ID ) {
            // Create autosave when submission page viewed
            global $user_ID;
            $exercise_draft = array(
                'post_status' => 'auto-draft',
                'post_date' => date('Y-m-d H:i:s'),
                'post_author' => $user_ID,
                'post_type' => 'exercise',
                'post_content' => ' ',
            );

            $exercise_ID = wp_insert_post( $exercise_draft );
        }

        $exercise = new WPUEP_Exercise( $exercise_ID );

        ob_start();
        include( $this->addonDir . '/templates/public_exercise_form.php' );
        $form = ob_get_contents();
        ob_end_clean();

        return apply_filters( 'wpuep_user_submissions_form', $form, $exercise );
    }

    public function submissions_process() {
        $successmsg = '';

        if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) ) {

            wp_verify_nonce( $_POST['submitexercise'], 'exercise_submit' );

            // Check if updating
            $updating = false;

            $updating_id = isset( $_POST['exercise_id'] ) ? intval( $_POST['exercise_id'] ) : false;
            if( $updating_id ) {
                $updating_post = get_post( $updating_id );

                if( $updating_post->post_type == 'exercise' && $updating_post->post_status == 'auto-draft' ) {
                    $updating = true;
                } elseif( $updating_post->post_type == 'exercise' && $updating_post->post_author == get_current_user_id() ) {
                    $updating = true;
                }
            }

            $title = isset( $_POST['title'] ) ? $_POST['title'] : '';
            $_POST['exercise_title'] = $title;

            if( !$title ) $title = __( 'Untitled', 'wp-ultimate-exercise' );

            $post = array(
                'post_title' => $title,
                'post_type'	=> 'exercise',
                'post_status' => 'auto-draft',
            );

            // Save post
            if( $updating ) {
                $post['ID'] = $updating_id;
                $post['post_status'] = $updating_post->post_status;

                wp_update_post( $post );
                $post_id = $updating_id;
            } else {
                $post_id = wp_insert_post( $post, true );
            }

            // Add terms
            $taxonomies = WPUltimateExercise::get()->tags();
            unset($taxonomies['ingredient']);

            // Check categorie and tags as well
            $taxonomies['category'] = true;
            $taxonomies['post_tag'] = true;

            foreach( $taxonomies as $taxonomy => $options ) {
                $terms = $_POST['exercise-'.$taxonomy];
                if( isset( $terms ) ) {
                    if( !is_array( $terms ) ) {
                        $terms = array( intval( $terms ) );
                    } else {
                        $terms = array_map( 'intval', $terms );
                    }

                    wp_set_object_terms( $post_id, $terms, $taxonomy );
                }
            }

            // If guest, add author name
            if( !is_user_logged_in() ) {
                if( $_POST['exercise-author'] != '' ) {
                    $authorname = $_POST['exercise-author'];
                } else {
                    $authorname = __( 'Anonymous', 'wp-ultimate-exercise' );
                }
                update_post_meta( $post_id, 'exercise-author', $authorname );
            }

            // Add featured image from media uploader
            if( isset( $_POST['exercise_thumbnail'] ) ) {
                update_post_meta( $post_id, '_thumbnail_id', $_POST['exercise_thumbnail'] );
            }

            // Add all images from basic uploader
            if( $_FILES ) {
                foreach( $_FILES as $key => $file ) {
                    if ( 'exercise-thumbnail' == $key ) {
                        if( $file['name'] != '' ) {
                            $this->insert_attachment_basic( $key, $post_id, true );
                        }
                    } else {
                        $this->insert_attachment_basic( $key, $post_id, false );
                    }
                }
            }

            // Check required fields
            $errors = array();
            $required_fields = WPUltimateExercise::option( 'user_submission_required_fields', array() );

            $required_fields_labels = array();
            $required_fields_options = wpuep_admin_user_submission_required_fields();
            foreach( $required_fields_options as $required_fields_option ) {
                $required_fields_labels[$required_fields_option['value']] = $required_fields_option['label'];
            }

            foreach( $required_fields as $required_field ) {
                if( isset( $_POST[$required_field] ) && $_POST[$required_field] == '' ) {
                    $errors[] = $required_fields_labels[$required_field];
                }
            }

            // Check security question
            if( WPUltimateExercise::option( 'user_submissions_use_security_question', '' ) == '1' ) {
                if( !isset( $_POST['security-answer'] ) || trim( $_POST['security-answer'] ) !== trim( WPUltimateExercise::option( 'user_submissions_security_answer', '11' ) ) ) {
                    $errors[] = __( 'Security Question', 'wp-ultimate-exercise' );
                }
            }

            if( count( $errors ) > 0 || isset( $_POST['preview'] ) ) {
                $output = '';

                if( count( $errors ) > 0 ) {
                    $output .= '<div class="wpuep-errors">';
                    $output .= __( 'Please fill in these required fields:', 'wp-ultimate-exercise' );
                    $output .= '<ul>';
                    foreach( $errors as $error ) {
                        $output .= '<li>' . $error . '</li>';
                    }
                    $output .= '</ul>';
                    $output .= '</div>';
                }

                if( isset( $_POST['preview'] ) ) {
                    $output .= '<h4>' . __( 'Preview', 'wp-ultimate-exercise' ). '</h4>';
                    $output .= '[ultimate-exercise id=' . $post_id . ']';
                    $output .= '<br/><br/>';
                }

                $output .= $this->submissions_form( $post_id );

                return do_shortcode( $output );
            } else {
                // Update post status
                $args = array(
                    'ID' => $post_id,
                    'post_status' => 'pending',
                );

                // Check approval rules
                $auto_approve = WPUltimateExercise::option( 'user_submission_approve', 'off' );

                if( $auto_approve == 'guests' ) {
                    $args['post_status'] = 'publish';
                } elseif( $auto_approve == 'registered' && is_user_logged_in() ) {
                    $args['post_status'] = 'publish';
                }

                $auto_approve_users = WPUltimateExercise::option( 'user_submission_approve_users', array() );
                $auto_approve_users = array_map( 'intval', $auto_approve_users );
                if( in_array( get_current_user_id(), $auto_approve_users ) ) {
                    $args['post_status'] = 'publish';
                }

                $auto_approve_role = trim( WPUltimateExercise::option( 'user_submissions_approve_role', '' ) );
                if( $auto_approve_role !== '' && current_user_can( $auto_approve_role ) ) {
                    $args['post_status'] = 'publish';
                }

                wp_update_post( $args );

                // Success message
                $successmsg = WPUltimateExercise::option( 'user_submission_submitted_text', __( 'Exercise submitted! Thank you, your exercise is now awaiting moderation.', 'wp-ultimate-exercise' ) );

                // Send notification email to administrator
                if( WPUltimateExercise::option('user_submission_email_admin', '0' ) == '1' ) {
                    $to = get_option( 'admin_email' );

                    if( $to ) {
                        $edit_link = get_edit_post_link( $post_id, '');

                        $subject = 'New user submission: ' . $title;
                        $message = 'A new exercise has been submitted on your website.';
                        $message .= "\r\n\r\n";
                        $message .= 'Edit this exercise: ' . $edit_link;

                        wp_mail( $to, $subject, $message );
                    }
                }
            }
        }

        do_action('wp_insert_post', 'wp_insert_post');
        return '<p class="successbox">' . $successmsg . '</p>';
    }

    public function insert_attachment_basic( $file_handler, $post_id, $setthumb = false ) {
        if ( $_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK ) {
            return;
        }

        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/media.php' );

        $attach_id = media_handle_upload( $file_handler, $post_id );

        if( true == $setthumb ) { // Thumbnail image
            update_post_meta( $post_id, '_thumbnail_id', $attach_id );
        } else { // Instructions image
            $number = explode( '_', $file_handler );
            $number = $number[2];
            $instructions = get_post_meta( $post_id, 'exercise_instructions', true );
            $instructions[$number]['image'] = $attach_id;
            update_post_meta( $post_id, 'exercise_instructions', $instructions );
        }

        return $attach_id;
    }
}

WPUltimateExercise::loaded_addon( 'user-submissions', new WPUEP_User_Submissions() );