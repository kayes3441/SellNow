<?php

namespace SellNow\Controllers;

use PDO;
use Twig\Environment;

abstract class Controller
{
    protected Environment $twig;
    protected PDO $db;

    public function __construct(Environment $twig, PDO $db)
    {
        $this->twig = $twig;
        $this->db = $db;
    }

    protected function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }

    protected function render(string $template, array $data = []): void
    {
        echo $this->twig->render($template, $data);
    }
}
