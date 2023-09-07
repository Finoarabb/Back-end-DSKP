<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\User;
use CodeIgniter\API\ResponseTrait;


class Login extends BaseController
{
    use ResponseTrait;
    

    public function index()
    {
        if(isLoggedIn()) return $this->fail('Anda sudah login');
        $rules = [
            'username' => 'required',
            'password' => 'required|min_length[8]',
        ];
        $errors=[
            'username'=>[
            'required' => 'Username tidak boleh kosong'],
            'password'=>[
            'required' => 'Password tidak boleh kosong',
            'min_length' => 'Password minimal harus terdiri dari {param} karakter']
        ];
        if (!$this->validate($rules,$errors))  return $this->fail($this->validator->getErrors());                                
        $model = new User();
        $user = $model->where('username', $this->request->getVar('username'))->first();
        if (!$user) return $this->fail(['username'=>'Username tidak ditemukan']);
        if(
            $this->request->getVar('password')!==$user['password']
        ) return $this->fail(['password'=>'Password Salah']);
        
        $payload = array(
            "uid" => $user['id'],
            "username"=>$user['username'],
            "role"=>$user['role'],
        );        
        $token = generate_jwt($payload);
        return $this->respond(['token'=>$token]);
    }
}
