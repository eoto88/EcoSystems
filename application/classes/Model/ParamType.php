<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_ParamType {
    public function getParamType($id) {
        $query = DB::select('*')
            ->from('param_type')
            ->where('id', '=', $id);
        return $query->execute()->as_array();
    }

    public function getParamTypes() {
        $query = DB::select('*')
                   ->from('param_type')
                   ->order_by('title', 'ASC');
        return $query->execute()->as_array();
    }
}