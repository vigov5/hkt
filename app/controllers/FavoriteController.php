<?php

class FavoriteController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
        $this->view->current_page = 'favorite';
    }

    public function indexAction()
    {
        $favorites = $this->current_user->getFavoriteList();
        $this->view->favorites = $favorites;
    }

    public function changeNotificationAction()
    {
        if ($this->request->isAjax()) {
            $this->view->disable();
            $favorite_id = $this->request->getPost('favorite_id', 'int');
            $favorite = Favorites::findFirst($favorite_id);
            if ($favorite && $favorite->user_id = $this->current_user->id) {
                $favorite->changeNotification();
                $response = [
                    'status' => 'success',
                    'message' => 'Notification Status changed!',
                ];
            } else {
                $response = [
                    'status' => 'fail',
                    'message' => 'Invalid Favorite ID',
                ];
            }
            echo json_encode($response);
            return;
        }
        $this->forwardNotFound();
    }
}

