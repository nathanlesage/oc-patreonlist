<?php namespace HendrikErz\PatreonList\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

class Tiers extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\ReorderController',
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';

    // User needs anything from the tiers_ permissions
    public $requiredPermissions = ['hendrikerz.patreonlist.manage'];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('HendrikErz.PatreonList', 'main', 'tiers');
    }
}
