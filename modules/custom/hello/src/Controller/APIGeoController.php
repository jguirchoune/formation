<?php

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;
//use Guzzle\Http\Client;

class APIGeoController extends ControllerBase {

    public function regions($param)
    {
        $url='';

        if ($param == '') $url = 'https://geo.api.gouv.fr/regions';
        elseif ((string)(int)$param == $param) $url = 'https://geo.api.gouv.fr/regions?code='.$param;
        else $url = 'https://geo.api.gouv.fr/regions?nom='.$param;

        $build = $this->getAPIGeoInfo($url);
        return $build;
    }

    public function departements($param)
    {
        $url='';

        if ($param == '') $url = 'https://geo.api.gouv.fr/departements';
        elseif ((string)(int)$param == $param) $url = 'https://geo.api.gouv.fr/departements?code='.$param;
        else $url = 'https://geo.api.gouv.fr/departements?nom='.$param;

        $build = $this->getAPIGeoInfo($url);
        return $build;
    }

    public function departementsRegion($param)
    {
        $url = 'https://geo.api.gouv.fr/departements';
        if ($param) $url='https://geo.api.gouv.fr/regions/'.$param.'/departements';
        $build = $this->getAPIGeoInfo($url);
        return $build;
    }

    public function communes($param)
    {
        $url='';
        if ($param == '') $url = 'https://geo.api.gouv.fr/communes';
        elseif((string)(int)$param == $param) $url = 'https://geo.api.gouv.fr/communes?codePostal=' . $param;
        else $url = 'https://geo.api.gouv.fr/communes?nom='.$param.'&fields=departement&boost=population';

        $build = $this->getAPIGeoInfo($url);
        return $build;
    }

    public function communesDepartement($param)
    {
        $url = 'https://geo.api.gouv.fr/communes';
        if ($param) $url = 'https://geo.api.gouv.fr/departements/'.$param.'/communes';
        $build = $this->getAPIGeoInfo($url);
        return $build;
    }

    public function communesRegion($param)
    {
        $url = 'https://geo.api.gouv.fr/communes';
        if ($param) $url = 'https://geo.api.gouv.fr/regions/'.$param.'/communes';
        $build = $this->getAPIGeoInfo($url);
        return $build;
    }

    public function getAPIGeoInfo($url) {

        $crl = curl_init();
        curl_setopt($crl, CURLOPT_URL, $url);
        curl_setopt($crl, CURLOPT_RETURNTRANSFER, TRUE);

        // close the curl connection
        $result = curl_exec($crl);
        curl_close($crl);

        $data = json_decode($result, true);
        $keys = array_keys($data[0]);

        $header = [];
        $rows = [];

        foreach ($keys as $k)
        {
            $header[] = $k;
        }

        $i=0;
        foreach ($data as $d) {
            foreach ($keys as $k) {
                $rows[$i][] = $d[$k];
            }
            $i++;
        }


        $table = [
            '#theme' => 'table',
            '#header' => $header,
            '#rows' => $rows,
        ];

        $build = ['table' => $table];

        return $build;
    }

}
