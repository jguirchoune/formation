<?php

namespace Drupal\hello\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;


class HelloAdminForm extends ConfigFormBase {

    public function getFormID() {
        return 'hello_admin_form';
    }

    protected function getEditableConfigNames() {
        return ['hello.config'];
    }

    public function buildForm(array $form, FormStateInterface $form_state) {

        //$value = $this->config(‘config_name’)->get(‘value’);

        $form['color_select'] = array(
            '#type' => 'select',
            '#title' => $this->t('Colors'),
            '#options' => [
                '' => $this->t('No color'),
                'green-class' => $this->t('Green'),
                'orange-class' => $this->t('Orange'),
                'blue-class' => $this->t('Blue'),
            ],
            '#default_value' => $this->config('hello.config')->get('color'),
            '#description' => $this->t('Choose color for processing.'),
        );

        /*$form['save_config'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Save Configuration'),
        );

        return $form;*/

        return parent::buildForm($form, $form_state);
    }

    /*public function validateForm(array &$form, FormStateInterface $form_state) {
    }*/

    public function submitForm(array &$form, FormStateInterface $form_state) {
        //$this->config(‘config_name’)->set(‘value’, ‘valeur’)->save();
        $this->config('hello.config')->set('color', $form_state->getValue('color_select'));

        $this->config('hello.config')->save();

        $this->entityTypeManager()->getViewBuilder('block')->resetCache();

        parent::submitForm($form, $form_state);
    }

}