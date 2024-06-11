<?php
use Config\Services;
$twoLevelsUpDir = dirname(dirname(__FILE__));
$dirName = basename($twoLevelsUpDir);
$module="Base";
if (strpos($dirName, '_') === false) {
    $authentication = service("authentication");
    $routes = !isset($routes) ? Services::routes(true) : $routes;
    $mdm = model("App\Modules\{$module}\Models\{$module}_Modules");
    $mdcxm = model("App\Modules\{$module}\Models\{$module}_Clients_Modules");
    $namespace = "App\Modules\{$module}\Controllers";
    $cxm = $mdcxm->get_AuthorizedClientByModule($authentication->get_Client(), $mdm->get_Module($module, true));

    if ($cxm == "authorized") {
        $routes->group($module,
            ['namespace' => $namespace],
            function ($subroutes) {
                $module="Base";
                $subroutes->add('/',"{$module}::index");
                $subroutes->add('/home', "{$module}::index");
                $subroutes->add('home/(:any)', "{$module}::home/$1");
                $subroutes->add('(:any)/(:any)/(:any)', 'Router::route/$1/$2/$3');
            }
        );
    } else {
        $routes->group($module,
            ['namespace' => $namespace],
            function ($subroutes) {
                $module="Base";
                $subroutes->add('/', "{$module}::denied");
                $subroutes->add('(:any)', "{$module}::denied");
            }
        );
    }
}

?>
