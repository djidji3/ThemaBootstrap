<!-- Ha a kert oldalra nincs direct fajl,akkor ez fog betoltodni -->
<?php get_header();?>

<main class="container my-5">
    <div class="row">
        <div class="col-lg-8 col-md-10 col-sm-12">

            <?php while (have_posts()) : the_post(); ?>

                <h1><?php the_title(); ?></h1>

                <div>
                    <?php the_content(); ?> 

                </div>
            <?php endwhile; ?>

        </div>
    </div>
</main>

<?php get_footer(); ?>