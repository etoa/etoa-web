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

    dbclose();
} catch (\PDOException $ex) {
    abort($ex->getMessage(), 'Datenbankfehler');
}
