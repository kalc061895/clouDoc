<?php

namespace App\Controllers;

use App\Models\FileUploadControlModel;

class FileUploadControlController extends BaseController
{
    protected $fileUploadControlModel;

    public function __construct()
    {
        $this->fileUploadControlModel = new FileUploadControlModel();
    }

    public function index()
    {
        $data['folders'] = $this->fileUploadControlModel->findAll();
        return view('file_upload_control/index', $data);
    }

    public function save()
    {
        $data = [
            'folder_name' => $this->request->getPost('folder_name'),
            'is_active' => $this->request->getPost('is_active'),
        ];

        if ($this->request->getPost('id')) {
            // Update
            $id = $this->request->getPost('id');
            $this->fileUploadControlModel->update($id, $data);
        } else {
            // Insert
            $this->fileUploadControlModel->save($data);
        }

        return redirect()->to('/file_upload_control');
    }

    public function delete($id)
    {
        $this->fileUploadControlModel->delete($id);
        return redirect()->to('/file_upload_control');
    }
}
