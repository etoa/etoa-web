<?php

namespace App;

use App\Widgets\Widget;
use Smarty;

class TemplateEngine
{
    private $smarty;

    public function __construct()
    {
        $smarty = new Smarty;
        $smarty->setTemplateDir(__DIR__ . '/../templates');
        $smarty->addPluginsDir(__DIR__ . '/../plugins');
        $smarty->setCompileDir(__DIR__ . '/../cache/compile');

        $this->smarty = $smarty;
    }

    public function assign(string $key, $value)
    {
        if ($value instanceof Widget) {
            $content = $value->render($this);
        } else {
            $content = $value;
        }
        $this->smarty->assign($key, $content);
    }

    public function render(string $template)
    {
        $this->smarty->display($template);
    }

    public function fetch(string $template)
    {
        return $this->smarty->fetch($template);
    }
}
