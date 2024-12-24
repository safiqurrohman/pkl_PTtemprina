<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_laporan extends CI_Model
{

    var $table = 'job_plans';
    var $tbl_divisi = 'tbl_divisi';
    var $column_search = array('a.jenis_pekerjaan', 'a.pekerjaan', 'a.user', 'b.nama_divisi', 'a.status'); // set column field database for datatable searchable
    var $column_order = array('jenis_pekerjaan', 'pekerjaan', 'mulai', 'deadline', 'status', null); // columns for datatable ordering
    var $order = array('id_plan' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query($term = '')
    {
        $this->db->select('a.*, b.nama_divisi');
        $this->db->from('job_plans as a');
        $this->db->join('tbl_divisi as b', 'a.id_divisi = b.id_divisi', 'left');
        // $this->db->like('a.pekerjan', $term);
        // $this->db->or_like('a.link', $term);
        // $this->db->or_like('b.nama_divisi', $term);

        // Filter berdasarkan user yang sedang login
        $this->db->where('a.user', $this->session->userdata('username')); // Ganti dengan data session user yang tepat


        // search term logic
        $this->db->like('a.jenis_pekerjaan', $term);
        $this->db->or_like('a.pekerjaan', $term);
        $this->db->or_like('a.user', $term);
        $this->db->or_like('b.nama_divisi', $term);
        $this->db->or_like('a.status', $term);
        $this->db->or_like('a.dibuat', $term);

        $i = 0;
        foreach ($this->column_search as $item) // loop column
        {
            if ($_POST['search']['value']) // if datatable send POST for search
            {
                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) // last loop
                    $this->db->group_end(); // close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }




    function get_datatables()
    {


        $term = $_REQUEST['search']['value'];
        $this->_get_datatables_query($term);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }


    function count_filtered()
    {
        $term = $_REQUEST['search']['value'];
        $this->_get_datatables_query($term);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from('job_plans as a');
        $this->db->join('tbl_divisi as b', 'a.id_divisi = b.id_divisi', 'left');
        return $this->db->count_all_results();
    }

    function getAll()
    {
        
        $this->db->select('a.*, b.nama_divisi');
        $this->db->join('tbl_divisi b', 'a.id_divisi = b.id_divisi', 'left');
        return $this->db->get('job_plans a');
    }

    function view_laporan($id)
    {
        $this->db->where('id_plan', $id);
        $this->db->join('tbl_divisi b', 'a.id_divisi = b.id_divisi', 'left');
        return $this->db->get('job_plans a');
    }

    function get_laporan($id)
    {
        $this->db->where('id_plan', $id);
        $this->db->join('tbl_divisi b', 'a.id_divisi = b.id_divisi', 'left');
        return $this->db->get('job_plans a')->row();
        log_message('debug', 'Query: ' . $this->db->last_query()); // Log query yang dijalankan

        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return null;
        }
    }

    function insert_data($data)
    {
        $insert = $this->db->insert($this->table, $data);
        return $insert;
    }

    function updateLaporan($id_plan, $data)
    {
        $this->db->where('id_plan', $id_plan);
        $this->db->update('job_plans', $data);
    }

    function deleteLaporan($id)
    {
        $this->db->where('id_plan', $id);
        $this->db->delete($this->table);
    }
}

/* End of file Mod_laporan.php */
