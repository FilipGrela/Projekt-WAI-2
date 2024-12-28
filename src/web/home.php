<!DOCTYPE html>
<html lang="pl">
<head>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<section>
    <div id="gallery">
        <h1>Galeria</h1>
        <div class="gallery">
            <?php
            $dir = __DIR__. '/img/example_gallery/';
            $images = glob("$dir*.{jpg,jpeg,gif,png,bmp,webp}", GLOB_BRACE);

            foreach ($images as $i) {
                echo "<img alt='' src='/img/example_gallery/". rawurlencode(basename($i)) ."'>";
            }

            ?>
        </div>
    </div>
</section>
</body>
</html>