<?PHP

//////////////////////////////////////////////////
// The Andromeda-Project-Browsergame			//
// Ein Massive-Multiplayer-Online-Spiel			//
// Programmiert von Nicolas Perrenoud		    //
// als Maturaarbeit '04 am Gymnasium Oberaargau	//
//////////////////////////////////////////////////

use App\TemplateEngine;
use App\Widgets\GameLogin;
use App\Widgets\InfoBox;
use App\Widgets\MainMenu;

require __DIR__ . '/vendor/autoload.php';

try {

    redirectHttps();

    // Session
    session_start();

    // Maintenance mode
    if (get_config('maintenance_mode') == 1) {
        include('_maintenance/index.html');
        exit;
    }

    // Templating engine
    $tpl = new TemplateEngine();

    // Page
    $page = isset($_GET['page']) ? $_GET['page'] : 'news';
    $tpl->assign('page', $page);
    if (preg_match('/^[a-z0-9_\/\-]+$/i', $page) > 0) {
        $pagepath = __DIR__ . "/content/$page.php";
        if (is_file($pagepath)) {
            ob_start();
            require $pagepath;
            $ob = ob_get_clean();
            $tpl->assign("content", $ob);
        } else {
            http_response_code(404);
            $tpl->assign('title', 'Fehler');
            $tpl->assign("error", "Seite wurde nicht gefunden!");
        }
    } else {
        http_response_code(400);
        $tpl->assign('title', 'Fehler');
        $tpl->assign("error", "Ungültige Abfrage!");
    }

    // Site nbame
    $tpl->assign("site_title", $site_title ?? ucfirst($page));

    // Render
    $tpl->render('layouts/site.html');

    dbclose();
} catch (\PDOException $ex) {
    abort($ex->getMessage(), 'Datenbankfehler');
}
