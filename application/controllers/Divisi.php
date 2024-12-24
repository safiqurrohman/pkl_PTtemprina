<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Divisi extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Mod_divisi'));
        // $this->load->model(array('Mod_userlevel'));
    }

    public function index()
    {
        // Menampilkan tampilan untuk halaman divisi
        $this->template->load('layoutbackend', 'struktur_organisasi/divisi');
        // echo 'selamat datang';
    }

    public function ajax_list()
    {
        ini_set('memory_limit', '512M');
        set_time_limit(3600);
        $list = $this->Mod_divisi->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $divisi) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $divisi->nama;
            $row[] = $divisi->nama_divisi;
            $row[] = $divisi->id_divisi;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Mod_divisi->count_all(),
            "recordsFiltered" => $this->Mod_divisi->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function adddivisi()
    {
        $this->load->view('divisi/add_divisi');
    }

    public function viewdivisi()
    {
        $id = $this->input->post('id');
        $table = $this->input->post('table');
        $data['table'] = $table;
        $data['data_field'] = $this->db->field_data($table);
        $data['data_table'] = $this->Mod_divisi->view_divisi($id)->result_array();
        $this->load->view('admin/view', $data);
    }

    public function editdivisi($id)
    {
        $data = $this->Mod_divisi->get_divisi($id);
        echo json_encode($data);
    }

    public function insert()
    {
        $this->_validate();
        $save  = array(
            'id_divisi'    => $this->input->post('id_divisi'),
            'nama'         => $this->input->post('nama'),
            'nama_divisi'  => $this->input->post('nama_divisi')
        );

        // Melakukan insert data ke database
        $insert = $this->Mod_divisi->insertDivisi($save);

        // Mengecek apakah insert berhasil
        if ($insert) {
            echo json_encode(array("status" => TRUE));  // Response success
        } else {
            echo json_encode(array("status" => FALSE, "message" => "Error adding / update data"));  // Response failure
        }

    }

    public function update()
    {
        $this->_validate();
        $id = $this->input->post('id_divisi');
        $save  = array(
            'id_divisi'    => $this->input->post('id_divisi'),
            'nama_divisi'  => $this->input->post('nama_divisi')
        );
        $this->Mod_divisi->updateDivisi($id, $save);
        echo json_encode(array("status" => TRUE));
    }

    public function delete()
    {
        $id = $this->input->post('id_divisi');
        $this->Mod_divisi->deleteDivisi($id, 'tbl_divisi');
        echo json_encode(array("status" => TRUE));
    }

    public function download()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'ID Divisi');
        $sheet->setCellValue('C1', 'Nama Divisi');

        $divisi = $this->Mod_divisi->getAll()->result();
        $no = 1;
        $x = 2;
        foreach ($divisi as $row) {
            $sheet->setCellValue('A' . $x, $no++);
            $sheet->setCellValue('B' . $x, $row->id_divisi);
            $sheet->setCellValue('C' . $x, $row->nama_divisi);
            $x++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'laporan-Divisi';

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

        if ($this->input->post('nama') == '') {
            $data['inputerror'][] = 'nama';
            $data['error_string'][] = 'Nama is required';
            $data['status'] = FALSE;
        }

        if ($this->input->post('nama_divisi') == '') {
            $data['inputerror'][] = 'nama_divisi';
            $data['error_string'][] = 'Nama Divisi is required';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }
}
