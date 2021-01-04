<br />
<div class="boxLine"></div>
<div class="boxTitle">
    <h2>Bilder von EtoA</h2>
</div>
<div class="boxLine"></div>
<div class="boxData"><br />
    <?PHP
    $dir = "public/images/screenshots";
    $files = [
        'allianz',
        'auktion',
        'bauhof',
        'hilfe',
        'planet',
        'raumkarte',
        'userstatistik',
        'wirtschaft',
    ];

    $cnt = 0;
    foreach ($files as $f) {
        $cnt++;
        $file = $dir . "/" . $f . ".jpg";
        $file_small = $dir . "/" . $f . "_small.jpg";
        $fs = getimagesize($file);
        $name = ucfirst($f);
        echo '&nbsp; &nbsp; &nbsp;
        <a href="' . $file . '" data-fslightbox="gallery" data-caption="' . $name . '">
            <img src="' . $file_small . '" style="width:250px;height:187px;" alt="' . $name . '" title="' . $name . '"/>
        </a> &nbsp;';
        if ($cnt == 2) {
            $cnt = 0;
            echo "<br/><br/>";
        }
    }
    ?>
</div>
<div class="boxLine"></div><br />
