<?php

namespace SellNow\Controllers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

abstract class Controller
{
    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    protected function render(string $template, array $data = []): void
    {
        $loader = new FilesystemLoader(__DIR__ . '/../../templates');
        $twig = new Environment($loader, ['debug' => true]);
        $twig->addGlobal('session', $_SESSION);
        
        echo $twig->render($template, $data);
    }
}