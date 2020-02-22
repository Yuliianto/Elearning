<?php

class ajaran_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function list_ajaran()
    {
        return $this->db->order_by('tahun_ajaran', 'DESC')->get($this->db->dbprefix('tahun_ajaran'))->result_array();
    }

    public function getDetailAjaran($type, $year)
    {
        $this->db->where(array('semester_ajaran' => $type))
            ->where(array('tahun' => $year));
        $res = $this->db->get($this->db->dbprefix('tahun_ajaran'));

        if ($res->num_rows() == 1) :
            return $res->row_array();
        else :
            return false;
        endif;

        return $res;
    }

    public function getAllTugasByYear($start, $end, $year)
    {
        $this->db->select('tugas.*, pengajar.id AS pengajar_id, pengajar.nama')
            ->join('pengajar', 'pengajar.id=tugas.pengajar_id')
            ->where(array('MONTH(tgl_buat)>=' => $start))
            ->where(array('MONTH(tgl_buat)<=' => $end))
            ->where(array('YEAR(tgl_buat)>=' => $year))
            ->where(array('YEAR(tgl_buat)<=' => $year))
            ->order_by('tugas.id', 'DESC');
        $query = $this->db->get('tugas');
        if ($query->num_rows() > 0) :
            return $query->result_array();
        else :
            return false;
        endif;

        return false;
    }

    public function getAllMateriByYear($start, $end, $year)
    {
        $this->db->select('materi.*, pengajar.id AS pengajar_id, pengajar.nama')
            ->from('materi')
            ->join('pengajar', 'materi.pengajar_id=pengajar.id')
            ->where(array('MONTH(tgl_posting)>=' => $start))
            ->where(array('MONTH(tgl_posting)<=' => $end))
            ->where(array('YEAR(tgl_posting)>=' => $year))
            ->where(array('YEAR(tgl_posting)<=' => $year))
            ->order_by('materi.id', 'DESC');
        $query1 = $this->db->get();

        if ($query1->num_rows() > 0) :
            return $query1->result_array();
        else :
            return false;
        endif;

        return false;
    }

    public function insertDataAjaran($data)
    {
        return $this->db->insert('tahun_ajaran', $data);
    }

    public function deleteDataAjaran($id)
    {
        return $this->db->delete('tahun_ajaran', array('id' => $id));
    }

    public function deleteDataTugas($id)
    {
        return $this->db->delete('tugas', array('id' => $id));
    }
}
