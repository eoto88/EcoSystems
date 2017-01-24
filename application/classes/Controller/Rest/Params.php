<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Rest_Params extends Controller_REST {

    protected $entityName = 'param';

    public function action_index() {
        $idInstance = $this->request->param('id_instance');
        $idParam = $this->request->param('id');
        $mParam = new Model_Param();
        $mPGroup = new Model_ParamGroup();

        if( isset($idInstance) && isset($idParam) ) {
            $this->respond($mParam->getInstanceParam($idInstance, $idParam));
        } else if( isset($idInstance) ) {
            if($this->request->query('header') == "false") {
                $groups = $mPGroup->getInstanceGroupsWithoutHeader($idInstance);
                for($i = 0; $i < count($groups); $i++) {
                    $groups[$i]['params'] = $mParam->getGroupParamsWithData($idInstance, $groups[$i]['id']);
                }
                $this->respond($groups);

            } else {
                $params = $mParam->getHeaderParamsWithData($idInstance);
                $this->respond($params);
            }
        } else {
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
