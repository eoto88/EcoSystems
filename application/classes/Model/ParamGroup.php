<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_ParamGroup {

    public function getInstanceGroups($id_instance) {
        $query = DB::query(Database::SELECT,
               "SELECT pg.id, pg.title ".
               "FROM param_group AS pg ".
               "WHERE pg.id_instance = :id_instance ".
               "ORDER BY pg.title ASC"
        );
        $query->param(':id_instance', $id_instance);
        return $query->execute()->as_array();
    }

    public function getInstanceHeaderGroup($id_instance) {
        $query = DB::query(Database::SELECT,
            "SELECT pg.id, pg.title ".
            "FROM param_group AS pg ".
            "WHERE pg.id_instance = :id_instance ".
            "AND pg.header = 1"
        );
        $query->param(':id_instance', $id_instance);
        return $query->execute()->as_array();
    }

    public function getInstanceGroupsWithoutHeader($id_instance) {
        $query = DB::query(Database::SELECT,
           "SELECT pg.id, pg.title ".
           "FROM param_group AS pg ".
           "WHERE pg.id_instance = :id_instance ".
           "AND pg.header = 0 ".
           "ORDER BY pg.title ASC"
        );
        $query->param(':id_instance', $id_instance);
        return $query->execute()->as_array();
    }
}