<?php
global $wpdb;
/* Template Name: Hűtő felvitel */
get_header();
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hűtő-felvétel</title>
</head>
<body>
    <h1>Új hűtő felvétele</h1>
    <form id="uj-huto-form" action="" method="post">
        <label for="hutoNev">Hűtő neve:</label>
        <input type="text" id="hutoNev" name="hutoNev" required>
        <div class="btn-group" role="group" aria-label="Basic example">
            <button type="button btn-sm btn-block" class="btn btn-warning">Mégse</button>
            <button type="button btn-sm btn-block" class="btn btn-primary">Hozzáadás</button>
        </div>
    
    </form>
</body>
</html>