<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class ProjectsController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for projects
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Projects', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $projects = Projects::find($parameters);
        if (count($projects) == 0) {
            $this->flash->notice("The search did not find any projects");

            $this->dispatcher->forward([
                "controller" => "projects",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $projects,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a project
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $project = Projects::findFirstByid($id);
            if (!$project) {
                $this->flash->error("project was not found");

                $this->dispatcher->forward([
                    'controller' => "projects",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $project->id;

            $this->tag->setDefault("id", $project->id);
            $this->tag->setDefault("name", $project->name);
            $this->tag->setDefault("description", $project->description);
            $this->tag->setDefault("starts", $project->starts);
            $this->tag->setDefault("created", $project->created);
            $this->tag->setDefault("updated", $project->updated);
            
        }
    }

    /**
     * Creates a new project
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "projects",
                'action' => 'index'
            ]);

            return;
        }

        $project = new Projects();
        $project->name = $this->request->getPost("name");
        $project->description = $this->request->getPost("description");
        $project->starts = $this->request->getPost("starts");
        $project->created = $this->request->getPost("created");
        $project->updated = $this->request->getPost("updated");
        

        if (!$project->save()) {
            foreach ($project->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "projects",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("project was created successfully");

        $this->dispatcher->forward([
            'controller' => "projects",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a project edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "projects",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $project = Projects::findFirstByid($id);

        if (!$project) {
            $this->flash->error("project does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "projects",
                'action' => 'index'
            ]);

            return;
        }

        $project->name = $this->request->getPost("name");
        $project->description = $this->request->getPost("description");
        $project->starts = $this->request->getPost("starts");
        $project->created = $this->request->getPost("created");
        $project->updated = $this->request->getPost("updated");
        

        if (!$project->save()) {

            foreach ($project->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "projects",
                'action' => 'edit',
                'params' => [$project->id]
            ]);

            return;
        }

        $this->flash->success("project was updated successfully");

        $this->dispatcher->forward([
            'controller' => "projects",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a project
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $project = Projects::findFirstByid($id);
        if (!$project) {
            $this->flash->error("project was not found");

            $this->dispatcher->forward([
                'controller' => "projects",
                'action' => 'index'
            ]);

            return;
        }

        if (!$project->delete()) {

            foreach ($project->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "projects",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("project was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "projects",
            'action' => "index"
        ]);
    }

}
