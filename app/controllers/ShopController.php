<?php

class ShopController extends ControllerBase
{

    const SHOPS_PER_PAGE = 6;

    public function initialize()
    {
        parent::initialize();
        $this->view->current_page = 'shop';
    }

    public function myAction()
    {
        $this->view->own_shops = $this->current_user->ownShops;
        $this->view->shops = $this->current_user->shops;
        $this->view->current_page = 'user';
    }

    public function openAction($page = 1)
    {
        $page = intval($page);
        if ($page < 1) {
            $page = 1;
        }
        $shops = Shops::getAllOpenShops();

        $paginator = new \Phalcon\Paginator\Adapter\Model([
                'data' => $shops,
                'limit' => self::SHOPS_PER_PAGE,
                'page' => $page,
            ]
        );

        $page = $paginator->getPaginate();
        $this->view->pagination = new Pagination($page, '/shop/open');
        $this->view->shops = $page->items;
    }

    /**
     * An Item view page
     * @param $id
     * @return mixed Forward to index if shop is not found
     */
    public function viewAction($id)
    {
        $id = intval($id);
        $shop = Shops::findFirstByid($id);
        if (!$shop) {
            $this->flash->error('shop was not found');

            return $this->forward('shop');
        }
        $this->view->shop = $shop;
        $this->view->item_shops = $shop->getAllOnSaleItems();
        $this->view->invoices = $shop->getInvoicesByDay();

        $this->setPrevUrl("shop/view/$id");
    }

    /**
     * Creates a new shop
     */
    public function createAction()
    {
        if (!$this->current_user) {
            $this->response->redirect();
        }
        $shop = new Shops();
        $errors = [];
        if ($this->request->isPost()) {
            $shop->load($_POST);
            $shop->created_by = $this->current_user->id;
            if (isset($_FILES['img_upload'])) {
                $file = $_FILES['img_upload'];
                $path = Config::getFullImageUploadDir() . $file['name'];
                $fh = new FileHelper($path);
                if ($fh->uploadImage($file)) {
                    $shop->img = Config::IMG_UPLOAD_DIR . $fh->getBasename();
                }
            }

            if ($shop->save()) {
                $request = $this->current_user->createNewShopRequest($shop);
                if ($this->current_user->isRoleOver(Users::ROLE_MODERATOR)) {
                    $request->beAccepted($this->current_user->id);
                }
                $this->flash->success('Shop was created successfully');

                return $this->forward('shop/view', ['id' => $shop->id]);
            } else {
                $this->setDefault($shop);
            }
        }
        $this->view->shop = $shop;
        $this->view->form = new BForm($shop, $errors);
    }

    /**
     * Saves a shop edited
     *
     */
    public function updateAction($id)
    {
        $shop = Shops::findFirstByid($id);
        if (!$shop || !$this->current_user->canEditShop($shop)) {
            $this->flash->error('Shop Unavailable');

            return $this->forward('shop');
        }

        $this->setDefault($shop);
        if ($this->request->isPost()) {
            $shop->load($_POST);
            if (isset($_FILES['img_upload'])) {
                $file = $_FILES['img_upload'];
                $path = Config::getFullImageUploadDir() . $file['name'];
                $fh = new FileHelper($path);
                if ($fh->uploadImage($file)) {
                    $shop->img = Config::IMG_UPLOAD_DIR . $fh->getBasename();
                }
            }
            if ($shop->save()) {
                $this->flash->success('Shop was updated successfully');

                return $this->forward('shop/view', ['id' => $shop->id]);
            } else {
                $this->flash->error('Something went wrong when create shop');
            }
        }
        $this->setDefault($shop);
        $this->view->shop = $shop;
        $this->view->form = new BForm($shop);
    }

    public function changeStatusAction($shop_id, $status)
    {
        $shop = Shops::findFirstByid($shop_id);
        if (!$shop || !$this->current_user->canEditShop($shop)) {
            $this->flash->error('Shop Unavailable');
            return $this->forward('shop');
        }

        if (!Shops::isValidStatus($status)) {
            $this->flash->error('Invalid Status');
            return $this->forward('shop');
        }

        $shop->changeStatus($status);
        $this->flash->success("{$shop->name} has been change to {$shop->getStatusValue()}");
        return $this->forward('shop/view', ['id' => $shop->id]);
    }

    public function requestAction()
    {
        if ($this->request->isAjax()) {
            $this->view->disable();
            $shop_id = $this->request->getPost('shop_id', 'int');
            $shop = Shops::findFirstById($shop_id);
            if (!$shop || $shop->checkOwnerOrStaff($this->current_user)) {
                $response = [
                    'status' => 'fail',
                    'message' => 'Request Invalid',
                ];
            } else {
                if ($this->current_user->createRequest(Requests::TYPE_SHOP_STAFF, null, $shop->created_by, $shop_id)) {
                    $response = [
                        'status' => 'success',
                        'message' => 'Request Created',
                    ];
                } else {
                    $response = [
                        'status' => 'fail',
                        'message' => 'Something went wrong',
                    ];
                }
            }
            echo json_encode($response);
        }

        return;
    }

    public function buyAction()
    {
        if ($this->request->isAjax()) {
            $status = true;
            $this->view->disable();
            $item_shop_id = $this->request->getPost('item_shop_id', 'int');
            $item_sets_str = $this->request->getPost('item_sets');
            $item_sets = json_decode($item_sets_str);
            $amount = $this->request->getPost('amount', 'int');
            $item_shop = ItemShops::findFirstById($item_shop_id);
            $status = true;
            $message = '';

            if ($amount < 1 || !$item_shop) {
                $status = false;
                $message = 'Invalid Request';
            }

            foreach ($item_sets as $item_set) {
                if (!ItemShops::exists($item_set)) {
                    $status = false;
                    $message = 'Set invalid';
                    break;
                }
            }

            if ($status && !$item_shop->canBeBought()) {
                $status = false;
                $message = 'Invalid Item';
            }
            if ($status && !$this->current_user->checkWallet($item_shop->getSalePrice() * $amount)) {
                $status = false;
                $message = 'Not Enough Money';
            }
            if ($status) {
                $this->current_user->createInvoiceToShop($item_shop, $amount, $item_sets_str);
                $message = 'Request Created';
            }
            $wallet = $this->current_user->wallet;
            $response = [
                'status' => $status ? 'success' : 'fail',
                'message' => $message,
                'wallet' => $wallet,
            ];
            echo json_encode($response);
        }

        return;
    }

    public function invoicesAction($shop_id, $date = null)
    {
        if (!$date || !DateHelper::isValidDate($date)) {
            $date = DateHelper::today();
        }
        $shop = Shops::findFirstById($shop_id);
        if (!$shop || !$this->current_user->canEditShop($shop)) {
            return $this->forwardNotFound();
        }
        $this->view->date = $date;
        $this->view->shop = $shop;
        $this->view->invoices = $shop->getInvoicesByDay($date);
    }
}
