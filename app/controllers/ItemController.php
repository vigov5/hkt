<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class ItemController extends ControllerBase
{

    const ITEM_PER_PAGE = 5;

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

    public function specialAction($type=Items::TYPE_DEPOSIT)
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
        ]);
        $this->view->item = $item;
        $this->setPrevUrl("item/view/$id");
    }

    /**
     * Searches for item
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Item', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery('page', 'int');
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters['order'] = 'id';

        $item = Items::find($parameters);
        if (count($item) == 0) {
            $this->flash->notice('The search did not find any item');

            return $this->forward('item');
        }

        $paginator = new Paginator([
            'data' => $item,
            'limit' => 10,
            'page' => $numberPage,
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Creates a new item
     */
    public function createAction()
    {
        if (!$this->current_user) {
            $this->response->redirect();
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
                $this->flash->success('item was created successfully');
                return $this->forward('item/view', ['id' => $item->id]);
            } else {
                $this->setDefault($item);
            }
        }
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

    /**
     * Deletes a item
     *
     * @param string $id
     * @return mix Forward to item/index if there are any errors. Otherwise forward to item/search
     */
    public function deleteAction($id)
    {
        $item = Items::findFirstByid($id);
        if (!$item) {
            $this->flash->error('item was not found');

            return $this->forward('item');
        }

        if (!$item->delete()) {

            foreach ($item->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->forward('item/search');
        }

        $this->flash->success('item was deleted successfully');

        return $this->forward('item');
    }

    public function buyAction()
    {
        if (!$this->request->isPost()) {
            return $this->response->redirect('item');
        }
        $item_user_id = $this->request->get('items');
        $amount = $this->request->get('amount');
        if (!$amount || !is_numeric($amount) || $amount < 1) {
            $amount = 1;
        }
        $item_user = ItemUsers::findFirstByid($item_user_id);
        if (!$item_user) {
            $this->flash->error('Item does not exist');
            return $this->forward('item');
        }
        if (!$item_user->isOnSale()) {
            $this->flash->error('Item is not on sale');
            return $this->forward('item');
        }
        if (!$this->current_user->checkWallet($amount * $item_user->getSalePrice())) {
            $this->flash->error('Sorry !!! You do not have enough money !!!');
            return $this->forward('item');
        }
        if ($this->current_user->createInvoiceToUser($item_user, $amount)) {
            $this->setFlashSession('success', 'Invoice created successfully !!!');
        } else {
            $this->setFlashSession('error', 'An error occured when trying to create invoice! Please contact the administrator !!!');
        };
        $this->redirectToPrevUrl();
    }
}
