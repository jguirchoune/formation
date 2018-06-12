<?php

namespace Drupal\hello\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
* Listens to the dynamic route events.
*/
class AccessRestriction extends RouteSubscriberBase {

    /**
    * {@inheritdoc}
    */
    protected function alterRoutes(RouteCollection $collection) {

        // Change path '/user/login' to '/login'.
        /*if ($route = $collection->get('user.login')) {
            $route->setPath('/login');
        }
        // Always deny access to '/user/logout'.
        // Note that the second parameter of setRequirement() is a string.
        if ($route = $collection->get('user.logout')) {
            $route->setRequirement('_access', 'FALSE');
        }*/

        /*if ($route = $collection->get('system.modules_list'))  $route->setRequirement('_access', 'FALSE');
        if ($route = $collection->get('system.modules_uninstall'))  $route->setRequirement('_access', 'FALSE');
        if ($route = $collection->get('user.admin_permissions'))  $route->setRequirement('_access', 'FALSE');*/
    }

}