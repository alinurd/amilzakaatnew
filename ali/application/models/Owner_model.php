<?php defined('BASEPATH') or exit('No direct script access allowed');

class Owner_model extends CI_Model
{

    public function set_filter_query()
    {

        $this->db->join('users', 'posts.user_id = users.id');
        $this->db->join('categories', 'posts.category_id = categories.id');
        $this->db->select('posts.* , users.username as username, users.slug as user_slug, categories.name as category_name, categories.slug as category_slug, categories.parent_id as category_parent_id, 
		(SELECT slug FROM categories WHERE id = category_parent_id) as parent_category_slug, (SELECT COUNT(comments.id) FROM comments WHERE comments.post_id = posts.id AND comments.parent_id = 0 AND status = 1) as comment_count');
        $this->db->where('posts.visibility', 1);
        $this->db->where('posts.status', 1);
        $this->db->where('posts.lang_id', $this->selected_lang->id);
    }

    public function booking_values()
    {
        $data = array(
            'nama' => remove_forbidden_characters($this->input->post('nama', true)),
            'tenor' => remove_forbidden_characters($this->input->post('tenor', true)),
            'tanggal_acara' => remove_forbidden_characters($this->input->post('tgl', true)),
            'hp' => remove_forbidden_characters($this->input->post('hp', true)),
            'email' => remove_forbidden_characters($this->input->post('email', true)),
            'alamat' => remove_forbidden_characters($this->input->post('alamat', true)),
            'group_id' => remove_forbidden_characters($this->input->post('group_id', true)),
            'post_id' => remove_forbidden_characters($this->input->post('post_id', true)),
        );
        return $data;
    }

    public function insert_booking()
    {

        $data = $this->owner_model->booking_values();
        $datax['group_id'] = $data['group_id'];
        $datax['tanggal'] = $data['tanggal_acara'];
        $datax['post_id'] = $data['post_id'];
        $datax["created_at"] = date('Y-m-d H:i:s');
        $this->db->insert('jadwal', $datax);
        $data['token'] = generate_unique_id();
        $data['code'] = generate_code_trans();
        $data["created_at"] = date('Y-m-d H:i:s');
        return $this->db->insert('booking', $data);
    }

    public function get_list_booking()
    {
        // $this->set_filter_query();
        $this->db->select('booking.*, booking.status as status_booking,booking.id as id_booking, posts.*, users.*');
        $this->db->from('booking');
        $this->db->join('posts', 'booking.post_id = posts.id');
        $this->db->join('users', 'booking.group_id = users.id');
        $this->db->order_by('booking.created_at', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_bookin_by_id($id)
    {
        $this->db->select('booking.*, booking.status as status_booking,booking.id as id_booking, posts.*, users.*');
        $this->db->from('booking');
        $this->db->join('posts', 'booking.post_id = posts.id');
        $this->db->join('users', 'booking.group_id = users.id');
        $this->db->where('booking.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_bayar_by_id($id)
    {
        $this->db->where('id_booking', $id);
        $query = $this->db->get("bayar");
        return $query->result();
    }
    public function get_jadwal($id)
    {
        $this->db->where('group_id', $id);
        $query = $this->db->get("jadwal");
        return $query->result();
    }

    public function update_pembayaran($id, $jml, $tenor)
    {
        $booking = $this->get_bookin_by_id($id);

        $bayar = $this->get_bayar_by_id($id);
        $total_harga = 0;
        foreach ($bayar as $item) {
            $total_harga += $item->jumlah;
        }
        if ($booking->harga == $total_harga) {
            $data = array(
                'status' => 3,
            );
            $this->db->where('id', $id);
            $idx= $this->db->update('booking', $data);
        } else {
            $datax = array(
                'id_booking' => $id,
                'jumlah' => $jml,
                'tenor' => $tenor,
                'created_at' =>  date('Y-m-d H:i:s')
            );
            $idx= $this->db->insert('bayar', $datax);
            $bayar = $this->get_bayar_by_id($id);
            $total_harga = 0;
            foreach ($bayar as $item) {
                $total_harga += $item->jumlah;
            }
            if ($booking->harga == $total_harga) {
                $data = array(
                    'status' => 3,
                );
            }else{
                $data = array(
                    'status' => 2,
                );
            }
            $this->db->where('id', $id);
            $idx= $this->db->update('booking', $data);
        }
        return $idx;
    }
    //check if email is unique
    public function is_unique_email($email, $user_id = 0)
    {
        $user = $this->auth_model->get_owner_by_email($email);

        //if id doesnt exists
        if ($user_id == 0) {
            if (empty($user)) {
                return true;
            } else {
                return false;
            }
        }

        if ($user_id != 0) {
            if (!empty($user) && $user->id != $user_id) {
                //email taken
                return false;
            } else {
                return true;
            }
        }
    }

    public function get_owner_by_email($email)
    {
        $this->db->where('email', $email);
        $query = $this->db->get('owner');
        return $query->row();
    }

    public function insert()
    {
        $this->load->library('bcrypt');
        $data = $this->owner_model->input_values();
        $data['token'] = generate_unique_id();
        // $data['last_seen'] = date('Y-m-d H:i:s');
        $data["created_at"] = date('Y-m-d H:i:s');
        $data["created_by"] = date('Y-m-d H:i:s');

        return $this->db->insert('owner', $data);
    }
}
