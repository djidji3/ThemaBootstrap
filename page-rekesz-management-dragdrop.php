<?php
get_header();
global $wpdb;

/* Táblanevek */
$rekesz_tabla = $wpdb->prefix . "rekesz";
$termek_tabla = $wpdb->prefix . "termek";

/* Adatok lekérése */
$rekeszek = $wpdb->get_results("SELECT * FROM {$rekesz_tabla}", OBJECT);
$termekek = $wpdb->get_results("SELECT * FROM {$termek_tabla}", OBJECT);

/* Termékek csoportosítása */
$termekekRekeszSzerint = [];
foreach ($termekek as $termek) {
    $termekekRekeszSzerint[$termek->rekesz_id][] = $termek;
}

/* Nonce generálása */
$nonce = wp_create_nonce('rekesz_mentes_nonce');
?>

<body>

    
    <div class="fagyaszto">
        
        <div class="d-grid gap-2">
          <button id="mentes-gomb" class="btn btn-primary save-floating-btn" type="button">Mentés</button>
        </div>
<?php foreach ($rekeszek as $rekesz): ?>

    <div class="rekesz" onclick="kihuzaRekesz(this)">

        <div class="rekesz-fejlec">
            Hűtő id: <?php echo esc_html($rekesz->huto_id); ?>  &  
            <?php echo esc_html($rekesz->nev); ?>
            <span class="kihuza-icon">▶</span>
        </div>

        <div class="rekesz-tartalom">
            <div class="termekek-container"
                 data-rekesz-id="<?php echo esc_attr($rekesz->id); ?>">

                <?php
                if (!empty($termekekRekeszSzerint[$rekesz->id])):
                    foreach ($termekekRekeszSzerint[$rekesz->id] as $termek):
                ?>

                    <div class="termek-item"
                         data-termek-id="<?php echo esc_attr($termek->id); ?>"
                         data-lejarat="<?php echo esc_attr($termek->lejarati_datum); ?>"
                         draggable="true"
                         ondragstart="dragStart(event)"
                         ondragend="dragEnd(event)"
                         onclick="event.stopPropagation()">

                        <div class="termek-nev">
                            <?php echo esc_html($termek->nev); ?>
                        </div>

                        <div class="adat">
                            Mennyiség:
                            <span class="mennyiseg-szam">
                                <?php echo esc_html($termek->mennyiseg); ?>
                            </span>
                            <?php echo esc_html($termek->mennyisegi_egyseg); ?>
                        </div>

                        <div class="adat">
                            Termék csoport:
                            <?php echo esc_html($termek->termek_csoport); ?>
                        </div>

                        <div class="adat">
                            Lejárat:
                            <?php echo esc_html($termek->lejarati_datum); ?>
                        </div>

                        <div class="termek-gombok">
                            <button class="btn btn-minus"
                                    onclick="modosit(event,-1)">−</button>
                            <button class="btn btn-plus"
                                    onclick="modosit(event,1)">+</button>
                        </div>

                    </div>

                <?php endforeach; endif; ?>

            </div>
        </div>
    </div>

<?php endforeach; ?>

</div>

<script>
const ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
const rekeszNonce = "<?php echo $nonce; ?>";
</script>

<?php get_footer(); ?>
</body>
</html>