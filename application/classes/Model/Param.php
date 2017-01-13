<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_Param {

    public function getParamByAlias($id_instance, $alias) {
        $query = DB::query(Database::SELECT,
               "SELECT * ".
               "FROM param AS p ".
               "JOIN param_group AS pg ON pg.id = p.id_group ".
               "WHERE alias = :alias ".
               "AND pg.id_instance = :id_instance"
        );
        $query->param(':id_instance', $id_instance);
        $query->param(':alias', $alias);
        return $query->execute()->current();
    }

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
            "SELECT p.title, p.alias, pt.title AS type, p.id_group, pg.title AS groupTitle, p.options ".
            "FROM param AS p ".
            "LEFT JOIN param_type AS pt ON pt.id = p.id_type ".
            "LEFT JOIN param_group AS pg ON pg.id = p.id_group ".
            "WHERE pg.id_instance = :id_instance ".
            "ORDER BY pg.title ASC, p.title ASC"
        );
        $query->param(':id_instance', $id_instance);
        return $query->execute()->as_array();
    }

    public function insertParam($data) {

    }
}