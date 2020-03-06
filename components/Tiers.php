<?php namespace HendrikErz\PatreonList\Components;

use HendrikErz\PatreonList\Models\Tier;

class Tiers extends \Cms\Classes\ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'hendrikerz.patreonlist::lang.components.tiers.name',
            'description' => 'hendrikerz.patreonlist::lang.components.tiers.description',
        ];
    }

    public function defineProperties()
    {
        // In case you wonder: The tier list also should offer
        // some options regarding the sorting and existence of
        // patrons.
        return [
            'excludeZero' => [
                'title' => 'hendrikerz.patreonlist::lang.components.patrons.exclude_title',
                'description' => 'hendrikerz.patreonlist::lang.components.patrons.exclude_description',
                'type' => 'checkbox',
                'default' => 1,
                'required' => true,
            ],
            'excludeEmpty' => [
                'title' => 'hendrikerz.patreonlist::lang.components.tiers.exclude_title',
                'description' => 'hendrikerz.patreonlist::lang.components.tiers.exclude_description',
                'type' => 'checkbox',
                'default' => 0,
                'required' => true,
            ],
            'sortBy' => [
                'title' => 'hendrikerz.patreonlist::lang.components.patrons.sort_title',
                'description' => 'hendrikerz.patreonlist::lang.components.patrons.sort_description',
                'default' => 'current_pledge',
                'type' => 'dropdown',
                'required' => true,
                'options' => [
                    'id' => 'hendrikerz.patreonlist::lang.components.patrons.sort_id',
                    'name' => 'hendrikerz.patreonlist::lang.components.patrons.sort_name',
                    'current_pledge' => 'hendrikerz.patreonlist::lang.components.patrons.sort_pledge',
                    'lifetime_pledge' => 'hendrikerz.patreonlist::lang.components.patrons.sort_lifetime',
                    'patron_since' => 'hendrikerz.patreonlist::lang.components.patrons.sort_age',
                ],
            ],
            'sortOrder' => [
                'title' => 'hendrikerz.patreonlist::lang.components.patrons.sort_by_title',
                'description' => 'hendrikerz.patreonlist::lang.components.patrons.sort_by_description',
                'default' => 'desc',
                'type' => 'dropdown',
                'required' => true,
                'options' => [
                    'asc' => 'hendrikerz.patreonlist::lang.components.patrons.sort_asc',
                    'desc' => 'hendrikerz.patreonlist::lang.components.patrons.sort_desc',
                ],
            ],
        ];
    }

    /**
     * Returns a list of tiers with patrons, becomes available as patreonTiers.tiers.
     *
     * @return Tier[]
     */
    public function tiers()
    {
        $excludeFormerPatrons = $this->property('excludeZero');
        $excludeEmptyTiers = $this->property('excludeEmpty');

        $tiers = [];
        if ($excludeEmptyTiers) {
            // Force tiers to have at least one patron
            $tiers = Tier::has('patrons')->get();
        } else {
            // Load all tiers regardless of the amount of patrons
            $tiers = Tier::with('patrons')->get();
        }

        foreach ($tiers as $tier) {
            // Always hide all hidden patrons
            $tier->patrons = $tier->patrons->reject(function ($patron) {
                return $patron->hide_from_all;
            });

            // Kick out all former patrons
            if ($excludeFormerPatrons) {
                $tier->patrons = $tier->patrons->reject(function ($patron) {
                    return !$patron->isActive();
                });
            }

            // Sort the patrons
            if ($this->property('sortOrder') === 'asc') {
                $tier->patrons = $tier->patrons->sortBy($this->property('sortBy'));
            } else {
                $tier->patrons = $tier->patrons->sortByDesc($this->property('sortBy'));
            }
        }

        // Finally return
        return $tiers;
    }
}
