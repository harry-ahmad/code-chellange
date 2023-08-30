<?php

namespace App\Helpers;


use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;
use Exception;

class Twig
{
    public static function render(string $name, string $path, ?array $data = []): string
    {
        try {
            $loader = new FilesystemLoader($path);
            $twig = new Environment($loader, ['debug' => true]);
            return $twig->render($name, $data);
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}