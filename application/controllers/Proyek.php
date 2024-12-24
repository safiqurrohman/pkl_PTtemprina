<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Proyek extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Memuat model untuk Daftar Laporan dan Divisi
        $this->load->model(array('Mod_laporan', 'Mod_divisi'));
        $this->load->library('session');
    }

    public function index()
    {
        $this->load->helper('url');
        $data['nama_divisi'] = $this->Mod_divisi->getAll()->result();

        $this->template->load('layoutbackend', 'laporan/proyek', $data);
    }

    public function ajax_list()
    {
        $list = $this->Mod_laporan->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $laporan) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $laporan->nama_divisi;
            $row[] = $laporan->jenis_pekerjaan;
            $row[] = $laporan->pekerjaan;
            $row[] = $laporan->mulai;
            $row[] = $laporan->deadline;
            $row[] = $laporan->berakhir;
            $row[] = $laporan->user;
            $row[] = $laporan->sebelum;
            $row[] = $laporan->tindakan;
            $row[] = $laporan->setelah;
            $row[] = $laporan->dibuat;
            $row[] = $laporan->keterangan;
            $row[] = $laporan->status;
            $row[] = $laporan->id_plan;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            // "draw" => intval($this->input->post('draw')),
            "recordsTotal" => $this->Mod_laporan->count_all(),
            "recordsFiltered" => $this->Mod_laporan->count_filtered(),
            "data" => $data,
        );

        echo json_encode($output);
    }


    public function viewlaporan()
    {
        $id = $this->input->post('id');
        $table = $this->input->post('table');
        $data_field = $this->db->field_data($table);
        $detail = $this->Mod_laporan->view_laporan($id)->result_array();
        $data = array(
            'table' => $table,
            'data_field' => $this->db->field_data($table),
            'data_table' => $detail,
        );
        $this->load->view('laporan/view', $data);
    }

    public function editlaporan($id)
    {

        $data = $this->Mod_laporan->get_laporan($id);
        echo json_encode($data);
    }
 

    public function insert()
    {
        $this->_validate();

        // Data yang diambil dari input form
        $data  = array(
            'id_divisi'       => $this->input->post('id_divisi'),
            'jenis_pekerjaan' => $this->input->post('jenis_pekerjaan'),
            'pekerjaan'       => $this->input->post('pekerjaan'),
            'mulai'           => $this->input->post('mulai'),
            'deadline'        => $this->input->post('deadline'),
            'berakhir'        => $this->input->post('berakhir'),
            'user'            => $this->input->post('user'),
            'sebelum'         => $this->input->post('sebelum'),
            'tindakan'        => $this->input->post('tindakan'),
            'setelah'         => $this->input->post('setelah'),
            'dibuat'          => $this->input->post('dibuat'),
            'keterangan'      => $this->input->post('keterangan'),
            'status'          => $this->input->post('status'),
        );

        // Menyimpan data ke dalam database
        $insert_id = $this->db->insert_id();
        $this->Mod_laporan->insert_data($data);

        // echo json_encode(["status" => TRUE]);

        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {

        $this->_validate();
        $id = $this->input->post('id');
        $data  = array(
            'id_plan'       => $this->input->post('id_plan'),
            'id_divisi'       => $this->input->post('id_divisi'),
            'jenis_pekerjaan' => $this->input->post('jenis_pekerjaan'),
            'pekerjaan'       => $this->input->post('pekerjaan'),
            'mulai'           => $this->input->post('mulai'),
            'deadline'        => $this->input->post('deadline'),
            'berakhir'        => $this->input->post('berakhir'),
            'user'            => $this->input->post('user'),
            'sebelum'         => $this->input->post('sebelum'),
            'tindakan'        => $this->input->post('tindakan'),
            'setelah'         => $this->input->post('setelah'),
            'dibuat'          => $this->input->post('dibuat'),
            'keterangan'      => $this->input->post('keterangan'),
            'status'          => $this->input->post('status'),
        );
        $this->Mod_laporan->update_laporan($id, $data);
        echo json_encode(array("status" => TRUE));
    }
    public function delete()
    {
        $id = $this->input->post('id_plan');
        $this->Mod_laporan->deletelaporan($id, 'job_plans');
        // $this->Mod_submenu->deleteakses($id_submenu, 'tbl_akses_submenu');
        $data['status'] = TRUE;
        echo json_encode($data);
    }

    public function download()
    {

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Submenu');
        $sheet->setCellValue('C1', 'Link');
        $sheet->setCellValue('D1', 'Icon');
        $sheet->setCellValue('E1', 'Menu');
        $sheet->setCellValue('F1', 'Is Active');

        $menu = $this->Mod_submenu->getAll()->result();
        $no = 1;
        $x = 2;
        foreach ($menu as $row) {
            $sheet->setCellValue('A' . $x, $no++);
            $sheet->setCellValue('B' . $x, $row->nama_submenu);
            $sheet->setCellValue('C' . $x, $row->link);
            $sheet->setCellValue('D' . $x, $row->icon);
            $sheet->setCellValue('E' . $x, $row->nama_menu);
            $sheet->setCellValue('F' . $x, $row->is_active);
            $x++;
        }
        $writer = new Xlsx($spreadsheet);
        $filename = 'laporan-Submenu';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }


    private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        // Validasi id_divisi
        // if ($this->input->post('id_divisi') == '') {
        //     $data['inputerror'][] = 'id_divisi';
        //     $data['error_string'][] = 'ID Divisi harus diisi';
        //     $data['status'] = FALSE;
        // }

        // Validasi jenis_pekerjaan
        if ($this->input->post('jenis_pekerjaan') == '') {
            $data['inputerror'][] = 'jenis_pekerjaan';
            $data['error_string'][] = 'Jenis Pekerjaan harus diisi';
            $data['status'] = FALSE;
        }

        // Validasi pekerjaan
        if ($this->input->post('pekerjaan') == '') {
            $data['inputerror'][] = 'pekerjaan';
            $data['error_string'][] = 'Pekerjaan harus diisi';
            $data['status'] = FALSE;
        }

        // Validasi mulai (tanggal mulai)
        if ($this->input->post('mulai') == '') {
            $data['inputerror'][] = 'mulai';
            $data['error_string'][] = 'Tanggal Mulai harus diisi';
            $data['status'] = FALSE;
        }

        // Validasi deadline (tanggal deadline)
        if ($this->input->post('deadline') == '') {
            $data['inputerror'][] = 'deadline';
            $data['error_string'][] = 'Tanggal Deadline harus diisi';
            $data['status'] = FALSE;
        }

        // Validasi berakhir (tanggal berakhir)
        if ($this->input->post('berakhir') == '') {
            $data['inputerror'][] = 'berakhir';
            $data['error_string'][] = 'Tanggal Berakhir harus diisi';
            $data['status'] = FALSE;
        }

        // Validasi user
        if ($this->input->post('user') == '') {
            $data['inputerror'][] = 'user';
            $data['error_string'][] = 'User harus diisi';
            $data['status'] = FALSE;
        }

        // Validasi sebelum
        if ($this->input->post('sebelum') == '') {
            $data['inputerror'][] = 'sebelum';
            $data['error_string'][] = 'Sebelum harus diisi';
            $data['status'] = FALSE;
        }

        // Validasi tindakan
        if ($this->input->post('tindakan') == '') {
            $data['inputerror'][] = 'tindakan';
            $data['error_string'][] = 'Tindakan harus diisi';
            $data['status'] = FALSE;
        }

        // Validasi setelah
        if ($this->input->post('setelah') == '') {
            $data['inputerror'][] = 'setelah';
            $data['error_string'][] = 'Setelah harus diisi';
            $data['status'] = FALSE;
        }

        // Validasi dibuat (tanggal dibuat)
        if ($this->input->post('dibuat') == '') {
            $data['inputerror'][] = 'dibuat';
            $data['error_string'][] = 'Tanggal Dibuat harus diisi';
            $data['status'] = FALSE;
        }

        // Validasi keterangan
        if ($this->input->post('keterangan') == '') {
            $data['inputerror'][] = 'keterangan';
            $data['error_string'][] = 'Keterangan harus diisi';
            $data['status'] = FALSE;
        }

        // Validasi status
        if ($this->input->post('status') == '') {
            $data['inputerror'][] = 'status';
            $data['error_string'][] = 'Status harus diisi';
            $data['status'] = FALSE;
        }

        // Jika ada error, tampilkan pesan error dan hentikan proses
        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

}
