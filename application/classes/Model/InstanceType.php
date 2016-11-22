<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_InstanceType {

    public function getInstanceType($idInstanceType) {
        $query = DB::select('*')->from('instance_type')->where('id', '=', $idInstanceType);
        return $query->execute()->current();
    }

    public function getInstanceTypes() {
        $query = DB::select('*')->from('instance_type');
        $types = $query->execute()->as_array();

        $newArray = array();
        foreach ($types as $type) {
            $newArray[$type['id']] = $type['title'];
        }

        return $newArray;
    }
}