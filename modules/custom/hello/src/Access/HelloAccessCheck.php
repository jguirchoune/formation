<?php

namespace Drupal\hello\Access;

use Drupal\Core\Access\AccessCheckInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Access\AccessResult;
use Drupal\Component\Datetime\TimeInterface;


class HelloAccessCheck implements AccessCheckInterface {


    /**
     * @var \Drupal\Component\Datetime\Time
     */
    protected $time;

    /**
     * HelloAccessCheck constructor.
     * @param \Drupal\Component\Datetime\Time $date_time
     */
    public function __construct(TimeInterface $date_time) {
        $this->time = $date_time;
    }

    public function applies(Route $route) {
        return NULL;
    }

    public function access(Route $route, Request $request = NULL, AccountInterface $account) {

        $nbr_heures = $route->getRequirement('_access_hello');

        $created = $account->getAccount()->created;

        $pastTime = $this->time->getCurrentTime() - $created;

        $allowedAccessTime = $nbr_heures * 3600;

        if(!$account->isAnonymous() && ($pastTime > $allowedAccessTime)) return AccessResult::allowed()->cachePerUser();
        else return AccessResult::forbidden()->cachePerUser();

    }
}