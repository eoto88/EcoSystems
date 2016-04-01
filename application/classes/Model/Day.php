<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_Day {
    
    public function getCurrentDayId($idInstance) {
        $this->insertIfNoCurrentDay($idInstance);
        $cd = $this->getCurrentDay($idInstance);
        return $cd['id_day'];
    }

    public function getCurrentDay($idInstance) {
        $this->insertIfNoCurrentDay($idInstance);
        $query = DB::query(Database::SELECT,
            "SELECT * FROM day WHERE `date` = DATE(NOW()) AND id_instance = :idInstance"
        );
        $query->param(':idInstance', $idInstance);
        return $query->execute()->current();
    }

    public function getDayByDate($idInstance, $date) {
        $query = DB::query(Database::SELECT,
            "SELECT id_day, date FROM day WHERE date = DATE(:date) AND id_instance = :idInstance"
        );
        $query->parameters(array(
            ':date' => $date,
            ':idInstance' => $idInstance
        ));
        return $query->execute()->current();
    }
    
    public function verifyIfCurrentDay($idInstance) {
        $query = DB::query(Database::SELECT,
            "SELECT COUNT(`id_day`) AS current_day FROM day WHERE `date` = DATE(NOW()) AND id_instance = :idInstance"
        );
        $query->param(':idInstance', $idInstance);
        $result = $query->execute()->current();
        return (intval($result['current_day']) > 0);
    }
    
    private function insertIfNoCurrentDay($idInstance) {
        if( ! $this->verifyIfCurrentDay($idInstance) ) {
            $query = DB::query(Database::INSERT, "INSERT INTO day (`date`, `id_instance`) VALUES( DATE(NOW()), :idInstance )");
            $query->param(':idInstance', $idInstance);
            $query->execute();
        }
    }
    
    public function getLastDays($idInstance) {
        $query = DB::query(Database::SELECT,
            "SELECT date, room_tmp_avg, tank_tmp_avg FROM day ".
            "WHERE `date` <= DATE(SUBDATE(current_date, 2)) AND id_instance = :idInstance"
        );
        $query->param(':idInstance', $idInstance);
        return $query->execute()->as_array();
    }
    
    public function updateDayAvg($idInstance, $date, $roomTempAvg, $tankTempAvg) {
        $query = DB::update('day')->set(
            array('room_tmp_avg' => $roomTempAvg, 'tank_tmp_avg' => $tankTempAvg)
        )->where('date', '=', $date)->and_where('id_instance', '=', $idInstance);
        $query->execute();
    }
}