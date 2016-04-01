
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
    
    public function checkTodos() {
        $config = Kohana::$config->load('app');
        $mTodo = new Model_Todo();
        $todos = $mTodo->getUncheckedTodos();

        $messageParams = array(
            'base_url' => $config['base_url'],
            'todos' => $todos
        );
        $viewMessage = View::factory( "email/todos" )->set($messageParams);

        $mLog = new Model_Log();

        $message = array(
            'subject' => 'EcoSystem - ToDo\'s',
            'body'    => $viewMessage->render(),
            'from'    => array($config['sender_email'] => 'EcoSystem'),
            'to'      => $config['admin_email']
        );
        $code = Email::send('default', $message['subject'], $message['body'], $message['from'], $message['to'], $mime_type = 'text/html');
        if( ! $code ) {
            $mLog->log( "error", __("Error while sending tasks list.") );
        } else {
            $mLog->log( "info", __("Send tasks list by email") );

        }
    }
    
    private function backupLastDays() {
        // TODO loop on users for backup
        $mInstance = new Model_Instance();
        // TODO get user for getInstances
        $instances = $mInstance->getInstances();
        $mHour = new Model_Hour();
        $mQuarterHour = new Model_QuarterHour();
        $mDay = new Model_Day();

        foreach($instances as $instance) {
            $lastDaysAvg = $mHour->getLastDaysTempAverage($instance['id_instance']);
            foreach ($lastDaysAvg as $dayAvg) {
                $mDay->updateDayAvg($instance['id_instance'], $dayAvg['date'], $dayAvg['avg_room_temp'], $dayAvg['avg_tank_temp']);
                $mHour->deleteHours($dayAvg['date']);
                $mQuarterHour->deleteQuarterHours($dayAvg['date']);
            }
        }
        $mLog = new Model_Log();
        $mLog->log( "info", __("Update last days average and delete old data") );
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