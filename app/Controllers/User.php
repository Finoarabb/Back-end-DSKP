<?php

namespace App\Controllers;

use App\Models\User as ModelsUser;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class User extends ResourceController
{
    protected $model;
    public function __construct()
    {
        $this->model = new ModelsUser();
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $users = $this->model->findAll();
        return ($this->respond($users));
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $users = $this->model->find($id);
        return ($this->respond($users));
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $rules = [
            'username' => 'required|is_unique[users.username]',
            'nama' => 'required',
            'password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]',
        ];
        $errors = [
            'username' => [
                'required' => 'Username tidak boleh kosong',
                'is_unique' => 'Username sudah digunakan',
            ],
            'nama' => [
                'required' => 'Nama tidak boleh kosong'
            ],
            'password' => [
                'required' => 'Password tidak boleh kosong',
                'min_length' => 'Password minimal harus terdiri dari {param} karakter'
            ],
            'confirm_password' => [
                'required' => 'Silahkan konfirmasi password terlebih dahulu',
                'matches' => 'Password tidak sama'
            ]
        ];
        if (!$this->validate($rules,$errors))  return $this->fail($this->validator->getErrors()); 
        $data = [
            'username'=> $this->request->getVar('username'),
            'password'=> $this->request->getVar('password'),
            'nama'=> $this->request->getVar('nama'),
        ];
        $user = $this->model->insert($data);
        if($user===false) return $this->fail('Insert gagal');
        return $this->respondCreated($this->model->find($user));
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $rules = ['role'=>'required|in_list[admin,supervisor,staff,operator]'];
        if(!$this->validate($rules)) return $this->fail('Invalid');       
        $role = $this->request->getJSON('role');
        
        $result = $this->model->update($id, ['role' => $role]);

    if ($result) {
        // Record was successfully updated
        return $this->respondUpdated(['message' => 'Role berhasil diubah']);
    } else {
        // Failed to update the record
        return $this->fail('Failed to update record.');
    }
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $data = $this->model->find($id);
        if(!$data) return $this->failNotFound('Data '.$id.' tidak ditemukan',404);
        try{

            $deleted = $this->model->delete($id);
            return $this->respondDeleted(['Msg'=>'Data '.$data["username"].' Berhasil dihapus']);
        } catch(Exception $e){
            return $this->fail('Gagal');
        }
    }
}
