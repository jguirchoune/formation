<?php

namespace Drupal\hello\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;

class HelloForm extends FormBase {

    protected $result = null;

    public function getFormID() {
        return 'hello_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state) {

        $form['first_value'] = array(
            '#type' => 'number',
            '#title' => $this->t('First value'),
            '#required' => TRUE,
            '#description' => $this->t('Enter first value.'),
            '#ajax' => array(
                'callback' => array($this, 'validateTextAjax'),
                'event' => 'change',
            ),
        );

        $form['operation'] = array(
            '#type' => 'radios',
            '#title' => $this->t('Operation'),
            '#default_value' => 0,
            '#options' => array(
                0 => $this->t('Addition'),
                1 => $this->t('Soustract'),
                2 => $this->t('Multiply'),
                3 => $this->t('Divide'),
            ),
            '#description' => $this->t('Choose operation for processing.'),
            '#ajax' => array(
                'callback' => array($this, 'validateTextAjax'),
                'event' => 'change',
            ),
        );

        $form['second_value'] = array(
            '#type' => 'number',
            '#title' => $this->t('Second value'),
            '#required' => TRUE,
            '#description' => $this->t('Enter second value.'),
            '#ajax' => array(
                'callback' => array($this, 'validateTextAjax'),
                'event' => 'change',
            ),
            '#suffix' => '<span class="text-message"></span>',
        );


        $form['view'] = array(
            '#type' => 'select',
            '#title' => $this->t('View result on...'),
            '#options' => [
                '1' => $this->t('rebuild'),
                '2' => $this->t('redirect'),
            ],
            '#description' => $this->t('Choose operation for processing.'),
        );

        /*$form['view'] = array(
            '#type' => 'select',
            '#title' => $this->t('View'),
            '#options' => [
                $this->t('form'),
                $this->t('redirect'),
            ],
            '#required' => TRUE,
        );*/


        $form['calculate'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Calculate'),
        );

        $type_result = 'hidden';
        if($this->result) $type_result = 'number';

        $form['result'] = array(
            '#type' => $type_result,
            '#title' => $this->t('Result'),
            //'#attributes' => array('readonly' => 'readonly'),
            '#disabled' => TRUE,
            '#value' => $this->result,
        );

       /* if($this->result) {
            $form['result'] = array(
                '#type' => 'number',
                '#title' => $this->t('Result'),
                //'#attributes' => array('readonly' => 'readonly'),
                '#disabled' => TRUE,
                '#value' => $this->result,
            );
        }
        else {
            $form['result'] = array(
                '#type' => 'hidden',
            );
        }*/

        return $form;
    }

    public function validateTextAjax(array &$form, FormStateInterface $form_state) {


        $first_value = $form_state->getValue('first_value');
        $second_value = $form_state->getValue('second_value');
        $operation = $form_state->getValue('operation');
        switch ($operation){
            case 0 :
                $this->result = $first_value + $second_value;
                break;
            case 1 :
                $this->result = $first_value - $second_value;
                break;
            case 2 :
                $this->result = $first_value * $second_value;
                break;
            case 3 :
                $this->result = $first_value / $second_value;
                break;
        }
        $form_state->setRebuild();

        /*calculate($form, $form_state);
        $form_state->setRebuild();*/

        $css = ['border' => '2px solid green'];
        $message = 'Résultat: ' . $this->result;
        $response = new AjaxResponse();
        $response->addCommand(new CssCommand('#edit-text', $css));
        $response->addCommand(new HtmlCommand('.text-message', $message));
        return $response;
    }

    public function validateForm(array &$form, FormStateInterface $form_state) {
        $second_value = $form_state->getValue('second_value');
        $operation = $form_state->getValue('operation');
        if($second_value == 0 && $operation == 3) {
            $form_state->setErrorByName('second_value', $this->t('Cannot divide by zero !'));
        }
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {

        $first_value = $form_state->getValue('first_value');
        $second_value = $form_state->getValue('second_value');
        $operation = $form_state->getValue('operation');
        $view = $form_state->getValue('view');

        //calculate($form_state);

        switch ($operation){
            case 0 :
                $this->result = $first_value + $second_value;
                break;
            case 1 :
                $this->result = $first_value - $second_value;
                break;
            case 2 :
                $this->result = $first_value * $second_value;
                break;
            case 3 :
                $this->result = $first_value / $second_value;
                break;
        }
        //$form_state->setValue('result', $this->result);
        if ($view == '1') {
            //$form_state->addRebuildInfo('result', $this->result);
            $form_state->setRebuild();
        }
        else if ($view == '2')
            $form_state->setRedirect('hello.calculator.result', ['result' => $this->result]);
        /*if ($view == 'form') $form_state->setRebuild();
        else if ($view == 'redirect')
            $form_state->setRedirect('hello.calculator.result', ['result' => $this->result]);*/

        \Drupal::state()->set('calculator_lastsubmit', REQUEST_TIME);
    }

    public function calculate(FormStateInterface $form_state) {
        $first_value = $form_state->getValue('first_value');
        $second_value = $form_state->getValue('second_value');
        $operation = $form_state->getValue('operation');

        switch ($operation){
            case 0 :
                $this->result = $first_value + $second_value;
                break;
            case 1 :
                $this->result = $first_value - $second_value;
                break;
            case 2 :
                $this->result = $first_value * $second_value;
                break;
            case 3 :
                $this->result = $first_value / $second_value;
                break;
        }
    }
}