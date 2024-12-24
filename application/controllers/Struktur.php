<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Struktur extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // $this->load->helper('url');
        // $data['menu'] = $this->Mod_menu->getAll()->result();
        $this->template->load('layoutbackend', 'struktur_organisasi/hirarki');
    }
}
