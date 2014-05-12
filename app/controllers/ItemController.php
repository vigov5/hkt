<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class ItemController extends ControllerBase
{

    const ITEM_PER_PAGE = 20;

    public function initialize()
    {
        parent::initialize();
        $this->view->current_page = 'item';
    }

    /**
     * Index action
     */
    public function indexAction()
    {
    }

    public function specialAction($type = Items::TYPE_DEPOSIT)
    {
        if ($type != Items::TYPE_DEPOSIT && $type != Items::TYPE_WITHDRAW) {
            $type = Items::TYPE_DEPOSIT;
        }
        $this->view->current_page = 'wallet';
        $item_users = ItemUsers::getOnSaleItems($type);
        $this->view->item_users = $item_users;
        $this->view->form = new BuyForm();
        $this->setPrevUrl("item/special/$type");
    }

    public function onsaleAction()
    {
        $item_users = ItemUsers::getOnSaleItems();
        $this->view->item_users = $item_users;
        $this->view->form = new BuyForm();
        $this->session->set('prev_url', $this->url->get("item/onsale"));
        $this->setPrevUrl("item/onsale");
    }

    /**
     * An Item view page
     * @param $id
     * @return mixed Forward to index if item is not found
     */
    public function viewAction($id)
    {
        $id = intval($id);
        $item = Items::findFirstByid($id);
        if (!$item) {
            $this->flash->error('item was not found');

            return $this->forward('item');
        }
        $today = date('Y-m-d 00:00:00');
        $tomorrow = date('Y-m-d 00:00:00', time() + 86400);
        $this->view->invoices = $item->getInvoices([
                'conditions' => "created_at > \"$today\" AND created_at < \"$tomorrow\"",
                'order' => 'created_at desc, to_user_id'
            ]
        );
        $this->view->item = $item;
        $this->setPrevUrl("item/view/$id");
    }

    /**
     * Creates a new item
     * @param int|null $shop_id
     * @return mixed Forward if something went wrong
     */
    public function createAction($shop_id = null)
    {
        if (!$this->current_user) {
            $this->response->redirect();
        }
        $shop = null;
        if ($shop_id) {
            $shop = Shops::findFirstById($shop_id);
            if (!$shop || !$shop->checkOwnerOrStaff($this->current_user)) {
                $this->flash->success('Invalid Shop');
                return $this->forward('shop/open');
            }
        }
        $item = new Items();
        $errors = [];
        if ($this->request->isPost()) {
            $item->load($_POST);
            $item->created_by = $this->current_user->id;
            if (isset($_FILES['img_upload'])) {
                $file = $_FILES['img_upload'];
                $path = Config::getFullImageUploadDir() . $file['name'];
                $fh = new FileHelper($path);
                if ($fh->uploadImage($file)) {
                    $item->img = Config::IMG_UPLOAD_DIR . $fh->getBasename();
                }
            }

            if ($item->save()) {
                $request1 = $this->current_user->createNewItemRequest($item);
                if ($shop) {
                    $request2 = $this->current_user->createShopSellItemRequest($item, $shop);
                } else {
                    $request2 = $this->current_user->createSellItemRequest($item);
                }

                if ($this->current_user->canAccessNoDestinationRequests()) {
                    $request1->beAccepted($this->current_user->id);
                    $request2->beAccepted($this->current_user->id);
                }
                $this->flash->success('item was created successfully');

                return $this->forward('item/view', ['id' => $item->id]);
            } else {
                $this->setDefault($item);
            }
        }
        $this->view->link = $shop ? "item/create/{$shop->id}" : 'item/create';
        $this->view->item = $item;
        $this->view->form = new BForm($item, $errors);
    }

    /**
     * Saves a item edited
     *
     */
    public function updateAction($id)
    {
        $item = Items::findFirstByid($id);
        if (!$item) {
            $this->flash->error('item was not found');

            return $this->forward('item');
        }

        if (!$this->current_user->canEditItem($item)) {
            return $this->forward('index/notFound');
        }
        $this->setDefault($item);
        if ($this->request->isPost()) {
            $item->load($_POST);
            if (isset($_FILES['img_upload'])) {
                $file = $_FILES['img_upload'];
                $path = Config::getFullImageUploadDir() . $file['name'];
                $fh = new FileHelper($path);
                if ($fh->uploadImage($file)) {
                    $item->img = Config::IMG_UPLOAD_DIR . $fh->getBasename();
                }
            }
            if ($item->save()) {
                $this->flash->success('item was updated successfully');

                return $this->forward('item/view', ['id' => $item->id]);
            }
        }
        $this->setDefault($item);
        $this->view->item = $item;
        $this->view->form = new BForm($item);
    }

    public function buyAction()
    {
        if (!$this->request->isPost()) {
            return $this->response->redirect('item');
        }
        $item_user_id = $this->request->get('item_user_id', 'int');
        $item_id = $this->request->get('items', 'int');
        $amount = $this->request->get('amount');
        if (!$amount || !is_numeric($amount) || $amount < 1) {
            $amount = 1;
        }
        $item_user = ItemUsers::findFirstByid($item_user_id);
        if (!$item_user) {
            $this->setFlashSession('error', 'Item does not exist');
            $this->redirectToPrevUrl();
            return;
        }

        if ($item_user->item_id != $item_id) {
            $this->setFlashSession('error', 'Data Invalid');
            $this->redirectToPrevUrl();
            return;
        }

        if (!$item_user->isOnSale()) {
            $this->setFlashSession('error', 'Item is not on sale');
            $this->redirectToPrevUrl();
            return;
        }
        if (!$item_user->item || !$item_user->item->isAvailable()) {
            $this->setFlashSession('error', 'Item is currently unavailable');
            $this->redirectToPrevUrl();
            return;
        }
        if (!$this->current_user->checkWallet($amount * $item_user->getSalePrice())) {
            $this->setFlashSession('error', 'Sorry !!! You do not have enough money !!!');
            $this->redirectToPrevUrl();
            return;
        }

        if ($this->current_user->createInvoiceToUser($item_user, $amount)) {
            $this->setFlashSession('success', 'Invoice created successfully !!!');
        } else {
            $this->setFlashSession('error',
                'An error occured when trying to create invoice! Please contact the administrator !!!'
            );
        };
        $this->redirectToPrevUrl();
    }

    public function myAction()
    {
        $this->view->item_users = $this->current_user->itemUsers;
    }

    public function shopAction($shop_id)
    {
        $shop = Shops::findFirstById($shop_id);
        if (!$shop || !$shop->checkOwnerOrStaff($this->current_user)) {
            return $this->forward('index/notFound');
        }

        $this->view->shop = $shop;
        $this->view->item_shops = $shop->itemShops;
    }

    public function requestAction()
    {
        if ($this->request->isAjax()) {
            $this->view->disable();
            $item_id = $this->request->getPost('item_id', 'int');
            $item = Items::findFirstById($item_id);
            if (!$item || !$this->current_user->canCreateBuyItemRequest($item)) {
                $response = [
                    'status' => 'fail',
                    'message' => 'Invalid Item',
                ];
            } else {
                $this->current_user->createSellItemRequest($item);
                $response = [
                    'status' => 'success',
                ];
            }
            echo json_encode($response);
        }
        return ;
    }

    public function availableAction($page = 1)
    {
        $page = intval($page);
        if ($page < 1) {
            $page = 1;
        }
        $builder = $this->modelsManager->createBuilder()
            ->from('Items')
            ->where('status =' . Items::STATUS_AVAILABLE)
            ->andWhere('type = ' . Items::TYPE_NORMAL)
            ->orderBy('id desc');

        $paginator = new Phalcon\Paginator\Adapter\QueryBuilder([
            'builder' => $builder,
            'limit' => self::ITEM_PER_PAGE,
            'page' => $page
        ]);
        $page = $paginator->getPaginate();
        $this->view->pagination = new Pagination($page, '/item/available');
        $this->view->items = $page->items;
    }
}
