<?php namespace HendrikErz\PatreonList;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
      return [
        'HendrikErz\PatreonList\Components\Patreons' => 'patreonList',
        'HendrikErz\PatreonList\Components\Tiers' => 'patreonTiers'
      ];
    }

    public function registerSettings()
    {
    }
}
