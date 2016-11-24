<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_Param {

    public function getInstanceParams($id_instance) {
        $query = DB::query(Database::SELECT,
            "SELECT * ".
            "FROM param ".
            "JOIN instance_param ON instance_param.id_param = param.id ".
            "WHERE instance_param.id_instance = :id_instance"
        );
        $query->param(':id_instance', $id_instance);
        return $query->execute()->as_array();
    }
}