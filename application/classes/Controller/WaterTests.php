<?php defined('SYSPATH') or die('No direct script access.');

class Controller_WaterTests extends Controller_AuthenticatedPage {

    public function before() {
        parent::before();
        $this->jsTranslations = array(
        );
    }

    public function action_index() {
        $this->template->title = __('Water tests');
        $this->template->icon = 'fa-flask';

        $hForm = new Helper_Form();

        $form = $hForm->createForm(array(
            'name' => 'form-water-test',
            'api-url' => 'waterTests',
            'requires-id-instance' => "true",
            'fields' => array(
                array(
                    'name' => 'id',
                    'type' => 'hidden'
                ),
                array(
                    'name' => 'date',
                    'type' => 'datepicker',
                    'label' => __('Date'),
                    'format' => 'YYYY/MM/DD',
                    'value' => date('Y/m/d')
                ),
                array(
                    'name' => 'ph',
                    'type' => 'spinner',
                    'label' => __('pH'),
                    'initval' => '7',
                    'min' => '1',
                    'max' => '14',
                    'decimals' => '1',
                    'step' => '0.1'
                ),
                array(
                    'name' => 'ammonia',
                    'type' => 'spinner',
                    'label' => __('Ammonia (NH<sub>3</sub>)'),
                    'initval' => '0',
                    'min' => '0',
                    'max' => '8',
                    'decimals' => '1',
                    'step' => '0.1'
                ),
                array(
                    'name' => 'nitrite',
                    'type' => 'spinner',
                    'label' => __('Nitrite (NO<sub>2</sub>)'),
                    'initval' => '0',
                    'min' => '0',
                    'max' => '4',
                    'decimals' => '1',
                    'step' => '0.1'
                ),
                array(
                    'name' => 'nitrate',
                    'type' => 'spinner',
                    'label' => __('Nitrate (NO<sub>3</sub>)'),
                    'initval' => '0',
                    'min' => '0',
                    'max' => '110',
                    'decimals' => '0',
                    'step' => '5'
                ),
                array(
                    'type' => 'submit'
                )
            )
        ));

        $mWaterTest = new Model_WaterTest();
        $waterTests = $mWaterTest->getWaterTests($this->currentInstanceId);
        $ph = array();
        $ammonia = array();
        $nitrite = array();
        $nitrate = array();

        foreach($waterTests as $row) {
            $datetime = strtotime($row['date']) * 1000;
            $ph[] = array(
                'x' => $datetime,
                'y' => floatval($row['ph'])
            );
            $ammonia[] = array(
                'x' => $datetime,
                'y' => floatval($row['ammonia'])
            );
            $nitrite[] = array(
                'x' => $datetime,
                'y' => floatval($row['nitrite'])
            );
            $nitrate[] = array(
                'x' => $datetime,
                'y' => floatval($row['nitrate'])
            );
        }

        $dashboardData = array(
            'phData' => $ph,
            'ammoniaData' => $ammonia,
            'nitriteData' => $nitrite,
            'nitrateData' => $nitrate,
            'form' => $form
        );

        $view = View::factory("watertests")->set($dashboardData);
        $this->template->content = $view->render();
    }

}
