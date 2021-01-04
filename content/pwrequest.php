<br />
<div class="boxLine"></div>
<div class="boxTitle">
    <h2>Neues Passwort anfordern</h2>
</div>
<div class="boxLine"></div>
<div class="boxData">
    Bitte wähle die Runde aus, in der sich dein Account befindet:
    <ul>
        <?php foreach (App\Models\Round::active() as $round) : ?>
            <li>
                <a href="<?= loginRoundUrl($round, 'pwforgot') ?>"><?= $round->name ?></a>
                <?php if ($round->startdate > 0) : ?>
                    (online seit <?= App\Support\StringUtil::dateFormat($round->startdate) ?>)
                <?php endif; ?>
            </li>
        <?php endforeach ?>
    </ul>
</div>
<div class="boxLine"></div>
