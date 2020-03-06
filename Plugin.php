<?php namespace HendrikErz\PatreonList;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            'HendrikErz\PatreonList\Components\Patreons' => 'patreonList',
            'HendrikErz\PatreonList\Components\Tiers' => 'patreonTiers',
        ];
    }

    public function registerSettings()
    {
    }

    // Register custom column types
    public function registerListColumnTypes()
    {
        return [
            'hide_indicator' => function ($val) {
                // No display if hide_from_all is 0
                if (!$val) {
                    return '';
                }

                // Else: Display a hidden indicator
                $title = \Lang::get('hendrikerz.patreonlist::lang.columns.hide_from_all_tooltip');
                return '<span data-toggle="tooltip" data-placement="top" title="' . $title . '" class="oc-icon-eye-slash text-danger"></span>';
            },
            'patron_status' => function ($val) {
                if ($val) {
                    // Active patron
                    return '<span class="oc-icon-circle text-success">' . \Lang::get('hendrikerz.patreonlist::lang.columns.status_active') . '</span>';
                } else {
                    // Inactive patron
                    return '<span class="oc-icon-circle text-danger">' . \Lang::get('hendrikerz.patreonlist::lang.columns.status_inactive') . '</span>';
                }
            },
            'tier_description' => function ($val) {
                return $val; // Don't filter out the HTML
            },
        ];
    }
}
