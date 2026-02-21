<?php get_header();

/* adatbazis muveletekhez */
global $wpdb;

/* kereses mezo tartalama valtozoba */
$kifejezes = (isset($_POST['kifejezes'])) ? $_POST['kifejezes'] : "";

/* a keresett kifejezes elott/utan bmi lehet, es escapelni is kell*/
$like = '%' . $wpdb->esc_like($kifejezes) . '%';

/* sql lekerdezes elokeszitese */
$sql = $wpdb->prepare(
    "SELECT * FROM {$wpdb->prefix}huto
     WHERE nev LIKE %s
     order by nev ASC
     LIMIT %d",
    $like,
    10
);
$eredmeny = $wpdb->get_results($sql, OBJECT);

/* kimenet feldolgozasa */
$kimenet = "<table class='table table-striped table-sm table-secondary' id='huto-table'>
            <tr>
                <th>Hűtő  neve</th>
                <th>Művelet</th>
            </tr>";
foreach ($eredmeny as $sor) {
    $kimenet .= "
     <tr>
        <td>{$sor->nev}</td>
        <td>
            <i class='fa-regular fa-pen-to-square'></i>
            <i class='fa-solid fa-trash'></i>
        </td>
    </tr>
    ";
}
$kimenet .= "</table>";
?>
<main class="container my-5">
    <div class="row">
        <div class="col-lg-8 col-md-10 col-sm-12">

            <?php while (have_posts()) : the_post(); ?>

                <h1><?php the_title(); ?></h1>

                <div>
                    <!-- <?php the_content(); ?> -->
                    <form id="keresesForm" action="" method="post">
                        <input type="search" name="kifejezes" id="kifejezes" placeholder="Keresés...">
                    </form>
                </div>

                <h3>Hűtő lista</h3>

                <div>
                    <a class="btn btn-secondary btn-sm" href="<?php echo site_url('/huto-felvitel'); ?>" role="button">Új hűtő felvétele</a>
                    <!-- megjelenitjuk az adatbazis adatait html es php segitsegevel  -->
                    <?php echo $kimenet; ?>
                    <a class="btn btn-secondary btn-sm" href="<?php echo site_url('/huto-felvitel'); ?>" role="button">Új hűtő felvétele</a>
                  
                </div>







            <?php endwhile; ?>

        </div>
    </div>
</main>

<?php get_footer(); ?>