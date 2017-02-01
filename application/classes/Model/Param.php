<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_Param {

    public function getParamByAlias($idInstance, $alias) {
        $query = DB::query(Database::SELECT,
               "SELECT p.id, p.id_group, pt.alias AS typeAlias, pt.data_type AS dataType ".
               "FROM param AS p ".
               "JOIN param_group AS pg ON pg.id = p.id_group ".
               "JOIN param_type AS pt ON pt.id = p.id_type ".
               "WHERE p.alias = :alias ".
               "AND pg.id_instance = :id_instance"
        );
        $query->param(':id_instance', $idInstance);
        $query->param(':alias', $alias);
        return $query->execute()->current();
    }

    public function getInstanceHeaderGroupParams($id_instance) {
        $query = DB::query(Database::SELECT,
            "SELECT p.id AS id_param, p.title, p.alias AS paramAlias, p.icon, pt.alias AS typeAlias, pt.title AS type, p.options ".
            "FROM param_group AS pg ".
            "JOIN param AS p ON p.id_group = pg.id ".
            "JOIN param_type AS pt ON pt.id = p.id_type ".
            "WHERE pg.id_instance = :id_instance ".
            "AND pg.header = 1"
        );
        $query->param(':id_instance', $id_instance);
        return $query->execute()->as_array();
    }

    public function getInstanceGroupParams($idInstance, $idGroup) {
        $query = DB::query(Database::SELECT,
            "SELECT p.id AS id_param, p.title, p.alias AS paramAlias, p.icon, pt.alias AS typeAlias, pt.title AS type, p.options ".
            "FROM param_group AS pg ".
            "JOIN param AS p ON p.id_group = pg.id ".
            "JOIN param_type AS pt ON pt.id = p.id_type ".
            "WHERE pg.id_instance = :id_instance ".
            "AND pg.id = :id_group"
        );
        $query->param(':id_instance', $idInstance);
        $query->param(':id_group', $idGroup);
        return $query->execute()->as_array();
    }

    public function getParamWithData($idParam) {
        $query = DB::query(Database::SELECT,
            "SELECT p.title, p.alias AS paramAlias, p.icon, pt.alias AS typeAlias, pt.title AS type, p.options, d.data, d.datetime ".
            "FROM param AS p ".
            "JOIN param_type AS pt ON pt.id = p.id_type ".
            "JOIN data AS d ON d.id_param = p.id ".
            "WHERE p.id = :id_param ".
            "AND d.data <> \"\" ".
            "ORDER BY d.datetime DESC ".
            "LIMIT 1");
        $query->param(':id_param', $idParam);
        return $query->execute()->as_array();
    }

    public function getHeaderParamsWithData($idInstance) {
        $mData = new Model_Data();
        $params = $this->getInstanceHeaderGroupParams($idInstance);
        for($j = 0; $j < count($params); $j++) {
            $data = $mData->getParamData($params[$j]['id_param'], 1);
            if($data) {
                $params[$j]['datetime'] = $data[0]['datetime'];
                $params[$j]['data'] = $data[0]['data'];
            }
        }
        return $params;
    }

    public function getGroupParamsWithData($idInstance, $idGroup) {
        $mData = new Model_Data();
        $params = $this->getInstanceGroupParams($idInstance, $idGroup);
        for($j = 0; $j < count($params); $j++) {
            $data = $mData->getParamData($params[$j]['id_param'], 1);
            if($data) {
                $params[$j]['datetime'] = $data[0]['datetime'];
                $params[$j]['data'] = $data[0]['data'];
            }
        }
        return $params;
    }

    public function getInstanceParams($idInstance) {
        // = addFields =
        // - type
        // - groupTitle
        // - dataType
        // - valueOptions

        $query = DB::query(Database::SELECT,
            "SELECT p.id, p.title, p.alias, pt.title AS type, p.id_group, pg.title AS groupTitle, pt.data_type AS dataType, pt.value_options AS valueOptions ".
            "FROM param AS p ".
            "LEFT JOIN param_type AS pt ON pt.id = p.id_type ".
            "LEFT JOIN param_group AS pg ON pg.id = p.id_group ".
            "WHERE pg.id_instance = :id_instance ".
            "ORDER BY p.title ASC"
        );
        $query->param(':id_instance', $idInstance);
        return $query->execute()->as_array();
    }

    public function getInstanceParam($idInstance, $idParam) {
        $query = DB::query(Database::SELECT,
            "SELECT p.id, p.title, p.alias, p.id_type, p.id_group, IF(p.options = \"\", pt.options, p.options) AS options ".
            "FROM param AS p ".
            "LEFT JOIN param_type AS pt ON pt.id = p.id_type ".
            "LEFT JOIN param_group AS pg ON pg.id = p.id_group ".
            "WHERE pg.id_instance = :id_instance ".
            "AND p.id = :id_param"
//            "ORDER BY pg.title ASC, p.title ASC"
        );
        $query->param(':id_instance', $idInstance);
        $query->param(':id_param', $idParam);
        return $query->execute()->as_array();
    }

    public function insertParam($data) {
        // TODO alias UNIQUE
    }
}