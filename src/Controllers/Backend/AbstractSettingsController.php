<?php

namespace App\Controllers\Backend;

use App\Repository\ConfigSettingRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

abstract class AbstractSettingsController extends BackendController
{
    /**
     * @return array<string,array<string,mixed>>
     */
    abstract protected function getSettings(): array;

    /**
     * @return array<string,array<string,mixed>>
     */
    protected function getFields(ConfigSettingRepository $config): array
    {
        $fields = [];
        foreach ($this->getSettings() as $key => $def) {
            $fields[$key] = [
                ...$def,
                'name' => $key,
                'value' => $config->get($key, defaultValue: (string) $def['default'], useCache: false),
                'placeholder' => (string) $def['default'],
            ];
        }

        return $fields;
    }

    /**
     * @return array<string,array<string,mixed>>|false
     */
    protected function storeSettings(Request $request, Response $response, ConfigSettingRepository $config): array|false
    {
        $post = $request->getParsedBody();
        foreach ($this->getSettings() as $key => $def) {
            if ($def['required'] && (!isset($post[$key]) || '' == trim($post[$key]))) {
                $this->setSessionMessage('error', "Das Feld '" . $def['label'] . "' darf nicht leer sein.");

                return false;
            }
        }
        foreach ($this->getSettings() as $key => $def) {
            $config->set($key, trim($post[$key]));
        }

        return $post;
    }
}
