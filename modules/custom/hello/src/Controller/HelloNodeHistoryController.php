<?php

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Datetime\DateFormatter;
use Drupal\node\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class HelloNodeHistoryController extends ControllerBase {

  /**
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * @var \Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormatter;

  public function __construct(Connection $database, DateFormatter $dateFormatter) {
    $this->database = $database;
    $this->dateFormatter = $dateFormatter;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database'),
      $container->get('date.formatter')
    );
  }

  /**
   * Displays the updates history of the node.
   *
   * @param \Drupal\node\NodeInterface $node
   * @return array
   */
  public function content(NodeInterface $node) {

    /** @var \Drupal\Core\Database\Query\SelectInterface $query */
    $query = \Drupal::database()->select('hello_node_history', 'hnh')
      ->fields('hnh', ['uid', 'update_time'])
      ->condition('nid', $node->id());
    $result = $query->execute();

    // Tableau des updates.
    $rows = [];
    $uids = [];
    foreach ($result as $record) {
      $rows[] = [
        $this->entityTypeManager()->getStorage('user')->load($record->uid)->toLink(),
        \Drupal::service('date.formatter')->format($record->update_time),
      ];
      $uids[] = 'user:' . $record->uid;
    }
    $table = [
      '#theme' => 'table',
      '#header' => [$this->t('Author'), $this->t('Update time')],
      '#rows' => $rows,
    ];

    $count = count($rows);
    $output = [
      '#theme' => 'hello_node_history',
      '#node' => $node,
      '#count' => $count,
    ];

    return [
      'output' => $output,
      'table' => $table,
      '#cache' => [
        'keys' => ['hello:node_history:' . $node->id()],
        'tags' => array_merge(['node:' . $node->id()], $uids),
      ],
    ];
  }

}
