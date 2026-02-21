
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
    "SELECT * FROM {$wpdb->prefix}termek
     WHERE nev LIKE %s
     OR termek_csoport LIKE %s
     OR mennyisegi_egyseg LIKE %s
     OR lejarati_datum LIKE %s
     OR rekesz_id LIKE %s
     ORDER BY lejarati_datum ASC 
     LIMIT %d",
    $like, $like, $like, $like, $like, 10);
$eredmeny = $wpdb->get_results($sql, OBJECT);


/* kimenet feldolgozasa */
$kimenet = "";
foreach ($eredmeny as $sor) {
    $kimenet .= "
    <article>
    <h4><?php echo esc_html($sor->nev); ?></h4>
    <p>vasarlasi_datum: {$sor->vasarlasi_datum}</p>
    <p>lejarati_datum:  {$sor->lejarati_datum}</p>
    <p>termek_csoport: {$sor->termek_csoport}</p>
    <p>mennyiseg: {$sor->mennyiseg}</p>
    <p>mennyisegi_egyseg: {$sor->mennyisegi_egyseg}</p>
    <p>rekesz_id: {$sor->rekesz_id}</p>
    <p>utolso_frissites: {$sor->utolso_frissites}</p>
    </article>
    ";
}
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
                    </form id="keresesForm">
                </div>

                <h3>Termék lista</h3>
                <article>
                    <div>
                        <!-- megjelenitjuk az adatbazis adatait html es php segitsegevel  -->
                        <?php echo $kimenet; ?>

                    </div>
                </article>

            <?php endwhile; ?>

        </div>
    </div>
</main>

<!-- footer betoltes -->
<?php get_footer(); ?>