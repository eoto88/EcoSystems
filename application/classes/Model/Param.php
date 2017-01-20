<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_Param {

    public function getParamByAlias($id_instance, $alias) {
        $query = DB::query(Database::SELECT,
               "SELECT p.id, p.id_group, pt.alias AS typeAlias, pt.data_type AS dataType ".
               "FROM param AS p ".
               "JOIN param_group AS pg ON pg.id = p.id_group ".
               "JOIN param_type AS pt ON pt.id = p.id_type ".
               "WHERE p.alias = :alias ".
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

    public function getHeaderParamsWithData($id_instance) {
        return $this->getGroupParamsWithData($id_instance, true);
    }

//SELECT p.title, p.alias, pt.title AS type, p.id_group, pg.title AS groupTitle, p.options, d.data, d.datetime
//FROM param AS p
//JOIN param_type AS pt ON pt.id = p.id_type
//JOIN param_group AS pg ON pg.id = p.id_group
//LEFT JOIN data AS d ON d.id_param = p.id
//LEFT OUTER JOIN data d2 ON (d2.id_param = p.id AND (d.datetime < d2.datetime OR d.datetime = d2.datetime AND d.id < d2.id))
//WHERE pg.id_instance = 1
//AND d.data <> ""
//AND d2.id IS NULL
//#AND pg.header = "1"
//ORDER BY d.datetime DESC, pg.title ASC, p.title ASC

    public function getGroupParamsWithData($id_instance, $header) {
        $query = DB::query(Database::SELECT,
            "SELECT p.title, p.alias AS paramAlias, p.icon, pt.alias AS typeAlias, pt.title AS type, p.id_group, pg.title AS groupTitle, p.options, d.data, d.datetime ".
            "FROM param AS p ".
            "JOIN param_type AS pt ON pt.id = p.id_type ".
            "JOIN param_group AS pg ON pg.id = p.id_group ".
            "LEFT JOIN data AS d ON d.id_param = p.id ".
            "LEFT OUTER JOIN data d2 ON (d2.id_param = p.id AND (d.datetime < d2.datetime OR d.datetime = d2.datetime AND d.id < d2.id)) ".
            "WHERE pg.id_instance = :id_instance ".
            "AND d.data <> \"\" ".
            "AND d2.id IS NULL ".
            "AND pg.header = ". ($header ? "1" : "0") ." ".
            "ORDER BY d.datetime DESC, p.title ASC");
        $query->param(':id_instance', $id_instance);
        return $query->execute()->as_array();
    }

    public function getInstanceParams($id_instance) {
        $query = DB::query(Database::SELECT,
            "SELECT p.id, p.title, p.alias, pt.title AS type, p.id_group, pg.title AS groupTitle, p.options ".
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
        // TODO alias UNIQUE
    }
}