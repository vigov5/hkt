<?php

class ItemuserController extends ControllerBase
{

    public function indexAction()
    {

    }

    public function changeStatusAction()
    {
        if ($this->request->isAjax()) {
            $this->view->disable();
            $item_user_id = $this->request->getPost('item_user_id', 'int');
            $status = $this->request->getPost('status', 'int');
            if ($status != ItemUsers::STATUS_NORMAL && $status != ItemUsers::STATUS_FORCE_SALE &&
                $status != ItemUsers::STATUS_FORCE_NOT_SALE
            ) {
                $response = [
                    'status' => 'fail',
                    'message' => 'Status Invalid',
                ];
            } else {
                $item_user = ItemUsers::findFirst($item_user_id);
                if ($item_user || $this->current_user->canEditItemuser($item_user)) {
                    $item_user->beForced($status);

                    $response = [
                        'status' => 'success',
                        'data' => [
                            'id' => $item_user->id,
                            'name' => $item_user->item->name,
                            'type_value' => $item_user->item->getTypeValue(),
                            'price' => $item_user->getSalePrice(),
                            'status_value' => $item_user->printStatus(),
                            'start_sale_date' => $item_user->start_sale_date ? $item_user->start_sale_date : '',
                            'end_sale_date' => $item_user->end_sale_date ? $item_user->end_sale_date : '',
                            'created_at' => $item_user->created_at ? $item_user->created_at : '',
                            'updated_at' => $item_user->updated_at,
                            'btn_group' => $item_user->printActionButtonGroup(),
                            'is_on_sale' => $item_user->isOnSale(),
                        ]
                    ];
                } else {
                    $response = [
                        'status' => 'fail',
                        'message' => 'Authorization Failed',
                    ];
                }
            }
            echo json_encode($response);
        }

        return;
    }

    public function updateAction($id)
    {
        $item_user = ItemUsers::findFirst($id);
        if (!$item_user || !$this->current_user->canEditItemuser($item_user)) {
            return $this->forward('index/notfound');
        }

        if ($this->request->isPost()) {
            $item_user->load($_POST);
            if ($item_user->save()) {
                $this->flash->success('item was updated successfully');
            }
        }
        $this->setDefault($item_user);
        $this->view->item_user = $item_user;
        $this->view->form = new BForm($item_user);
    }
}

