<?php namespace HendrikErz\PatreonList\Models;

use HendrikErz\PatreonList\Models\Patron;
use HendrikErz\PatreonList\Models\Tier;

class PatronImport extends \Backend\Models\ImportModel
{
    /**
     * @var array The rules to be applied to the data.
     */
    public $rules = [];

    // More info: https://octobercms.com/docs/backend/import-export
    public function importData($results, $sessionKey = null)
    {
      $numberFields = [
        'current_pledge',
        'max_amount',
        'lifetime_pledge'
      ];

      $seen = []; // Remember what patrons we had to warn on duplicates

        foreach ($results as $row => $data) {
          // Warn if there is no Email, as this is
          // being used as sort of an identifier internally
          if (!isset($data['email']) || $data['email'] === '') {
            $this->logWarning($row, "The Patron " . $data['name'] . " has no associated email! It is recommended to provide an Email for all patrons.");
          } else {
            // Warn of possible duplicates
            if (in_array($data['email'], $seen)) {
              $this->logWarning($row, 'The Patron ' . $data['name'] . ' (' . $data['email'] . ') has already been imported! Possible Duplicate.');
            } else {
              $seen[] = $data['email'];
            }
          }

          $tier = null;

          // Determine the tier, if applicable
          if ($data['tier'] !== '') {
            try {
              $tier = Tier::where('name', $data['tier'])->firstOrFail();
            } catch (\Exception $e) {
              // Create a tier and assume the pledge amount from
              // the patron, as this will in most cases not deviate.
              $this->logWarning($row, 'Tier ' . $data['tier'] . ' does not exist! Creating ...');
              $tier = new Tier;
              $tier->name = $data['tier'];
              if (isset($data['current_pledge']) && $data['current_pledge'] !== '') {
                $tier->pledge_amount = substr($data['current_pledge'], 1);
              } else {
                $tier->pledge_amount = 0;
              }
              $tier->save();
            }
          }

          // Now, correct certain fields. For instance, the Patreon
          // CSVs all include the pledges in the format $%d, which
          // is unhandy to store as an integer.
          foreach ($numberFields as $field) {
            if (isset($data[$field]) && strpos($data[$field], '$') === 0) {
              $data[$field] = substr($data[$field], 1);
            }
          }

          // Due to the nature of CSVs, even boolean fields are quite
          // literal. So we need to change that here!
          if (isset($data['follows_you'])) {
            $data['follows_you'] = $data['follows_you'] === 'Yes' ? 1 : 0;
          }

          // Same: We want a nice boolean here.
          if (isset($data['patron_status'])) {
            $data['patron_status'] = $data['patron_status'] === 'Active patron' ? 1 : 0;
          }

          // Now, let's see if we already have this patron somewhere
          try {
            $patron = Patron::where('email', $data['email'])->firstOrFail();
            $patron->fill($data);
            if ($tier) {
              // Attach to its tier
              $tier->patrons()->add($patron);
            } else {
              $patron->save(); // Save without tier
            }

            $this->logUpdated();
            // Continue with next loop iteration
            continue;
          } catch (\Exception $ex) {
            // At this point, do not log an error,
            // because the error is to be expected.
          }

          // Try creating the patron
          try {
            $patron = new Patron;
            $patron->fill($data);
            if ($tier) {
              // Attach to its tier
              $tier->patrons()->add($patron);
            } else {
              $patron->save(); // Save without tier
            }

            $this->logCreated();
          } catch (\Exception $ex) {
            $this->logError($row, $ex->getMessage());
          }
        }
    }
}
