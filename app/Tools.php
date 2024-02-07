<?php

namespace App;

class Tools
{
    const REFERER = 'referer';
    const SYNDICATION = 'syndication';
    const DRIP_FEED = 'drip_feed';
    const CUTTER = 'cutter';
    const EVENT_CALENDER = 'event_calender';
    const LEAD_VALIDATOR = 'leadvalidator';
    const LANDING_PAGE = 'landingpage';
    const TYPOGENERATOR = 'typogenerator';



    public static function all(): array
    {
        return [
            self::REFERER,
            self::SYNDICATION,
            self::DRIP_FEED,
            self::CUTTER,
            self::EVENT_CALENDER,
            self::LANDING_PAGE,
            self::TYPOGENERATOR
        ];
    }

    public static function current(): string
    {
        return session()->get('selected_tool', 'referer');
    }

    public static function isCurrent($tool): bool
    {
        return in_array(static::current(), (array)$tool);
    }

    public static function switch(string $tool): void
    {
        session()->put('selected_tool', $tool);
    }
}
