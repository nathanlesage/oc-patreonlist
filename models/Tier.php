<?php namespace HendrikErz\PatreonList\Models;

use Model;

/**
 * Model
 */
class Tier extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name', 'pledge_amount',
    ];

    protected $implements = [
        'October\Rain\Database\Traits\Sortable',
    ];

    public $hasMany = [
        'patrons' => ['HendrikErz\PatreonList\Models\Patron'],
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'hendrikerz_patreonlist_tiers';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}
