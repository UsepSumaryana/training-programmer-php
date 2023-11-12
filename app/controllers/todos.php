<?php

use App\Controllers\Controller;
use App\Model\Todos as TodosModel;
use App\Model\Attachment;

class Todos extends Controller
{

    public function index()
    {

        $userId = $this->checkToken();

        $todosModel = new TodosModel();

        $todos = $todosModel->getUserTodos($userId);

        $this->view('todos', $todos);
    }

    public function create()
    {
        $userId = $this->checkToken();

        if(isset($_POST['submit'])) {
            $result = $this->createAction($userId);
            $this->view('todosCreate', $result);
        }

        $this->view('todosCreate');
    }

    public function edit($id)
    {
        $userId = $this->checkToken();

        if(isset($_POST['submit'])) {
            $result = $this->editAction($id);
            $this->view('todosCreate', $result);
        }

        $todos = new TodosModel();

        $todos->getById($id);

        $data = [
            'id' => $id,
            'title' => $todos->title,
            'description' => $todos->description,
            'dueDate' => $todos->dueDate
        ];

        $this->view('todosCreate', $data);
    }

    public function createAction($userId)
    {
        $attachment = new Attachment();
        $todos = new TodosModel();

        $title = $_POST['title'] ?? '';
        $desc = $_POST['description'] ?? '';
        $dueDate = $_POST['dueDate'] ?? '';
        $files = $_FILES['attachment'] ?? '';

        if (empty($_POST['title'])) {
            $errors[] = 'Title is required';
        }

        if (strlen($_POST['title']) > 100) {
            $errors[] = 'Title max 100 characters';
        }

        if (empty($_POST['dueDate'])) {
            $errors[] = 'Due Date is required';
        }

        if (!empty($files['name'])) {
            if (!$attachment->validateFile($files)) {
                $errors[] = $attachment->errMsg;
            } else {
                $attachment->upload($files);
            }
        }

        if (!empty($errors)) {
            return [
                'errors' => $errors,
                'title' => $title,
                'description' => $desc,
                'dueDate' => $dueDate
            ];
        }

        $todos->title = $title;
        $todos->description = $desc;
        $todos->userId = (int)$userId;
        $todos->dueDate = $dueDate;
        $todos->attachment = $attachment->fileName;

        $todos->save();

        header('location:../todos');
    }

    public function editAction($todoId)
    {
        $attachment = new Attachment();
        $todos = new TodosModel();

        $title = $_POST['title'] ?? '';
        $desc = $_POST['description'] ?? '';
        $dueDate = $_POST['dueDate'] ?? '';
        $files = $_FILES['attachment'] ?? '';

        $todos->getById($todoId);

        if (empty($_POST['title'])) {
            $errors[] = 'Title is required';
        }

        if (strlen($_POST['title']) > 100) {
            $errors[] = 'Title max 100 characters';
        }

        if (empty($_POST['dueDate'])) {
            $errors[] = 'Due Date is required';
        }

        if (!empty($files['name'])) {
            $attachment->deleteFile($todos->attachment);

            if (!$attachment->validateFile($files)) {
                $errors[] = $attachment->errMsg;
            } else {
                $attachment->upload($files);
                $todos->attachment = $attachment->fileName;
            }
        }

        if(!empty($errors)) {
            return [
                'errors' => $errors,
                'title' => $title,
                'description' => $desc,
                'dueDate' => $dueDate
            ];
        }
        
        $todos->title = $title;
        $todos->description = $desc;
        $todos->dueDate = $dueDate;

        $todos->save($todoId);

        header('location:../');
    }

    public function delete($id)
    {

        $todos = new TodosModel();
        $attachment = new Attachment();

        $todos->getById($id);
        $attachment->deleteFile($todos->attachment);
        $todos->delete($id);
        

        header('location:..');
    }

    public function setDone($id)
    {

        $todos = new TodosModel();

        $todos->getById($id);
        $todos->status = 'completed';
        
        $todos->save($id);

        header('location:..');
    }
}
