<?php
/**
 * 
 * Torbara Maxx-Fitness Theme for WordPress, exclusively on Envato Market: http://themeforest.net/user/torbara?ref=torbara
 * @encoding     UTF-8
 * @version      6.0.0
 * @copyright    Copyright (C) 2015 Torbara (http://torbara.com). All rights reserved.
 * @license      GNU General Public License version 2 or later, see http://www.gnu.org/licenses/gpl-2.0.html
 * @author       Alexandr Khmelnytsky (support@torbara.com)
 * @support      support@torbara.com
 * 
 */
 
// Load TGM plugin activation
require_once( get_template_directory() . '/includes/class-tgm-plugin-activation.php' );

//  TGM plugin activation
if ( ! function_exists( 'maxxfitness_recommended_plugins' ) ) {

        function maxxfitness_recommended_plugins() {

                $plugins = array(
                        array(
                            'name'      => esc_html__('Contact Form 7', 'maxx-fitness'),
                            'slug'      => 'contact-form-7',
                            'required'  => false
                        ),
                        array(
                            'name'      => esc_html__('Custom Category Template', 'maxx-fitness'),
                            'slug'      => 'custom-category-template',
                            'required'  => false,
                        ),
                        array(
                            'name'      => esc_html__('Google Maps Easy', 'maxx-fitness'),
                            'slug'      => 'google-maps-easy',
                            'required'  => false,
                        ),
                        array(
                            'name'      => esc_html__('Photo Gallery', 'maxx-fitness'),
                            'slug'      => 'photo-gallery',
                            'required'  => false,
                        ),
                        array(
                            'name'      => esc_html__('Post Template', 'maxx-fitness'),
                            'slug'      => 'wp-post-template',
                            'required'  => false,
                        ),
                        array(
                            'name'      => esc_html__('Widget Logic', 'maxx-fitness'),
                            'slug'      => 'widget-logic',
                            'required'  => false,
                        ),
                        array(
                            'name'      => esc_html__('PS Disable Auto Formatting', 'maxx-fitness'),
                            'slug'      => 'ps-disable-auto-formatting',
                            'required'  => false,
                        ),
                        array(
                            'name'      => esc_html__('WooCommerce', 'maxx-fitness'),
                            'slug'      => 'woocommerce',
                            'required'  => false,
                        ),
                        array(
                            'name'      => esc_html__('WP Editor', 'maxx-fitness'),
                            'slug'      => 'wp-editor',
                            'required'  => false,
                        ),
                        array(
                            'name'      => esc_html__('Yoast SEO', 'maxx-fitness'),
                            'slug'      => 'wordpress-seo',
                            'required'  => false,
                        ),
                        array(
                            'name'      => esc_html__('Ecwid Shopping Cart', 'maxx-fitness'),
                            'slug'      => 'ecwid-shopping-cart',
                            'required'  => false,
                        ),
                        array(
                            'name'      => esc_html__('WooCommerce Product Archive Customiser', 'maxx-fitness'),
                            'slug'      => 'woocommerce-product-archive-customiser',
                            'required'  => false,
                        ),
                        array(
                            'name'      => esc_html__('Page Builder: KingComposer', 'maxx-fitness'),
                            'slug'      => 'kingcomposer',
                            'required'  => false,
                        ),
                        // Prepaked plugins
                        array(
                            'name'      => esc_html__('Aklare Slider', 'maxx-fitness'),
                            'slug'      => 'aklare-slider',
                            'source'    => get_template_directory() .  '/plugins/aklare-slider.v.3.0.0.tar',
                            'required'  => false,
                            'version'   => '3.0.0'
                        ),
                        array(
                            'name'      => esc_html__('MaxxFitness Features widget', 'maxx-fitness'),
                            'slug'      => 'maxx-fitness-features',
                            'source'    => get_template_directory() .  '/plugins/maxx-fitness-features.v.3.0.0.tar',
                            'required'  => false,
                            'version'   => '3.0.0'
                        ),
                        array(
                            'name'      => esc_html__('MaxxFitness Locations widget', 'maxx-fitness'),
                            'slug'      => 'maxx-fitness-locations',
                            'source'    => get_template_directory() .  '/plugins/maxx-fitness-locations.v.3.0.0.tar',
                            'required'  => false,
                            'version'   => '3.0.0'
                        ),
                        array(
                            'name'      => esc_html__('MaxxFitness Products widget', 'maxx-fitness'),
                            'slug'      => 'maxx-fitness-products',
                            'source'    => get_template_directory() .  '/plugins/maxx-fitness-products.v.3.0.0.tar',
                            'required'  => false,
                            'version'   => '3.0.0'
                        ),
                        array(
                            'name'      => esc_html__('MaxxFitness Schedule widget', 'maxx-fitness'),
                            'slug'      => 'maxx-fitness-schedule',
                            'source'    => get_template_directory() .  '/plugins/maxx-fitness-schedule.v.3.0.0.tar',
                            'required'  => false,
                            'version'   => '3.0.0'
                        ),
                        array(
                            'name'      => esc_html__('MaxxFitness Trainers widget', 'maxx-fitness'),
                            'slug'      => 'maxx-fitness-trainers',
                            'source'    => get_template_directory() .  '/plugins/maxx-fitness-trainers.v.3.0.0.tar',
                            'required'  => false,
                            'version'   => '3.0.0'
                        ),
                        array(
                            'name'      => esc_html__('MaxxFitness Workouts widget', 'maxx-fitness'),
                            'slug'      => 'maxx-fitness-workouts',
                            'source'    => get_template_directory() .  '/plugins/maxx-fitness-workouts.v.3.0.0.tar',
                            'required'  => false,
                            'version'   => '3.0.0'
                        ),
                        array(
                            'name'      => esc_html__('Envato Market', 'maxx-fitness'),
                            'slug'      => 'envato-market',
                            'source'    => get_template_directory() . '/plugins/envato-market.1.0.0-RC2.tar',
                            'required'  => false,
                            'version'   => '1.0.0-RC2'
                        ),
                        array(
                            'name'     => esc_html__('KC Pro!', 'maxx-fitness'),
                            'slug'     => 'kc_pro',
                            'source'   => get_template_directory_uri() . '/plugins/kc_pro.tar',
                            'required' => true,
                            'version'  => '1.5'
                        ),
                        array(
                            'name'      => esc_html__('TT Warp 7 Framework', 'maxx-fitness'),
                            'slug'      => 'tt-warp7',
                            'source'    => get_template_directory() . '/plugins/tt-warp.7.3.36.tar',
                            'required'  => TRUE,
                            'version'   => '7.3.36'
                        )
                );
                $config = array('is_automatic' => true);
                tgmpa($plugins, $config);

        }
}
add_action( 'tgmpa_register', 'maxxfitness_recommended_plugins' );