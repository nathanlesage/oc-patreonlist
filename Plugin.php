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

    // What I need for the list: Specific column types
    // I can do something like patron_hidden or so and
    // then define registerListColumnTypes() here in this
    // class so that they don't show up as yes/no, but
    // with, e.g. circles. Here's the source (sample implementation):
    // https://github.com/gergo85/oc-photography/blob/master/Plugin.php
    public function registerListColumnTypes()
    {
      return [
        'hide_indicator' => function ($val) {
          if (!$val) return ''; // No display if hide_from_all is 0
          // Else: Display a hidden indicator
          return '<span
            data-toggle="tooltip"
            data-placement="top"
            title="' . \Lang::get('hendrikerz.patreonlist::lang.columns.hide_from_all_tooltip') . '"
            class="oc-icon-eye-slash text-danger">
          </span>';
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
        'tier_description' => function($val) {
          return $val; // Don't filter out the HTML
        }
      ];
    }
}
