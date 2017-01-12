<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_ParamGroup {

    public function getInstanceParamGroups($id_instance) {
        $query = DB::query(Database::SELECT,
               "SELECT * ".
               "FROM param_group AS pg ".
               //"JOIN param_type AS pt ON pt.id = p.id_type ".
               "WHERE pg.id_instance = :id_instance "
        );
        $query->param(':id_instance', $id_instance);
        return $query->execute()->as_array();
    }
}