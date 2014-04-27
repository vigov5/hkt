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
    public function indexAction($type=Items::TYPE_DEPOSIT)
    {
        if ($type != Items::TYPE_DEPOSIT && $type != Items::TYPE_WITHDRAW) {
            $type = Items::TYPE_DEPOSIT;
        }
        $this->view->current_page = 'wallet';
        $items = Items::find(["type = $type"]);
        $this->view->items = $items;
        $this->view->form = new BuyForm();
    }

    public function onsaleAction()
    {
        $items = Items::getOnSaleItems();
        $this->view->items = $items;
        $this->view->form = new BuyForm();
    }

    /**
     * Index action
     */
    public function listAction($type=1)
    {
        //var_dump($this->security->getSessionToken());die();
        switch ($type) {
            case Items::TYPE_DEPOSIT:
            case Items::TYPE_WITHDRAW:
            case Items::TYPE_NORMAL:
                $items = Items::find(["type = $type"]);
                break;
            default:
                $items = Items::find(["type = 1"]);
        }
        $this->view->items = $items;
        $this->view->form = new BuyForm();
    }

    /**
     * An Item view page
     * @param $id
     * @return mixed Forward to index if item is not found
     */
    public function viewAction($id)
    {
        $item = Items::findFirstByid($id);
        if (!$item) {
            $this->flash->error('item was not found');
            return $this->forward('item');
        }
        $this->view->item = $item;
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
        $item_id = $this->request->get('items');
        $item = Items::findFirstByid($item_id);
        if (!$item) {
            $this->flash->error('Item does not exist');
            return $this->forward('item');
        }
        if (!$item->isOnSale()) {
            $this->flash->error('Item is not on sale');
            return $this->forward('item');
        }
        if (!$this->current_user->checkWallet($item->price)) {
            $this->flash->error('Sorry !!! You do not have enough money !!!');
            return $this->forward('item');
        }
        if ($this->current_user->createInvoice($item, 1)) {
            $this->flash->success('Invoice created successfully !!!');
        } else {
            $this->flash->error('An error occured when trying to create invoice! Please contact the administrator !!!');
        };
        $this->forward('item');
    }
}
