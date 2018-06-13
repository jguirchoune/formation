<?php

namespace Drupal\annonce\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\Event;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Routing\ResettableStackedRouteMatchInterface;
use Drupal\Core\Database\Connection;
use Drupal\Component\Datetime\TimeInterface;

/**
 * Class AnnonceEventSubscriber.
 */
class AnnonceEventSubscriber implements EventSubscriberInterface {

  /**
   * Drupal\Core\Session\AccountProxyInterface definition.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;
  protected $messenger;
  protected $currentRouteMatch;
  protected $database;
  protected $time;

  /**
   * Constructs a new AnnonceEventSubscriber object.
   */
  public function __construct(AccountProxyInterface $current_user,
                              MessengerInterface $messenger,
                              ResettableStackedRouteMatchInterface $current_route_match,
                              Connection $database,
                              TimeInterface $time) {
    $this->currentUser = $current_user;
    $this->messenger = $messenger;
    $this->currentRouteMatch = $current_route_match;
    $this->database = $database;
    $this->time = $time;
  }

  /**
   * {@inheritdoc}
   */
  static function getSubscribedEvents() {
    $events['kernel.request'] = [['display_username'],['display_annonce'],['save_annonce_history']];

    return $events;
  }

  /**
   * This method is called whenever the kernel.request event is
   * dispatched.
   *
   * @param GetResponseEvent $event
   */
  public function display_username(Event $event) {
    //drupal_set_message('Event kernel.request thrown by Subscriber in module annonce.', 'status', TRUE);
    //\Drupal::messenger()->addMessage(t('Event for %name', ['%name' => \Drupal::currentUser()->getAccountName()]));
    //\Drupal::messenger()->addMessage(t('Event for %name', ['%name' => $this->currentUser->getAccountName()]));
    $this->messenger->addMessage(t('Event for %name', ['%name' => $this->currentUser->getDisplayName()]));
  }

  public function display_annonce(Event $event) {
    if($this->currentRouteMatch->getCurrentRouteMatch()->getRouteName() == 'entity.annonce.canonical')
        $this->messenger->addMessage(t('Annonce Entity'));
  }

  public function save_annonce_history(Event $event) {
      if($this->currentRouteMatch->getCurrentRouteMatch()->getRouteName() == 'entity.annonce.canonical') {
          $uid = $this->currentUser->id();
          $aid = $this->currentRouteMatch->getParameter('annonce')->id();
          $date = $this->time->getCurrentTime();
          $this->database->insert('annonce_history')
              ->fields([
                  'uid' => $uid,
                  'aid' => $aid,
                  'date' => $date,
              ])->execute();
      }
  }

}
