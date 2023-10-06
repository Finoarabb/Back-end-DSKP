<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Disposisi as ModelsDisposisi;
use App\Models\Letter;
use App\Models\User;
use CodeIgniter\API\ResponseTrait;

use function PHPSTORM_META\map;

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
        ->join('letters','disposisi.sid=letters.no_surat','right')
        ->findAll();
        // $no_surat = array_column($disp, 'no_surat');
        // $surat = $this->letter_model->find($no_surat);
        // var_dump($surat);exit;
        // if(empty($surat)) return $this->respond('Surat belum ada');
        return $this->respond($disp);
    }

    public function dispose() {
        $data=[];
        $tujuan = $this->request->getJSON();        
        
        
            $temp = $this->disp_model->whereIn('uid',$tujuan->disposalTarget)->where('sid',$tujuan->sid)->findAll();
            if(!empty($temp)) return $this->fail('Disposisi Sudah Dilakukan');
            foreach($tujuan->disposalTarget as $item)
            $data[]=['uid'=>$item,'sid'=>$tujuan->sid,'pesan'=>$tujuan->pesan]; 
        $result=$this->disp_model->insertBatch($data);
        return $this->respond($result);
    }
}
