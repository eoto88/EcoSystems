<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_Param {

    public function getParams() {
        $query = DB::query(Database::SELECT,
            "SELECT p.id, p.id_category, p.code, p.title, pc.title AS category_title ".
            "FROM param AS p ".
            "JOIN param_category AS pc ON pc.id = p.id_category ".
            "ORDER BY p.title ASC, pc.id ASC"
        );
        return $query->execute()->as_array();
    }

    public function getInstanceParams($id_instance) {
        $query = DB::query(Database::SELECT,
            "SELECT p.title, p.alias, pt.title AS type ".
            "FROM param AS p ".
            "JOIN param_type AS pt ON pt.id = p.id_type ".
            "WHERE p.id_instance = :id_instance "
        );
        $query->param(':id_instance', $id_instance);
        return $query->execute()->as_array();
    }
}