<?php

/**
 * The template for displaying archive projects pages.
 *
 * @package HelloElementor
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
?>
<style>
    .project-row {
        border: none;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: flex-start;
        gap: 10px;
    }

    .project-row .project-col {
        border: none;
        width: 32.33%;
    }

    .project-card {
        border: none;
        margin: 15px;
        box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.2);
        border-radius: 10px;
        padding: 20px;
    }

    .project-card .description {
        margin-top: 10px;
    }

    .project-card button {
        margin-top: 10px;
    }

    /* project card */
</style>
<?php

if (get_post_type() === 'projects') {
?>
    <div class="projects site-main" id="content">
        <div class="project-container">
            <div class="project-title">
                <h1><?php the_archive_title(); ?></h1>
                <p><?php the_archive_description(); ?></p>
            </div>
            <div class="project-row">
                <?php
                while (have_posts()) {
                    the_post();
                    $post_link = get_permalink();
                    $post_id = get_the_ID();
                    $feature_image_url = get_the_post_thumbnail_url($post_id);
                    $get_category = get_the_terms($post_id, 'project_type');
                ?>
                    <div class="project-col">
                        <div class="project-card">
                            <div class="feature-image">
                                <img src="<?php echo $feature_image_url;  ?>" alt="project-feature-image">
                            </div>
                            <div class="title">
                                <a href="<?php echo $post_link;  ?>">
                                    <h2><?php the_title(); ?> </h2>
                                </a>
                                <div>Category :</div>
                                <?php
                                foreach ($get_category as $cat) {
                                ?>

                                    <a href="<?php echo site_url() . '/project_type/' . $cat->slug; ?>"><span style="color:rgb(150,150,150);"><?php echo $cat->name . ' '; ?></span></a>
                                <?php } ?>
                            </div>
                            <div class="description">
                                <?php the_excerpt(); ?>
                            </div>
                            <div class="button">
                                <a href="<?php echo $post_link ?>"><button>Read more</button></a>
                            </div>
                        </div>
                    </div>
                <?php }
                wp_reset_postdata();

                ?>
                <?php wp_link_pages(); ?>
            </div>
            <?php
            global $wp_query;

            if ($wp_query->max_num_pages > 1) :
            ?>
                <nav class="pagination">
                    <?php /* Translators: HTML arrow */ ?>
                    <div class="nav-previous"></div><?php next_posts_link(sprintf(__('%s Previous', 'hello-elementor'), '<button class="meta-nav">&larr;</button>')); ?>
        </div>
        <?php /* Translators: HTML arrow */ ?>
        <div class="nav-next"><?php previous_posts_link(sprintf(__('Next %s', 'hello-elementor'), '<button class="meta-nav">&rarr;</button>')); ?></div>
        </nav>
    <?php endif; ?>
    </div>
    </div>





<?php } else {
} ?>