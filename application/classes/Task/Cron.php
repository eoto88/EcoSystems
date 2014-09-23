
<?php defined('SYSPATH') or die('No direct script access.');
 
class Task_Cron extends Minion_Task {
    protected $_options = array(
        'action' => ''
    );

    /*public function build_validation(Validation $validation) {
        return parent::build_validation($validation)
            ->rule('foo', 'not_empty') // Require this param
            ->rule('bar', 'numeric'); // This param should be numeric
    }*/
    
    private function checkTodos() {
        $mToDo = new Model_Todo();
        $toDos = $mToDo->checkTodos();
        $message = "";
        foreach($toDos as $toDo) {
            $message .= " - ". $toDo['title'];
        }
        if($message) {
            $config = Kohana::$config->load('app');
            $message = array(
                'subject' => 'Graduinoponics - To Do',
                'body'    => $message,
                'from'    => array($config['sender_email'] => 'Garduinoponics'),
                'to'      => $config['admin_email']
            );
            $code = Email::send('default', $message['subject'], $message['body'], $message['from'], $message['to']);
            if( ! $code ) {
                $mLog = new Model_Log();
                $mLog->log("error", "Can't send tasks list.");
            }
        }
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
        if( $params['action'] == 'backupLastDays' ) {
            $this->backupLastDays();
        } else if( $params['action'] == 'checkTodos' ) {
            $this->checkTodos();
        }
    }
}