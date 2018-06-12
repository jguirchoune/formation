<?php

namespace Drupal\hello\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;

class APIGeoForm extends FormBase {

    protected $region = null;
    protected $departements = null;
    protected $communes = null;

    public function getFormID() {
        return 'apigeo_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {

        $data = $this->getAPIGeoInfo('https://geo.api.gouv.fr/regions');
        $regions[] = 'Sélectionner la région';
        foreach ($data as $d) {
            $regions[] = [$d['code'] => $d['nom']];
            /*$regions['key'][] = $d['code'];
            $regions['value'][] = $d['nom'];*/
        }
        $form['regions'] = array(
            '#name' => 'regions',
            '#type' => 'select',
            '#title' => $this->t('Regions'),
            '#options' => $regions,
            '#ajax' => array(
                'callback' => array($this, 'validateTextAjax'),
                'event' => 'change',
            ),
            '#suffix' => '<span class="text-message"></span>',
        );

        $form['departements'] = array(
            '#name' => 'departements',
            '#type' => 'select',
            '#title' => $this->t('Départements'),
            '#options' => $this->departements,
            '#ajax' => array(
                'callback' => array($this, 'validateTextAjax2'),
                'event' => 'change',
            ),
            '#suffix' => '<span class="text-message2"></span>',
        );


        $form['submit'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Communes'),
        );

        if($this->communes) $form['communes'] = $this->communes;




        /*if($this->region) {
            $data = $this->getAPIGeoInfo('https://geo.api.gouv.fr/regions/'.$this->region.'/departements');
            $departements[] = '';
            foreach ($data as $d) {
                //$departements['key'][] = $d['code'];
                //$departements['value'][] = $d['nom'];
            }
            $form['regions'] = array(
                '#type' => 'select',
                '#title' => $this->t('Départements'),
                '#options' => $departements,
            );
        }*/

        return $form;
    }

    public function getAPIGeoInfo($url) {

        $crl = curl_init();
        curl_setopt($crl, CURLOPT_URL, $url);
        curl_setopt($crl, CURLOPT_RETURNTRANSFER, TRUE);

        // close the curl connection
        $result = curl_exec($crl);
        curl_close($crl);

        $data = json_decode($result, true);

        return $data;
    }

    public function validateTextAjax(array &$form, FormStateInterface $form_state) {

        $data = $this->getAPIGeoInfo('https://geo.api.gouv.fr/regions/'.$form_state->getValue('regions').'/departements');
        $departements[] = 'Sélectionner le département';
        foreach ($data as $d) {
            $departements[] = [$d['code'] => $d['nom']];
            //$departements[] = $d['nom'];
        }

        $this->departements = $departements;

        $form['departements'] = array(
            '#name' => 'departements',
            '#type' => 'select',
            '#title' => $this->t('Départements'),
            '#options' => $departements,
            '#ajax' => array(
                'callback' => array($this, 'validateTextAjax2'),
                'event' => 'change',
            ),
            '#suffix' => '<span class="text-message2"></span>',
        );

        //$this->region = $form_state->getValue('regions');
        //$css = ['border' => '2px solid green'];
        //$message = 'Résultat: ' . $this->region;

        $response = new AjaxResponse();
        //$response->addCommand(new CssCommand('#edit-text', $css));
        //$message = 'Résultat: ' . $form_state->getValue('regions');
        //$response->addCommand(new HtmlCommand('.text-message', $message));
        $response->addCommand(new HtmlCommand('.text-message', $form['departements']));
        return $response;
    }

    public function validateTextAjax2(array &$form, FormStateInterface $form_state) {

        $data = $this->getAPIGeoInfo('https://geo.api.gouv.fr/departements/'.$form_state->getValue('departements').'/communes');
        //$data = $this->getAPIGeoInfo('https://geo.api.gouv.fr/departements/'.'89'.'/communes');


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




        //$this->region = $form_state->getValue('regions');
        //$css = ['border' => '2px solid green'];
        //$message = 'Résultat: ' . $this->region;

        $response = new AjaxResponse();
        //$response->addCommand(new CssCommand('#edit-text', $css));
        //$message = 'Résultat: ' . $form_state->getValue('regions');
        //$response->addCommand(new HtmlCommand('.text-message', $message));
        $response->addCommand(new HtmlCommand('.text-message2', $build));
        //$response->addCommand(new HtmlCommand('.text-message', 'Test'));
        return $response;
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {

        $this->departements = $form_state->getValue('departements');
        $data = $this->getAPIGeoInfo('https://geo.api.gouv.fr/departements/'.$form_state->getValue('departements').'/communes');
        //$data = $this->getAPIGeoInfo('https://geo.api.gouv.fr/departements/'.'89'.'/communes');

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

        $this->communes = [
            '#theme' => 'table',
            '#header' => $header,
            '#rows' => $rows,
        ];

        $form_state->setRebuild();
    }

}