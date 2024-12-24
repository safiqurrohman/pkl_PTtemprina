<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_divisi extends CI_Model
{

    var $table = 'tbl_divisi';
    var $column_order = array('nama_divisi'); // Column yang dapat diurutkan
    var $column_search = array('nama_divisi', 'nama'); // Kolom yang bisa dicari
    // var $column_search = array('nama'); // Kolom yang bisa dicari
    var $order = array('id_divisi' => 'desc'); // default order

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {
        $this->db->from($this->table);
        $i = 0;

        foreach ($this->column_search as $item) // loop column
        {
            if ($_POST['search']['value']) // if datatable send POST for search
            {
                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) // last loop
                    $this->db->group_end(); // close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    function getAll()
    {
        return $this->db->get($this->table);
    }

    function view_divisi($id)
    {
        $this->db->where('id_divisi', $id);
        return $this->db->get($this->table);
    }

    function get_nama_divisi($nama_divisi)
    {
        $this->db->from($this->table);
        $this->db->where('nama_divisi', $nama_divisi);
        $query = $this->db->get();
        return $query->row();
    }

    function get_divisi($id)
    {
        $this->db->where('id_divisi', $id);
        return $this->db->get($this->table)->row();
    }

    function edit_divisi($id)
    {
        $this->db->where('id_divisi', $id);
        return $this->db->get($this->table);
    }

    function insertDivisi( $data)
    {
        $insert = $this->db->insert($this->table, $data);
        return $insert;
    }

    function updateDivisi($id, $data)
    {
        $this->db->where('id_divisi', $id);
        $this->db->update($this->table, $data);
    }

    function deleteDivisi($id)
    {
        $this->db->where('id_divisi', $id);
        $this->db->delete($this->table);
    }
}

/* End of file Mod_divisi.php */
