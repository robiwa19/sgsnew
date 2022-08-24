<?php namespace App\Controllers\Setting;
use App\Controllers\BaseController;
use App\Models\Datatable_model;
use App\Models\IonAuthModel;
use App\Models\HomeModel;
use Config\Services;

class Setting extends BaseController
{
    public function __construct()
	{
		$this->ionAuth    = new \IonAuth\Libraries\IonAuth();
		$this->validation = \Config\Services::validation();
        $this->request = Services::request();
		helper(['form', 'url']);
		$this->back="login_user";
	 
	}
    public function output_json($data)
	{
         return $this->response->setStatusCode(200)->setJson($data);
		 
	}
    public function index()
    {  if (!$this->ionAuth->loggedIn())
		{
			return redirect()->to($this->back);
	   };
		 $home_model = new HomeModel();
        $this->data['data']= $home_model->find(1);
		$this->data['menu']="layout/layout_admin/menu_kiri";
        $this->data['form']="setting/edit_info";
        return view('layout/layout_admin/layout_admin',$this->data);
    }
    
    public function is_admin()
	{
		if (!$this->ion_auth->is_admin()){
			show_error('Hanya Administrator yang diberi hak untuk mengakses halaman ini, <a href="'.base_url('dashboard').'">Kembali ke menu awal</a>', 403, 'Akses Terlarang');
		}
	}
    
    

    public function save_edit_data()
    { 
		if (!$this->ionAuth->loggedIn())
		{
			return redirect()->to($this->back);
	   };
        $rules = [
			"about" => "required",
            "alamat" => "required",
            "telp" => "required",
			"alasan_join" => "required",
			"map" => "required",
            
			
		];

		$messages = [
			"about" => [
				"required" => "About is required"
			],
            "alamat" => [
				"required" => "Alamat is required"
			],
            "telp" => [
				"required" => "Telp  is required"
			],
			"alasan_join" => [
				"required" => "Alasan Join  is required"
			],
			"map" => [
				"required" => "Alasan Join  is required"
			],
		];

		if (!$this->validate($rules, $messages)) {

			$response = [
				'status' => false,
				'errors' => $this->validator->getErrors(),
				'data' => []
			];
		} else {
            $home_model = new HomeModel();
             $id_setting= $this->request->getVar("setting_id");
			if ($home_model->find($id_setting)) {
				$data['about'] = $this->request->getVar("about");
				$data['alamat'] = $this->request->getVar("alamat");
				$data['telp'] = $this->request->getVar("telp");
				$data['alasan_join'] = $this->request->getVar("alasan_join");
				$data['map'] = $this->request->getVar("map");
                $home_model->update($id_setting, $data);
                $response = [
					'status' => true,
					 'data' => [],
                     'url'	 => 'edit_info',
				];
            }else{
            $response = [
                'status' => false,
                'failed' => 'No Data found',
                'data' => []
            ];

        }

    }
		return $this->output_json($response);
	}
}