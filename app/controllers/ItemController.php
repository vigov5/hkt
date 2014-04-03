<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class ItemController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
        $this->view->items = Item::find();
    }

    /**
     * An Item view page
     * @param $id
     * @return mixed Forward to index if item is not found
     */
    public function viewAction($id)
    {
        $item = Item::findFirstByid($id);
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
            $parameters = array();
        }
        $parameters['order'] = 'id';

        $item = Item::find($parameters);
        if (count($item) == 0) {
            $this->flash->notice('The search did not find any item');

            return $this->forward('item');
        }

        $paginator = new Paginator(array(
            'data' => $item,
            'limit' => 10,
            'page' => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Creates a new item
     */
    public function createAction()
    {
        $item = new Item();

        if ($this->request->isPost()) {
            $item->save_attributes = $_POST;
            //$item->load($_POST);                        

            if ($item->save()) {
                $this->flash->success('item was created successfully');
                return $this->forward('item/view', ['id' => $item->id]);
            } else {    
                $this->setDefault($item);
            }
        }
        $this->view->item = $item;
        $this->view->form = new BForm($item);
    }

    /**
     * Saves a item edited
     *
     */
    public function updateAction($id)
    {
        $item = Item::findFirstByid($id);
        if (!$item) {
            $this->flash->error('item was not found');
            return $this->forward('item');
        }

        $this->setDefault($item);
        if ($this->request->isPost()) {
            $item->load($_POST);
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
        $item = Item::findFirstByid($id);
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

}
