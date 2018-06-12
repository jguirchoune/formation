<?php

namespace Drupal\hello\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a api geo block.
 *
 * @Block(
 *	id = "api_geo_block",
 *	admin_label = @Translation("API Geo")
 * )
 */
class APIGeo extends BlockBase {

	/**
	* Implements Drupal\Core\Block\BlockBase::build().
	*/
	public function build() {

        // request url
        $url = "https://geo.api.gouv.fr/departements/89/communes";
        //$crl = curl_init();


        try {

            // initialized curl
            $ch = curl_init();

            // set header for curl request
            /*$headers = array(
                "Cache-Control: no-cache",
                "Pragma: no-cache"
            );*/

            // set required setting with curl configuration
            curl_setopt($ch, CURLOPT_URL, $url);
            /*curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);*/
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            //curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            /*curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);*/

            // pass the additional values
            //curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));

            // close the curl connection
            $result = curl_exec($ch);
            curl_close($ch);

            ksm($result);
            //return $result;
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }





        /*$crl = curl_init();
        $url = "https://geo.api.gouv.fr/departements/01/communes";
        curl_setopt($crl, CURLOPT_URL, $url);
        $result=curl_exec($crl);
        ksm($result);
        $headers = curl_getinfo($crl);
        ksm($headers);
        curl_close($crl);*/

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