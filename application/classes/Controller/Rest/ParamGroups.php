<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Rest_ParamGroups extends Controller_REST {

    protected $entityName = 'param_group';

    public function action_index() {
        $idInstance = $this->request->param('id_instance');
        $idGroup = $this->request->param('id');
        $addFields = $this->request->query('addFields');
        $filters = $this->request->query('filters');
        $mParam = new Model_Param();
        $mParamGroup = new Model_ParamGroup();

        if( ! empty($idInstance) && ! empty($idGroup)) {
            die();
//            $this->respond($mParamGroup->getInstanceParamGroups($idInstance));
        } else if( ! empty($idInstance) && empty($idGroup)) {
            $groups = null;
            if($filters == 'header:eq:0') {
                $groups = $mParamGroup->getInstanceGroupsWithoutHeader($idInstance);
            } else {
                $groups = $mParamGroup->getInstanceHeaderGroup($idInstance);
            }
            if($addFields == 'params,data') {
                for($i = 0; $i < count($groups); $i++) {
                    $groups[$i]['params'] = $mParam->getGroupParamsWithData($idInstance, $groups[$i]['id']);
                }
            }
            $this->respond($groups);
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
