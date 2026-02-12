<?php get_header(); ?>

<main class="container my-5">
    <div class="row">
        <div class="col-lg-8">

            <?php while (have_posts()) : the_post(); ?>

                <article>
                    <h1><?php the_title(); ?></h1>

                    <div class="mb-3 text-muted">
                        <?php the_date(); ?>
                    </div>

                    <div>
                        <?php the_content(); ?>
                    </div>
                </article>

            <?php endwhile; ?>

        </div>
    </div>
</main>

<?php get_footer(); ?>
