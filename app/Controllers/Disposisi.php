<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Disposisi as ModelsDisposisi;
use App\Models\Letter;
use App\Models\User;
use CodeIgniter\API\ResponseTrait;

class Disposisi extends BaseController
{
    use ResponseTrait;
    protected $letter_model;
    protected $disp_model;
    protected $user_model;
    public function __construct()
    {
        $this->letter_model = new Letter();
        $this->disp_model = new ModelsDisposisi();
        $this->user_model = new User();
    }
    public function index()
    {
        $uid = verify_jwt()->uid;
        $disp = $this->disp_model
        ->where('uid', $uid)
        ->join('letters','disposisi.no_surat=letters.no_surat','right')
        ->findAll();
        // $no_surat = array_column($disp, 'no_surat');
        // $surat = $this->letter_model->find($no_surat);
        // var_dump($surat);exit;
        // if(empty($surat)) return $this->respond('Surat belum ada');
        return $this->respond($disp);
    }

    public function dispose($no_surat) {
        $data=[];
        $tujuan = $this->request->getJSON();        
        foreach($tujuan->ids as $id){
            $temp = $this->disp_model->where(['uid'=>$id,'no_surat'=>$no_surat])->findAll();
            if(empty($temp))
               $data[]=['uid'=>$id,'no_surat'=>$no_surat,'pesan'=>$tujuan->pesan]; 
        }
        $result=$this->disp_model->insertBatch($data);
        return $this->respond($result);
    }
}
