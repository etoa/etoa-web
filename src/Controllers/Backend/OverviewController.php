<?php

declare(strict_types=1);

namespace App\Controllers\Backend;

use App\Models\Forum\User;
use App\Repository\RoundRepository;
use App\Support\ForumBridge;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class OverviewController extends BackendController
{
    protected function getTitle(): string
    {
        return 'Übersicht';
    }

    public function __invoke(Request $request, Response $response, RoundRepository $rounds, ForumBridge $forum): Response
    {
        $admins = $forum->usersOfGroup(config('auth.admin.usergroup'));

        return parent::render($response, 'overview.html', [
            'forumAdminUrl' => ForumBridge::url('admin'),
            'rounds' => $rounds->all(),
            'admins' => array_map(fn (User $admin) => [
                'name' => $admin->username,
                'url' => ForumBridge::url('user', $admin->id),
            ], $admins),
            'sysinfo' => $this->getSystemAppInfo(),
        ]);
    }

    /**
     * @return array<string>
     */
    private function getSystemAppInfo(): array
    {
        $data = [
            'Environment' => config('app.environment')->value,
            'OS' => PHP_OS_FAMILY,
            'Web server' => $_SERVER['SERVER_SOFTWARE'],
            'PHP' => phpversion(),
        ];
        $gitInfoFile = APP_DIR . '/gitinfo';
        if (file_exists($gitInfoFile)) {
            $content = file_get_contents($gitInfoFile);
            $lines = array_filter(array_map('trim', explode("\n", $content)));
            foreach ($lines as $line) {
                $parts = explode(': ', $line, 2);
                if (2 == count($parts)) {
                    $data[$parts[0]] = $parts[1];
                }
            }
        }

        return $data;
    }
}
