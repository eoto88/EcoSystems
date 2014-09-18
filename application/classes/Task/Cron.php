
<?php defined('SYSPATH') or die('No direct script access.');
 
class Task_Cron extends Minion_Task {
    protected $_options = array();

    /*public function build_validation(Validation $validation) {
        return parent::build_validation($validation)
            ->rule('foo', 'not_empty') // Require this param
            ->rule('bar', 'numeric'); // This param should be numeric
    }*/
    
    private function checkToDo() {
        $mToDo = new Model_ToDo();
        $toDos = $mToDo->getToDo();
        foreach($toDos as $toDo) {
            error_log(print_r($toDo, true));
        }
        mail('when.the.music.pla@gmail.com', 'Graduinoponics Cron Job', 'Graduinoponics Cron Job');
    }
    
    private function backupLastDays() {
        $mHour = new Model_Hour();
        $mDay = new Model_Day();
        $lastDaysAvg = $mHour->getLastDaysTempAverage();
        foreach($lastDaysAvg as $dayAvg) {
            $mDay->updateDayAvg($dayAvg['date'], $dayAvg['avg_room_temp'], $dayAvg['avg_tank_temp']);
            $mHour->deleteHours($dayAvg['date']);
        }
    }
 
    /**
     * This is a demo task
     *
     * @return null
     */
    protected function _execute(array $params) {
        $this->backupLastDays();
        $this->checkToDo();
    }
}