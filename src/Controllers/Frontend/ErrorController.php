<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\Support\ForumBridge;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class ErrorController
{
    private static array $messages = [
        "name" => "Du hast vergessen einen Namen oder ein Passwort einzugeben!",
        "pass" => "Falsches Passwort oder falscher Benutzername!<br/><br/><a href=\"pwrequest\">Passwort vergessen?</a>",
        "ip" => "IP-Adresse-Überprüfungsfehler! Kein Login von diesem Computer möglich, da schon eine andere IP mit diesem Account verbunden ist!",
        "timeout" => "Das Timeout wurde erreicht und du wurdest automatisch ausgeloggt!",
        "session" => "Session-Cookie-Fehler. Überprüfe ob dein Browser wirklich Sitzungscookies akzeptiert!",
        "tomanywindows" => "Es wurden zu viele Fenster geöffnet oder aktualisiert, dies ist leider nicht erlaubt!",
        "session2" => "Deine Session ist nicht mehr vorhanden! Sie wurde entweder gelöscht oder sie ist fehlerhaft. Dies kann passieren wenn du dich an einem anderen PC einloggst obwohl du noch mit diesem online warst!",
        "nosession" => "Deine Session ist nicht mehr vorhanden! Sie wurde entweder gelöscht oder sie ist fehlerhaft. Dies kann passieren wenn du dich an einem anderen PC einloggst obwohl du noch mit diesem online warst!",
        "verification" => "Falscher Grafikcode! Bitte gib den linksstehenden Code in der Grafik korrekt in das Feld darunter ein! Diese Massnahme ist leider nötig um das Benutzen von automatisierten Programmen (Bots) zu erschweren.",
        "logintimeout" => "Der Login-Schlüssel ist abgelaufen! Bitte logge dich neu ein!",
        "sameloginkey" => "Der Login-Schlüssel wurde bereits verwendet! Bitte logge dich neu ein!",
        "wrongloginkey" => "Falscher Login-Schlüssel! Ein Login ist nur von der offiziellen EtoA-Startseite aus möglich!",
        "nologinkey" => "Kein Login-Schlüssel! Ein Login ist nur von der offiziellen EtoA-Startseite aus möglich!",
        "general" => "Ein allgemeiner Fehler ist aufgetreten. Bitte den Entwickler kontaktieren!",
        "unknown" => "Unbekannter Fehler. Bitte den Entwickler kontaktieren!",
    ];

    function __invoke(Request $request, Response $response, Twig $view): Response
    {
        $code = $request->getQueryParams()['err'] ?? 'unknown';

        return $view->render($response, 'frontend/error.html', [
            'site_title' => 'Fehler',
            'title' => 'Da ist was schiefgegangen...',
            'header_img' => 'err.png',
            'message' => self::$messages[$code] ?? self::$messages['unknown'],
            'support_url' => ForumBridge::url('board', get_config('support_board')),
            'developer_url' => 'https://github.com/etoa/etoa',
        ]);
    }
}
