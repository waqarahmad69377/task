<?php

/**
 *  Template Name: Quotes 
 * 
 * @package HelloElementor
 * 
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
get_header();
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
<div class="site-main" id="content">

    <div class="project-container">
        <div class="project-row" id="quotes">


        </div>
    </div>


</div>




<?php


get_footer();

?>