<?php

namespace Drupal\hello\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a hello block.
 *
 * @Block(
 *	id = "hello_block",
 *	admin_label = @Translation("Hello!")
 * )
 */
class Hello extends BlockBase {

	/**
	* Implements Drupal\Core\Block\BlockBase::build().
	*/
	public function build() {
		$build = [
			'#markup' => $this->t('Welcome %name. It is %time.', [
				'%name' => \Drupal::currentUser()->getAccountName(),
				'%time' => \Drupal::service('date.formatter')->format(\Drupal::service('datetime.time')->getCurrentTime(), 'custom', 'H:i s\s'),
			]),
			'#cache' => [
				'keys' => ['hello:build'],
				'max-age' => '1000',
				'contexts' => ['user'],
			],
		];

		return $build;
	}
	
}