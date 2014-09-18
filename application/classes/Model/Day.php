<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_Day {
    
    public function getCurrentDayId() {
        $this->insertIfNoCurrentDay();
        $cd = $this->getCurrentDay();
        return $cd['id_day'];
    }

    public function getCurrentDay() {
        $query = DB::query(Database::SELECT, "SELECT * FROM day WHERE `date` = DATE(NOW())");
        return $query->execute()->current();
    }

    public function getDayByDate($date) {
        $query = DB::query(Database::SELECT, "SELECT id_day, date, sunrise, sunset FROM day WHERE date = DATE(:date)");
        $query->param(':date', $date);
        return $query->execute()->current();
    }
    
    public function verifyIfCurrentDay() {
        $query = DB::select( array(DB::expr('COUNT(`id_day`)'), 'current_day') )->from('day')->where('date', '=', 'DATE(NOW())');
        $result = $query->execute()->current();
        return (intval($result['current_day']) > 0);
    }
    
    private function insertIfNoCurrentDay() {
        if( ! $this->verifyIfCurrentDay() ) {
            $query = DB::query(Database::INSERT, "INSERT INTO day (`date`) VALUES( DATE(NOW()) )");
            $query->execute();
        }
    }
    
    public function getLastDays() {
        $query = DB::query(Database::SELECT, "SELECT date, room_tmp_avg, tank_tmp_avg FROM day WHERE `date` <= DATE(SUBDATE(current_date, 2))");
        return $query->execute()->as_array();
    }
    
    public function updateSunrise($datetime) {
        $this->insertIfNoCurrentDay();
        $query = DB::update('day')->set(array('sunrise' => gmdate("Y-m-d H:i:s", $datetime)))->where('date', '=', gmdate("Y-m-d", $datetime));
        $query->execute();
    }
    
    public function updateSunset($datetime) {
        $this->insertIfNoCurrentDay();
        $query = DB::update('day')->set(array('sunset' => gmdate("Y-m-d H:i:s", $datetime)))->where('date', '=', gmdate("Y-m-d", $datetime));
        $query->execute();
    }
    
    public function updateDayAvg($date, $roomTempAvg, $tankTempAvg) {
        $query = DB::update('day')->set(array('room_tmp_avg' => $roomTempAvg, 'tank_tmp_avg' => $tankTempAvg))->where('date', '=', $date);
        $query->execute();
    }
}