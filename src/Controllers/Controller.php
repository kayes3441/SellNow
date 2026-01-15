<?php

namespace SellNow\Controllers;

use PDO;
use Twig\Environment;
use SellNow\Container;

abstract class Controller
{
    protected Environment $twig;
    protected PDO $db;
    protected Container $container;

    public function __construct(
        Environment $twig,
        PDO $db,
        Container $container
    ) {
        $this->twig = $twig;
        $this->db = $db;
        $this->container = $container;
    }

    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    protected function view(string $template, array $data = []): void
    {
        echo $this->twig->render($template, $data);
    }

    /**
     * Resolve repository via interface
     */
    protected function repo(string $repositoryInterface)
    {
        return $this->container->resolve($repositoryInterface);
    }
}
