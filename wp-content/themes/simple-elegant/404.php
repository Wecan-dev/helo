<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package Simple & Elegant
 * @since Simple & Elegant 1.0
 */

get_header(); ?>

<div id="page-wrapper"  style="background: #00aab2;">
    <div class="container">
    
        <section class="error-404 not-found">
            <header>
                <h1 class="page-title entry-title" itemprop="headline"><?php esc_html_e( 'Lo siento, no lo encontramos', 'simple-elegant' ); ?></h1>
            </header>

            <div class="page-content entry-content align-center">
                <p><?php esc_html_e( 'Quieres realizar una nueva busqueda?', 'simple-elegant' ); ?></p>

                <?php get_search_form(); ?>
            </div><!-- .page-content -->
        </section><!-- .error-404 -->
        
    </div><!-- .container -->
</div><!-- #page-wrapper -->

<?php get_footer(); ?>
