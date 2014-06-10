<?php

class ItemshopController extends ControllerBase
{

    public function indexAction()
    {

    }

    public function changeStatusAction()
    {
        if ($this->request->isAjax()) {
            $this->view->disable();
            $item_shop_id = $this->request->getPost('item_shop_id', 'int');
            $status = $this->request->getPost('status', 'int');
            if ($status != ItemShops::STATUS_NORMAL && $status != ItemShops::STATUS_FORCE_SALE &&
                $status != ItemShops::STATUS_FORCE_NOT_SALE
            ) {
                $response = [
                    'status' => 'fail',
                    'message' => 'Status Invalid',
                ];
            } else {
                $item_shop = ItemShops::findFirst($item_shop_id);
                if ($item_shop || $this->current_user->canEditShop($item_shop->shop)) {
                    $item_shop->beForced($status);

                    $response = [
                        'status' => 'success',
                        'data' => [
                            'id' => $item_shop->id,
                            'name' => $item_shop->item->name,
                            'type_value' => $item_shop->item->getTypeValue(),
                            'price' => $item_shop->getSalePrice(),
                            'status_value' => $item_shop->printStatus(),
                            'start_sale_date' => $item_shop->start_sale_date ? $item_shop->start_sale_date : '',
                            'end_sale_date' => $item_shop->end_sale_date ? $item_shop->end_sale_date : '',
                            'created_at' => $item_shop->created_at ? $item_shop->created_at : '',
                            'updated_at' => $item_shop->updated_at,
                            'btn_group' => $item_shop->printActionButtonGroup(),
                            'is_on_sale' => $item_shop->isOnSale(),
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
        $item_shop = ItemShops::findFirst($id);
        if (!$item_shop || !$this->current_user->canEditShop($item_shop->shop)) {
            return $this->forward('index/notfound');
        }

        if ($this->request->isPost()) {
            $item_shop->load($_POST);
            if ($item_shop->save()) {
                $this->flash->success('item was updated successfully');
            }
        }
        $this->setDefault($item_shop);
        $this->view->item_shop = $item_shop;
        $this->view->form = new BForm($item_shop);
    }
}

