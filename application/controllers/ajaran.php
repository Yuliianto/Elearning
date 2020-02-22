<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class ajaran extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        must_login();
        $this->load->model(['ajaran_model', 'mapel_model']);
    }

    public function index()
    {
        $getAllAjaran = $this->ajaran_model->list_ajaran();

        $results = array();
        foreach ($getAllAjaran as $key => $val) :
            $results[$key] = $val;
        endforeach;
        $data['ajaran'] = $results;
        $this->twig->display('list-ajaran.html', $data);
    }

    public function detail($type = NULL, $year = NULL)
    {
        $type = (string) $this->uri->segment(3);
        $year = (int) $this->uri->segment(4);

        if (empty($type) or $type == NULL and empty($year) or $year == NULL) :
            redirect('ajaran');
        else :
            if ($data['content'] = $this->ajaran_model->getDetailAjaran($type, $year)) :

                $result = array();
                $results = array();

                if ($type === 'ganjil') :

                    //start month
                    $start = '01';

                    //end month
                    $end = '06';

                    /**
                     * Get all tugas by year
                     * return @array
                     * 
                     */
                    $getAllTugasByYear = $this->ajaran_model->getAllTugasByYear($start, $end, $year);

                    /**
                     * If any data?
                     * 
                     * result used foreach
                     * 
                     * return @array
                     */
                    if ($getAllTugasByYear) :
                        foreach ($getAllTugasByYear as $key => $val) :

                            $result[$key] = $val;

                        endforeach;
                    endif;

                    /**
                     * Get all materi by year
                     * return @array
                     * 
                     */
                    $getAllMateriByYear = $this->ajaran_model->getAllMateriByYear($start, $end, $year);

                    /**
                     * If any data?
                     * 
                     * result used foreach
                     * 
                     * return @array
                     */
                    if ($getAllMateriByYear) :
                        foreach ($getAllMateriByYear as $key => $val) :

                            $results[$key] = $val;

                        endforeach;
                    endif;

                    //result Subtitle by URI SEGMENT
                    $data['subtitle'] = $year;

                elseif ($type === 'genap') :
                    $start = '07';
                    $end = '12';
                    if ($getAllTugasByYear = $this->ajaran_model->getAllTugasByYear($start, $end, $year)) :
                        foreach ($getAllTugasByYear as $key => $val) :
                            $result[$key] = $val;
                        endforeach;
                    endif;
                endif;
                $data['subtitle'] = '- ' . $year . ' Semester - ' . $type;
                $data['ajaran'] = $result;
                $data['materi'] = $results;
                $this->twig->display('list-detail-ajaran.html', $data);
            else :
                echo 'fail';
            endif;
        endif;
    }

    public function add()
    {
        $this->form_validation->set_rules('ta', 'Tahun Ajaran', 'trim|required');
        $this->form_validation->set_rules('semester', 'Semester', 'trim|required');
        $this->form_validation->set_rules('tak', 'Tahun Ajaran Akademik', 'trim|required');
        if ($this->form_validation->run() == TRUE) :
            $data = array(
                'tahun_ajaran' => $this->input->post('ta', TRUE),
                'semester_ajaran' => $this->input->post('semester', TRUE),
                'tahun' => $this->input->post('tak', TRUE),
                'status' => (int) 1,
                'created_at' => date('Y-m-d H:i:s')
            );

            if ($this->ajaran_model->insertDataAjaran($data)) :
                $this->session->set_flashdata('materi', get_alert('success', 'Materi berhasil disimpan '));
                redirect('ajaran');
            else :
                $this->session->set_flashdata('materi', get_alert('error', 'Silahkan coba kembali '));
                redirect('ajaran');
            endif;
        endif;

        $this->twig->display('tambah-ajaran.html');
    }

    public function delete($id = NULL)
    {
        $id = (int) $this->uri->segment(3);
        if (empty($id) || $id == NULL) :
            redirect('ajaran', 'refresh');
        else :
            if ($this->ajaran_model->deleteDataAjaran($id)) :
                redirect('ajaran', 'refresh');
            else :
                redirect('ajaran', 'refresh');
            endif;
        endif;
    }
    public function deletetugas($id = NULL)
    {
        $id = (int) $this->uri->segment(3);
        if (empty($id) || $id == NULL) :
            redirect('ajaran', 'refresh');
        else :
            if ($this->ajaran_model->deleteDataTugas($id)) :
                redirect('', 'refresh');
            else :
                redirect('', 'refresh');
            endif;
        endif;
    }
}
