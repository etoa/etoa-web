<?PHP

//////////////////////////////////////////////////
// The Andromeda-Project-Browsergame			//
// Ein Massive-Multiplayer-Online-Spiel			//
// Programmiert von Nicolas Perrenoud		    //
// als Maturaarbeit '04 am Gymnasium Oberaargau	//
//////////////////////////////////////////////////

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

    dbclose();
} catch (\PDOException $ex) {
    abort($ex->getMessage(), 'Datenbankfehler');
}
