<br />
<div class="boxLine"></div>
<div class="boxTitle">
    <h2>Melde dich für eine Runde an</h2>
</div>
<div class="boxLine"></div>
<div class="boxData">
    Bitte wähle die Runde aus:
    <ul>
        <?php foreach (App\Models\Round::active() as $round) : ?>
            <li>
                <a href="<?= $round->url ?>/show.php?index=register"><?= $round->name ?></a>
                <?php if ($round->startdate > 0) : ?>
                    (online seit <?= App\Support\StringUtil::dateFormat($round->startdate) ?>)
                <?php endif; ?>
            </li>
        <?php endforeach ?>
    </ul>
</div>
<div class="boxLine"></div>
