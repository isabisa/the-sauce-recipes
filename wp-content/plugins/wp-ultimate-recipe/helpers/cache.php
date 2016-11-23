<?php

class WPURP_Cache {

    private $cache;

    public function __construct()
    {
        add_action( 'admin_init', array( $this, 'check_manual_reset' ) );
        add_action( 'admin_init', array( $this, 'check_if_present' ) );

        // Check if reset is needed
        add_action( 'save_post', array( $this, 'check_save_post' ), 11, 2 );
        add_action( 'admin_init', array( $this, 'check_reset' ) );
    }

    public function check_manual_reset()
    {
        if( isset( $_GET['wpurp_reset_cache'] ) ) {
            $this->trigger_reset();
            WPUltimateRecipe::get()->helper( 'notices' )->add_admin_notice( '<strong>WP Ultimate Recipe</strong> The cache is being reset' );
        }
    }

    public function check_if_present()
    {
        $this->cache = get_option( 'wpurp_cache', false );

        if( !$this->cache ) {
            $resetting = get_option( 'wpurp_cache_resetting', false );

            if( false === $resetting ) {
                $this->trigger_reset();
            }
        }
    }

    public function check_save_post( $id, $post )
    {
        if( $post->post_type == 'recipe' ) {
            $this->trigger_reset();
        }
    }

    public function trigger_reset()
    {
        $empty_cache = array(
            'recipes_by_date' => array(),
            'recipes_by_title' => array(),
            'recipe_authors' => array(),
            'recipes_with_data' => array(),
        );

        update_option( 'wpurp_cache_temp', $empty_cache );
        update_option( 'wpurp_cache_resetting', 0 );
    }

    public function check_reset()
    {
        $resetting = get_option( 'wpurp_cache_resetting', false );

        if( is_numeric( $resetting ) ) {
            $this->reset( $resetting, 100 );
        }
    }

    public function reset( $offset = 0, $limit = 100 )
    {
        $recipes_with_data = array();
        $recipes_by_date = array();
        $recipe_authors = array();
        $recipe_author_ids = array();

        $args = array(
            'post_type' => 'recipe',
            'post_status' => 'any',
            'orderby' => 'date',
            'order' => 'DESC',
            'posts_per_page' => $limit,
            'offset' => $offset,
        );

        $query = new WP_Query( $args );

        if( $query->have_posts() ) {
            $posts = $query->posts;

            foreach( $posts as $post ) {
                $recipe = new WPURP_Recipe( $post );
                $id = $recipe->ID();
                $author = $post->post_author;
                $title = $recipe->title();

                if( $post->post_status != 'publish' ) {
                    $title .= ' (' . $post->post_status . ')';
                }

                $recipes_by_date[] = array(
                    'value' => $id,
                    'label' => $title
                );

                if( !in_array( $author, $recipe_author_ids ) )
                {
                    $recipe_author_ids[] = $author;

                    $user = get_userdata( $author );

                    $name = $user ? $user->display_name : __( 'n/a', 'wp-ultimate-recipe' );

                    $recipe_authors[] = array(
                        'value' => $author,
                        'label' => $author . ' - ' . $name,
                    );
                }

                $recipes_with_data[$id] = array(
                    'ID' => $id,
                    'title' => $recipe->title(),
                    'post_status' => $post->post_status,
                    'link' => $recipe->link(),
                    'thumbnail' => $recipe->image_url( 'thumbnail' ),
                    'servings_normalized' => $recipe->servings_normalized(),
                    'nutritional' => $recipe->nutritional(),
                );
            }

            $recipes_by_title = $recipes_by_date;

            // Update current temp cache with new recipes
            $temp_cache = get_option( 'wpurp_cache_temp' );

            $recipes_by_date = array_merge( $temp_cache['recipes_by_date'], $recipes_by_date );
            $recipes_by_title = array_merge( $temp_cache['recipes_by_title'], $recipes_by_title );
            $recipe_authors = array_merge( $temp_cache['recipe_authors'], $recipe_authors );

            // Make sure $temp_cache['recipes_with_data'] is array
            $temp_cache['recipes_with_data'] = is_array( $temp_cache['recipes_with_data'] ) ? $temp_cache['recipes_with_data'] : array();

            $recipes_with_data = $temp_cache['recipes_with_data'] + $recipes_with_data; // Use + to preserve keys

            $updated_temp_cache = array(
                'recipes_by_date' => $recipes_by_date,
                'recipes_by_title' => $recipes_by_title,
                'recipe_authors' => $recipe_authors,
                'recipes_with_data' => $recipes_with_data,
            );

            update_option( 'wpurp_cache_temp', $updated_temp_cache );

            // Move on to next set of recipe next time
            update_option( 'wpurp_cache_resetting', $offset + $limit );

        } else {
            // No posts left, finished resetting
            update_option( 'wpurp_cache_resetting', false );

            // Sort temp cache
            $temp_cache = get_option( 'wpurp_cache_temp' );

            $recipes_by_date = $temp_cache['recipes_by_date'];
            $recipes_by_title = $temp_cache['recipes_by_title'];
            $recipe_authors = $temp_cache['recipe_authors'];
            $recipes_with_data = $temp_cache['recipes_with_data'];

            usort( $recipes_by_title, array( $this, 'sort_by_label' ) );
            usort( $recipe_authors, array( $this, 'sort_by_label' ) );

            // Put sorted temp cache as new cache
            $cache = array(
                'recipes_by_date' => $recipes_by_date,
                'recipes_by_title' => $recipes_by_title,
                'recipe_authors' => $recipe_authors,
                'recipes_with_data' => $recipes_with_data,
            );

            update_option( 'wpurp_cache', $cache );
            $this->cache = $cache;
        }
    }

    public function sort_by_label( $a, $b )
    {
        return strcmp( $a['label'], $b['label'] );
    }

    public function get( $item )
    {
        if( !$this->cache ) {
            $this->cache = get_option( 'wpurp_cache', array() );
        }

        if( isset( $this->cache[$item] ) ) {
            return $this->cache[$item];
        }
    }
}