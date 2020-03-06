<?php namespace HendrikErz\PatreonList\Components;

use HendrikErz\PatreonList\Models\Patron;

class Patreons extends \Cms\Classes\ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'hendrikerz.patreonlist::lang.components.patrons.name',
            'description' => 'hendrikerz.patreonlist::lang.components.patrons.description',
        ];
    }

    public function defineProperties()
    {
        return [
            'excludeZero' => [
                'title' => 'hendrikerz.patreonlist::lang.components.patrons.exclude_title',
                'description' => 'hendrikerz.patreonlist::lang.components.patrons.exclude_description',
                'type' => 'checkbox',
                'default' => 1,
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
     * Unifies the retrieval of a list of patrons across the component.
     *
     * @return Patron[]
     */
    protected function getPatronList()
    {
        $exclude = $this->property('excludeZero');
        $patrons = Patron::orderBy($this->property('sortBy'), $this->property('sortOrder'))->get();
        if ($exclude) {
            return $patrons->reject(function ($patron) {
              return !$patron->isActive();
            });
        } else {
            return $patrons;
        }
    }

    /**
     * Simply return all patrons, becomes available via patreonList.patrons
     *
     * @return Patron[]
     */
    public function patrons()
    {
        // Retrieve all patrons
        $patrons = $this->getPatronList();

        // Return a list filtered without all those
        // patrons to be hidden
        return $patrons->reject(function ($patron) {
            return $patron->hide_from_all;
        });
    }
}
