<?php


class AnnouncementController extends ControllerBase
{
    const ANNOUNCEMENTS_PER_PAGE = 50;
    public function initialize()
    {
        parent::initialize();
        $this->view->current_page = 'announcement';
    }

    /**
     * Index action
     */
    public function indexAction($page = 1)
    {
        $page = intval($page);
        if ($page < 1) {
            $page = 1;
        }
        $builder = $this->modelsManager->createBuilder()
            ->from('Announcements')
            ->orderBy('start_at desc');

        $paginator = new Phalcon\Paginator\Adapter\QueryBuilder([
            'builder' => $builder,
            'limit' => self::ANNOUNCEMENTS_PER_PAGE,
            'page' => $page
        ]);
        $page = $paginator->getPaginate();

        $this->view->pagination = new Pagination($page, '/announcement/index');
        $this->view->announcements = $page->items;
    }

    public function createAction()
    {
        $announcement = new Announcements();
        $errors = [];
        if ($this->request->isPost()) {
            $announcement->load($_POST);
            $announcement->created_by = $this->current_user->id;

            if ($announcement->save()) {
                $this->flash->success('New Announcement created !');

                return $this->forward('announcement');
            } else {
                $this->setDefault($announcement);
            }
        } else {
            $announcement->start_at = DateHelper::day_after(1) . ' 00:00:00';
            $announcement->end_at = DateHelper::day_after(7) . ' 00:00:00';
            $announcement->show_time = 1;
            $this->setDefault($announcement);
        }
        $this->view->announcement = $announcement;
        $this->view->form = new BForm($announcement, $errors);
    }

    /**
     * Saves a item edited
     *
     */
    public function updateAction($id)
    {
        $announcement = Announcements::findFirstById($id);
        if (!$announcement) {
            $this->flash->error('Announcement was not found');

            return $this->forward('announcement');
        }

        $this->setDefault($announcement);
        if ($this->request->isPost()) {
            $announcement->load($_POST);
            if ($announcement->save()) {
                $this->flash->success('Faq was updated successfully');

                return $this->forward('announcement');
            }
        }
        $this->setDefault($announcement);
        $this->view->announcement = $announcement;
        $this->view->form = new BForm($announcement);
    }
}
