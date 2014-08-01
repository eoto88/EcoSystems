<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_Day {

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
}