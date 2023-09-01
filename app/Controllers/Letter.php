<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\Letter as LetterModel;

class Letter extends ResourceController
{
    protected $model;
    public function __construct()
    {
        $this->model = new LetterModel();
    }
    
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $letters = $this->model->findAll();
        return $this->respond($letters);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $letters = $this->model->find($id)->first();
        return $this->respond($letters);
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $rules = ['file'=>'required',
                 'no_surat'=>'required',
                'tanggal'=>'required',
                'asal'=>'oneway[tujuan]',
                'tujuan'=>'oneway[asal]'];
        $errors = [
            'file'=>['required'=>'Silahkan sertakan file terkait'],
            'no_surat'=>['required'=>'Silahkan sertakan nomor surat'],
            'tanggal'=>['required'=>'Silahkan sertakan tanggal surat'],
            'asal'=>['oneway'=>'hanya sertakan jika surat masuk'],
            'no_surat'=>['oneway'=>'Hanya sertakan jika surat keluar'],
        ];
        if(!$this->validate($rules,$errors)) return $this->validator->getErrors();
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
        //
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        //
    }
}
