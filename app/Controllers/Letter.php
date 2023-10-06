<?php

namespace App\Controllers;

use App\Models\Disposisi;
use CodeIgniter\RESTful\ResourceController;
use App\Models\Letter as LetterModel;
use DateTime;
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
    public function index($tipe = null)
    {
        $data = $this->model->where($tipe === 'masuk' ? 'tujuan' : 'asal', '')->findAll();
        return $this->respond($data);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($no_surat = null)
    {
        $letters = $this->model->find($no_surat);
        if (empty($letters)) return $this->failNotFound('Data tidak Ditemukan');
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
        $tipe = $this->request->getVar('tipe');
        $jenis = $tipe === 'masuk' ? 'asal' : 'tujuan';

        $rules = [
            'file' => 'uploaded[file]|mime_in[file,image/jpg,image/jpeg,image/png,application/pdf]',
            'no_surat' => 'required|is_unique[letters.no_surat]',
            'tanggal' => 'required',
            $jenis => 'required'
        ];
        $errors = [
            'file' => [
                'uploaded' => 'Silahkan sertakan file terkait',
                'mime_in' => 'Sertakan dalam format pdf atau Gambar'
            ],
            'no_surat' => ['required' => 'Silahkan sertakan nomor surat', 'is_unique' => 'Nomor Surat sudah digunakan'],
            'tanggal' => ['required' => 'Silahkan sertakan tanggal surat'],
            $jenis => ['required' => 'Silahkan sertakan ' . $tipe . ' surat']

        ];
        if (!$this->validate($rules, $errors)) return $this->fail($this->validator->getErrors());



        // File upload
        $file = $this->request->getFile('file');
        $extension = $file->getClientExtension();
        $filename = $this->request->getVar('no_surat') . '.' . $extension;


        $data['no_surat'] = $this->request->getVar('no_surat');
        $data['tanggal'] = date('Y-m-d', strtotime($this->request->getVar('tanggal')));
        $data[$jenis] = $this->request->getVar($jenis);
        $data['file'] = $filename;
        $letter = $this->model->insert($data, false);
        if (!$letter) return $this->fail('Gagal Menambahkan surat');
        if (!$file->hasMoved()) $file->move(WRITEPATH . '/uploads/' . $tipe, $filename);
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
    public function approveLetter()
    {
        $id = $this->request->getVar('approveId');
        $surat = $this->model->find($id);
        if (empty($surat)) return $this->fail('Surat tidak ditemukan');
        $letter = $this->model->update($id, ['approval' => 1]);
        if (!$letter) return $this->fail('Approval Gagal');
        return $this->respond($letter);
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $data = $this->model->find($id);
        if (!$data) return $this->failNotFound('Data ' . $id . ' tidak ditemukan');
        try {

            $deleted = $this->model->delete($id);
            return $this->respondDeleted(['Msg' => 'Nomor Surat ' . $data["no_surat"] . ' Berhasil dihapus']);
        } catch (Exception $e) {
            return $this->fail('Gagal');
        }
    }

    public function approvedLetter()
    {
        $data = $this->model->where('approval', 1)->findAll();

        return $this->respond($data);
    }

    public function viewPdf($id = null)
    {
        $surat = $this->model->find($id);
        $tipe = empty($surat['asal']) ? 'keluar/' : 'masuk/';
        $pdfPath = WRITEPATH . 'uploads/' . $tipe . $surat['file'];

        if (file_exists($pdfPath) && is_readable($pdfPath)) {
            return $this->response->download($pdfPath, null, true);
        } else {
            return $this->response->setStatusCode(404, 'File not found');
        }
    }

    public function dashboard()
    {
        $surat = $this->model->findAll();
        $disp_model = new Disposisi();
        $disp = $disp_model->findAll();
        $suratMasuk = array_fill(0, 13, 0);
        $suratKeluar = array_fill(0, 13, 0);
        $propDisposisi = array_fill(0, 2, 0);
        $propApproval = array_fill(0, 2, 0);

        foreach ($surat as $srt) {
            $tgl = strtotime($srt['created_at']);
            $bulan = date('m', $tgl);
            $tahun = date('Y', $tgl);
            $selisihBulan = (date('Y') - $tahun) * 12 + (date('m') - $bulan);
            $key = $selisihBulan > 11 ? 12 : $selisihBulan;

            if (empty($srt['asal'])) {
                $suratKeluar[$key]++;
            } else {
                $suratMasuk[$key]++;
                if ($srt['approval'] == 0) {
                    $propApproval[$selisihBulan == 0 ? 0 : 1]++;
                } elseif (!in_array($srt['id'], array_column($disp, 'sid'))) {                                        
                    $propDisposisi[date('m-Y') === date('m-Y', strtotime($srt['updated_at'])) ? 0 : 1]++;
                }
            }
        }
        $data = ['srtmasuk' => $suratMasuk, 'srtkeluar' => $suratKeluar, 'propDisposisi' => $propDisposisi, 'propApproval' => $propApproval];


        return $this->respond($data);
    }
}
