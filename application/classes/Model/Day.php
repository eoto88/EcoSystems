<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_Day {
    
    public function getCurrentDayId() {
        $cd = $this->getCurrentDay();
        return $cd['id_day'];
    }

    public function getCurrentDay() {
        //$query = DB::query(Database::SELECT, "SELECT id_day, date, sunrise, sunset FROM day WHERE date = DATE(NOW())");
        $query = DB::select()->from('day')->where('date', '=', 'DATE(NOW())');
        return $query->execute()->current();
    }

    public function getDayByDate($date) {
        $query = DB::query(Database::SELECT, "SELECT id_day, date, sunrise, sunset FROM day WHERE date = DATE(:date)");
        $query->param(':date', $date);
        return $query->execute()->current();
    }
    
    public function verifyCurrentDay() {
        $query = DB::select( array(DB::expr('COUNT(`id_day`)'), 'current_day') )->from('day')->where('date', '=', 'DATE(NOW())');
        $result = $query->execute()->current();
        return $result['current_day'] > 0;
    }
    
    private function insertCurrentDay() {
        if( ! verifyCurrentDay() ) {
            $query = DB::query(Database::INSERT, "INSERT INTO day ('date') VALUES( DATE(NOW()) )");
            $query->execute();
        }
    }
    
    public function updateSunrise() {
        $query = DB::select()->from('day')->where('date', '=', 'DATE(NOW())');

    }
}