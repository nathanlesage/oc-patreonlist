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
      'follows_you' => 'boolean'
    ];

    protected $dates = ['deleted_at'];

    protected $fillable = [
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
      'charge_status'
    ];

    public $belongsTo = [
      'tier' => ['HendrikErz\PatreonList\Models\Tier']
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
