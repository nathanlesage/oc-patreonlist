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
        'current_pledge' => 'float',
        'lifetime_pledge' => 'float',
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
        'last_charge',
        'currency',
        'charge_frequency',
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

    /**
     * Returns true, if the patron is active, otherwise false.
     *
     * @return boolean
     */
    public function isActive ()
    {
      // Requirements to be considered active:
      // 1. The patron_status must be set to "true" (e.g. "Active patron")
      // 2. The current_pledge must be > 0.0
      // 3. The charge_status must not be null
      // Cf.: https://docs.patreon.com/#member
      return $this->patron_status && $this->current_pledge > 0.0 && $this->charge_status;
    }
}
