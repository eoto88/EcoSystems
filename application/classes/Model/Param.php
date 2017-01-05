<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_Param {

    public function getParams() {
//        $query = DB::select('*')->from('param')->order_by('title', 'ASC');
        $query = DB::query(Database::SELECT,
            "SELECT p.id, p.id_category, p.code, p.title, pc.title AS category_title ".
            "FROM param AS p ".
            "JOIN param_category AS pc ON pc.id = p.id_category ".
            "ORDER BY p.title ASC, pc.id ASC"
        );
        // TODO Category name
        return $query->execute()->as_array();
    }

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