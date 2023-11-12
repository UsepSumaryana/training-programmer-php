<?php
use App\HTTPAuth;
use App\Controllers\Controller;
use App\Model\AttachmentBase64;
use App\Model\Todos;

class Api extends Controller
{
    public function __construct()
    {
        $auth = new HTTPAuth();
        $auth->authenticate();
    }

    public function todos()
    {

        $todosModel = new Todos();

        $data = $this->getRequest();
        $userId = $data['user_id'] ?? '';

        $paramToValidate = [
            'user_id',
        ];

        $resValidate = $this->validateRequest($data, $paramToValidate);

        if (!$resValidate) {
            $this->sendResponse(401, $this->errMsg, []);
        }

        $todos = $todosModel->getUserTodos($userId);
        
        $this->sendResponse(200, 'success', $todos);

    }

    public function createTodos()
    {
        $todos = new Todos();
        $attachment = new AttachmentBase64();

        $data = $this->getRequest();
        $title = $data['title'] ?? '';
        $description = $data['description'] ?? '';
        $dueDate = $data['due_date'] ?? '';
        $userId = $data['user_id'] ?? '';
        $base64 = $data['attachment'] ?? '';

        $paramToValidate = [
            'title',
            'description',
            'due_date',
            'user_id',
        ];

        $resValidate = $this->validateRequest($data, $paramToValidate);

        if (!$resValidate) {
            $this->sendResponse(401, $this->errMsg, []);
        }

        $attachment->validateFile($base64);
        $attachment->upload($base64);

        $todos->title = $title;
        $todos->description = $description;
        $todos->dueDate = $dueDate;
        $todos->userId = $userId;
        $todos->attachment = $attachment->fileName;
        $todos->save();
        $this->sendResponse(200, 'success', []);
    }

    public function editTodos()
    {
        $todos = new Todos();
        $attachment = new AttachmentBase64();

        $data = $this->getRequest();
        $id = $data['id'] ?? '';
        $title = $data['title'] ?? '';
        $description = $data['description'] ?? '';
        $dueDate = $data['due_date'] ?? '';
        $userId = $data['user_id'] ?? '';
        $base64 = $data['attachment'] ?? '';

        $paramToValidate = [
            'id',
            'description',
            'due_date',
            'user_id',
        ];

        $resValidate = $this->validateRequest($data, $paramToValidate);
        if (!$resValidate) {
            $this->sendResponse(401, $this->errMsg, []);
        }

        $todos->getById($id);

        if (!empty($base64)) {
            $attachment->deleteFile($todos->attachment);
            $attachment->validateFile($base64);
            $attachment->upload($base64);
        }

        $todos->title = $title;
        $todos->description = $description;
        $todos->dueDate = $dueDate;
        $todos->userId = $userId;
        $todos->attachment = $attachment->fileName;
        $todos->save($id);
        $this->sendResponse(200, 'success', []);
    }

    public function deleteTodos()
    {
        $todos = new Todos();
        $data = $this->getRequest();
        $id = $data['id'] ?? '';

        $paramToValidate = [
            'id',
        ];

        $resValidate = $this->validateRequest($data, $paramToValidate);
        if (!$resValidate) {
            $this->sendResponse(401, $this->errMsg, []);
        }

        $todos->delete($id);
        $this->sendResponse(200, 'success', []);
    }
}