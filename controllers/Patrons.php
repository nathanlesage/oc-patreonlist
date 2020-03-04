<?php namespace HendrikErz\PatreonList\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use HendrikErz\PatreonList\Models\Patron;
use Input;
use Flash;

class Patrons extends Controller
{
    public $implement = [
      'Backend.Behaviors.ListController',
      'Backend.Behaviors.FormController',
      'Backend.Behaviors.ImportExportController'
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $importExportConfig = 'config_import.yaml';

    // User needs anything from the patrons_ permissions
    public $requiredPermissions = [ 'hendrikerz.patreonlist.manage' ];

    public function __construct()
    {
        parent::__construct();

        // We need to determine which controller method will be executed,
        // because otherwise only the list controller will be highlighted.
        // Here we're simply piggybacking on Laravels url() helper
        if (strpos(url()->current(), 'hendrikerz/patreonlist/patrons/import') === false) {
          // We're not in the import
          BackendMenu::setContext('HendrikErz.PatreonList', 'main', 'list');
        } else {
          // We are in the importing route
          BackendMenu::setContext('HendrikErz.PatreonList', 'main', 'import');
        }
    }

    public function onUnlinkTiers () {
      $successCounter = 0;
      $failureCounter = 0;
      foreach (Input::get('checked') as $id) {
        try {
          $patron = Patron::findOrFail($id);
          $patron->tier_id = null;
          $patron->save();
          $successCounter++;
        } catch (\Exception $e) {
          $failureCounter++;
        }
      }

      Flash::success("Successfully unlinked $successCounter tiers. There were $failureCounter errors.");
    }
}
