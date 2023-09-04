<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\Letter as LetterModel;
use Exception;

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
    public function show($no_surat = null)
    {
        $letters = $this->model->find($no_surat);
        if(empty($letters)) return $this->failNotFound('Data tidak Ditemukan');
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
        $rules = [
            'file' => 'uploaded[file]|mime_in[file,image/jpg,image/jpeg,image/png,application/pdf]',
            'no_surat' => 'required|is_unique[letters.no_surat]',
            'tanggal' => 'required|valid_date[d/m/Y]',
            'asal' => 'oneway[tujuan]',
            'tujuan' => 'oneway[asal]'
        ];
        $errors = [
            'file' => [
                'uploaded' => 'Silahkan sertakan file terkait',
                'mime_in' => 'Sertakan dalam format pdf atau Gambar'
            ],
            'no_surat' => ['required' => 'Silahkan sertakan nomor surat','is_unique'=>'Nomor Surat sudah digunakan'],
            'tanggal' => ['required' => 'Silahkan sertakan tanggal surat', 'valid_date' => 'Sesuaikan formatnya 01/01/2014'],
            'asal' => ['oneway' => 'hanya sertakan jika surat masuk'],
            'tujuan' => ['oneway' => 'Hanya sertakan jika surat keluar'],
        ];
        if (!$this->validate($rules, $errors)) return $this->fail($this->validator->getErrors());

        // Surat Masuk atau Surat keluar
        if (!empty($this->request->getVar('tujuan'))) {
            $data['tujuan'] = $this->request->getVar('tujuan');
            $srt_ = 'keluar';
        } else {
            $data['asal'] = $this->request->getVar('asal');
            $srt_ = 'masuk';
        }

        // File upload
        $file = $this->request->getFile('file');        
        $extension = $file->getClientExtension();
        $filename = $this->request->getVar('no_surat').'.'.$extension;            
        

        $data['no_surat'] = $this->request->getVar('no_surat');
        $data['tanggal'] = date('Y-m-d',strtotime($this->request->getVar('tanggal')));
        
        $data['file'] = $filename;
        $letter = $this->model->insert($data,false);
        if(!$letter) return $this->fail('Gagal Menambahkan surat');
        if (!$file->hasMoved()) $file->move(WRITEPATH . '/uploads/' . $srt_,$filename);
        return $this->respond($data);
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
        if(empty($this->model->find($id))) return $this->fail('Surat tidak ditemukan');
        $letter=$this->model->update($id,['approval'=>true]);
        if(!$letter) return $this->fail('Approval Gagal');
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $data = $this->model->find($id);
        if(!$data) return $this->failNotFound('Data '.$id.' tidak ditemukan');
        try{

            $deleted= $this->model->delete($id);
            return $this->respondDeleted(['Msg'=>'Nomor Surat '.$data["no_surat"].' Berhasil dihapus']);
        } catch(Exception $e){
            return $this->fail('Gagal');
        }
    }

    public function approvedLetter(){
        $data = $this->model->find('approval',true);
        return $this->respond($data);
    }    
     
}
