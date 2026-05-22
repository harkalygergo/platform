<?php

namespace App\Enum\Platform;

enum WidgetTypeEnum: string
{
    case HeaderBanner    = 'headerBanner';
    case SidebarWidget1  = 'sidebarWidget1';
    case SidebarWidget2  = 'sidebarWidget2';
    case SidebarWidget3  = 'sidebarWidget3';
    case SidebarWidget4  = 'sidebarWidget4';
    case SidebarWidget5  = 'sidebarWidget5';
    case FooterBefore    = 'footerBefore';
    case FooterAfter     = 'footerAfter';

    public function label(): string
    {
        return match($this) {
            self::HeaderBanner   => 'header banner',
            self::SidebarWidget1 => 'sidebar widget 1',
            self::SidebarWidget2 => 'sidebar widget 2',
            self::SidebarWidget3 => 'sidebar widget 3',
            self::SidebarWidget4 => 'sidebar widget 4',
            self::SidebarWidget5 => 'sidebar widget 5',
            self::FooterBefore   => 'footer - before',
            self::FooterAfter    => 'footer - after',
        };
    }

    public static function getChoices(): array
    {
        $choices = [];
        foreach (self::cases() as $case) {
            $choices[$case->label()] = $case;
        }
        return $choices;
    }
}
