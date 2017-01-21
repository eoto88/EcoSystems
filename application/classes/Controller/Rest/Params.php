<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Rest_Params extends Controller_REST {

    protected $entityName = 'param';

    public function action_index() {
        $idInstance = $this->request->param('id_instance');
        $idParam = $this->request->param('id'); // *****
        $mParam = new Model_Param();

        if( isset($idInstance) && isset($idParam) ) {
            $this->respond($mParam->getInstanceParam($idInstance, $idParam));
        } else if( isset($idInstance) ) {
            if($this->request->query('header') == "false") {
                $this->respond($mParam->getGroupParamsWithData($idInstance, false));
            } else {
                $this->respond($mParam->getInstanceParams($idInstance));
            }
        } else {
            // json_encode($result)
            $this->response->status(406)->body();
        }
    }

    public function action_update() {
        die();
    }

    public function action_create() {
        die();
    }

    public function action_delete() {
        die();
    }
}
