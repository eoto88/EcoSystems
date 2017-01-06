<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_ParamType {

    public function getParamTypes() {
        $query = DB::select('*')
                   ->from('param_type')
                   ->order_by('title', 'ASC');
        return $query->execute()->as_array();
    }
}