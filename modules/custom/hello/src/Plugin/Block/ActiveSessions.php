<?php

namespace Drupal\hello\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Provides an active sessions block.
 *
 * @Block(
 *	id = "active_sessions_block",
 *	admin_label = @Translation("Active Sessions")
 * )
 */
class ActiveSessions extends BlockBase {

	/**
	* Implements Drupal\Core\Block\BlockBase::build().
	*/
	public function build() {

		$database = \Drupal::database();

		/*$query = $database->select('sessions', 's')->fields('s', array('uid'));
		$active_sessions = $query->countQuery()->execute()->fetchField();*/
		$active_sessions = $database->select('sessions')->countQuery()->execute()->fetchField();
		$build = [
			'#markup' => $this->t('There is actually %sessions active sessions', [
				'%sessions' => $active_sessions
			]),
			'#cache' => [
				'keys' => ['activeSessions'],
				'max-age' => '10',
			],
		];
		
		return $build;
	}

	protected function blockAccess(AccountInterface $account) {
	    return AccessResult::allowedIfHasPermission($account, 'access hello');
    }
	
}