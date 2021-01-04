<?PHP

use App\Support\ForumBridge;
use App\Support\StringUtil;

$thread_id = get_config('rules_thread', 0);
$thread = ForumBridge::thread($thread_id);
?>
<br />
<div class="boxLine"></div>
<?php if ($thread !== null) : ?>
    <div class="boxTitle">
        <h2><?= $thread['subject'] ?></h2>
    </div>
    <div class="boxLine"></div>
    <div class="boxData">
        <?= StringUtil::text2html($thread["message"]) ?>
    </div>
<?php else : ?>
    <div class="boxTitle">Es trat ein Fehler auf!</div>
    <div class="boxLine"></div>
    <div class="boxData">
        <i>Regeln nicht vorhanden!</i>
    </div>
<?php endif; ?>
<div class="boxLine"></div>
