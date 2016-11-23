<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_WaterTest {
    public function getWaterTest($id) {
        $query = DB::select(
            'id',
            'id_instance',
            'datetime',
            'ph',
            'ammonia',
            'nitrite',
            'nitrate'
        )->from('water_test')->where('id', '=', $id);
        return $query->execute()->current();
    }

    public function getLastWaterTest($idInstance) {
        $query = DB::select(
            'id',
            'id_instance',
            'datetime',
            'ph',
            'ammonia',
            'nitrite',
            'nitrate'
        )->from('water_test')->where('id_instance', '=', $idInstance)->order_by('datetime', 'DESC')->limit(1)->offset(0);
        return $query->execute()->current();
    }

    public function getWaterTests($idInstance) {
        $query = DB::select(
            'id',
            'id_instance',
            'datetime',
            'ph',
            'ammonia',
            'nitrite',
            'nitrate'
        )->from('water_test')->where('id_instance', '=', $idInstance)->order_by('datetime', 'DESC');
        return $query->execute()->as_array();
    }

    function insertWaterTest($id_instance, $data) {
        $return = array();
        $validation = Validation::factory($data);
        $validation->rule('datetime', 'date');
        $validation->rule('ph', 'decimal', array(':value', '1'));
        $validation->rule('ph', 'range', array(':value', 1.0, 14.0));
        $validation->rule('ammonia', 'decimal', array(':value', '1'));
        $validation->rule('nitrite', 'decimal', array(':value', '1'));
        $validation->rule('nitrate', 'numeric');

        if( ! isset($data['ph']) ) {
            $data['ph'] = NULL;
        }
        if( ! isset($data['ammonia']) ) {
            $data['ammonia'] = NULL;
        }
        if( ! isset($data['nitrite']) ) {
            $data['nitrite'] = NULL;
        }
        if( ! isset($data['nitrate']) ) {
            $data['nitrate'] = NULL;
        }

        if( $validation->check() ) {
            $query = DB::insert('water_test', array(
                'id',
                'id_instance',
                'datetime',
                'ph',
                'ammonia',
                'nitrite',
                'nitrate'
            ))->values( array(
                DB::expr("UUID()"),
                $id_instance,
                $data['datetime'],
                $data['ph'],
                $data['ammonia'],
                $data['nitrite'],
                $data['nitrate']
            ) );
            $query->execute();

            $return['success'] = true;
        } else {
            $return['success'] = false;
            $return['errors'] = $validation->errors('water_test');
        }
        return $return;
    }

    public function updateWaterTest($data) {
        $return = array();
        $validation = Validation::factory($data);
        $validation->rule('datetime', 'date');
        $validation->rule('ph', 'decimal', array(':value', '1'));
        $validation->rule('ph', 'range', array(':value', 1, 14));
        $validation->rule('ammonia', 'decimal', array(':value', '1'));
        $validation->rule('nitrite', 'decimal', array(':value', '1'));
        $validation->rule('nitrate', 'decimal', array(':value', '1'));

        if( $validation->check() ) {

            $query = DB::update('water_test')->set(array(
                'datetime' => $data['datetime'],
                'ph' => $data['ph'],
                'ammonia' => $data['ammonia'],
                'nitrite' => $data['nitrite'],
                'nitrate' => $data['nitrate']
            ))->where('id', '=', $data['id']);
            $query->execute();

            $return['success'] = true;
            $return['entities'] = $this->getWaterTest($data['id']);
        } else {
            $return['success'] = false;
            $return['errors'] = $validation->errors('todo');
        }
        return $return;
    }
}