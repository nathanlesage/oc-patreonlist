<?php namespace HendrikErz\PatreonList\Models;

use Model;

/**
 * Model
 */
class Patron extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\SoftDelete;

    protected $casts = [
        'patron_status' => 'boolean',
        'current_pledge' => 'integer',
        'lifetime_pledge' => 'integer',
        'max_amount' => 'integer',
        'follows_you' => 'boolean',
        'hide_from_all' => 'boolean',
        'patreon_id' => 'integer',
    ];

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'patreon_id',
        'name',
        'email',
        'twitter',
        'patron_status',
        'follows_you',
        'current_pledge',
        'lifetime_pledge',
        'patron_since',
        'max_amount',
        'last_charge',
        'charge_status',
        'extra1',
        'extra2',
    ];

    public $belongsTo = [
        'tier' => ['HendrikErz\PatreonList\Models\Tier'],
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'hendrikerz_patreonlist_patrons';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}
