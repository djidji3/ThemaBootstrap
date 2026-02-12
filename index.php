<?php get_header(); ?>

<main class="container my-5">
    <div class="row">
        <div class="col-lg-8">

            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>

                    <article class="mb-4">
                        <h2>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h2>

                        <div>
                            <?php the_excerpt(); ?>
                        </div>
                    </article>

                <?php endwhile; ?>
            <?php else : ?>
                <p>Nincs találat.</p>
            <?php endif; ?>

        </div>
    </div>
</main>

<?php get_footer(); ?>

