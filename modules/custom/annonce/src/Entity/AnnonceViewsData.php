<?php

namespace Drupal\annonce\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Annonce entities.
 */
class AnnonceViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    $data['annonce_history']['table']['group'] = t('Annonce history');
    $data['annonce_history']['table']['provider'] = 'annonce';

    $data['annonce_history']['table']['base'] = [
      // Identifier (primary) field in this table for Views.
      'field' => 'ahid',
      // Label in the UI.
      'title' => t('Annonce history table'),
      // Longer description in the UI. Required.
      'help' => t('Annonce history table contains annonce history content and can be related to nodes.'),
      'weight' => -10,
    ];

    // Fields.
    $data['annonce_history']['uid'] = [
      'title' => t('UID'),
      'help' => t('User ID	'),
      'relationship' => [
        'base' => 'users_field_data',
        'base field' => 'uid',
        'id' => 'standard',
        'label' => t('User ID'),
      ],
    ];

    $data['annonce_history']['aid'] = [
      'title' => t('AID'),
      'help' => t('Visited annonce ID	'),
      'relationship' => [
        'base' => 'annonce',
        'base field' => 'id',
        'id' => 'standard',
        'label' => t('Visited annonce ID	'),
      ],
    ];

    $data['annonce_history']['date'] = [
      'title' => t('Date'),
      'help' => t('Visited date'),
      'field' => [
          'id' => 'date',
      ],
    ];

    return $data;
  }

}
