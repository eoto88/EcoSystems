<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Profile extends Controller_AuthenticatedPage {

    public function before() {
        parent::before();
        $this->jsTranslations = array(

        );
    }

    public function action_index() {
        $this->template->title = __('Profile');
        $this->template->icon = 'fa-tachometer';

        $hForm = new Helper_Form();
        $form = $hForm->createForm(array(
            'name' => 'form-profile',
            'url' => URL::base(TRUE, TRUE) . 'login',
            'name' => 'test',
            'fields' => array(
                array(
                    'name' => 'username',
                    'label' => __('Username')
                ),
                array(
                    'name' => 'name',
                    'label' => __('Name')
                ),
                array(
                    'name' => 'email',
                    'label' => __('Email')
                ),
                array(
                    'type' => 'password',
                    'id' => 'profile-password',
                    'name' => 'password',
                    'label' => __('Password')
                )
            )
        ));

        $view = View::factory( "profile" )->set(array(
            'error_messages' => array(),
            'form' => $form
        ) );

        $this->template->content = $view->render();
    }
} // End Welcome
