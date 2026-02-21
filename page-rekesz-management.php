<!-- header betoltes -->
<?php get_header();

/* adatbazis muveletekhez */
global $wpdb;

/* kereses mezo tartalama valtozoba */
$kifejezes = (isset($_POST['kifejezes'])) ? $_POST['kifejezes'] : "";

/* a keresett kifejezes elott/utan bmi lehet, es escapelni is kell*/
$like = '%' . $wpdb->esc_like($kifejezes) . '%';

/* sql lekerdezes elokeszitese */
$sql = $wpdb->prepare(
    "SELECT * FROM {$wpdb->prefix}rekesz
     WHERE nev LIKE %s
     OR huto_id LIKE %s
     order by huto_id ASC
     LIMIT %d",
    $like,
    $like,
    10
);
$eredmeny = $wpdb->get_results($sql, OBJECT);


/* kimenet feldolgozasa */
$kimenet = "<table class='table table-striped table-sm table-secondary' id='rekesz-table'>
    <tr><th>Rekesz neve</th><th>Hűtő ID</th></tr>";
foreach ($eredmeny as $sor) {
    $kimenet .= "
     <tr>
        <td>{$sor->nev}</td>
        <td>{$sor->huto_id}</td>
    </tr>
    ";
}
$kimenet .= "</table>";
?>

<main class="container my-5">
    <div class="row">
        <div class="col-lg-8 col-md-10 col-sm-12">

            <?php while (have_posts()) : the_post(); ?>

                <h1 id="h1-cim"><?php the_title(); ?></h1>

                <div>
                    <!-- <?php the_content(); ?> -->
                    <form id="keresesForm" action="" method="post">
                        <input type="search" name="kifejezes" id="kifejezes" placeholder="Keresés...">
                    </form>
                </div>

                <h3>Rekesz lista</h3>

                    <div>
                        <!-- megjelenitjuk az adatbazis adatait html es php segitsegevel  -->
                        <?php echo $kimenet; ?>

                    </div>
            

            <?php endwhile; ?>

        </div>
    </div>
</main>

<!-- footer betoltes -->
<?php get_footer(); ?>