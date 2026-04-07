<?php

namespace App\Twig;

use App\Menu\Platform\MenuBuilder;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function __construct(private MenuBuilder $menuBuilder) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('sidebar_menu', [$this->menuBuilder, 'build']),
            new TwigFunction('breadcrumbs',    [$this->menuBuilder, 'buildBreadcrumbs']),
            new TwigFunction('is_string', [$this, 'isString']),
            new TwigFunction('is_array', [$this, 'isArray']),
        ];
    }

    public function isString($variable): bool
    {
        return is_string($variable);
    }

    public function isArray($variable): bool
    {
        return is_array($variable);
    }
}
