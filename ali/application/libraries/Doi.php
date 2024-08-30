<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Doi
{
	private $_ci;
	private $preference=array();

	function __construct()
	{
		$this->_ci =& get_instance();

		if ($x=$this->_ci->session->userdata('preference')){
			$this->preference=$this->_ci->session->userdata('preference');
		}

	}

	function initialize($config = array())
	{

	}

	public static function now($type='full', $return=true, $data=''){
		$result="";

		if ($data=='')
			$data=time();
		else
			$data=strtotime($data);

		switch($type){
			case 'full':
				$result= date('Y-m-d H:i:s',$data);
				break;
		}
		if ($return)
			return $result;
		else
			echo $result;
	}

	public static function dump($expression , $return = false, $die=false){
        ob_start();
        var_dump($expression);
        $content = ob_get_contents();
        ob_end_clean();

        if($return){
            return $content;
        }else{
            if(isset($_SERVER['argc']) && isset($_SERVER['argv'])){//from cli
                echo '<pre class="doi_dump">';
                echo htmlentities($content);
                echo '</pre>';
			}else{
                echo '<pre class="doi_dump">';
                echo htmlentities($content);
                echo '</pre>';
            }
			if($die)
				die();
        }
    }

	public static function kirim_email($data){
		try {
			$_ci =& get_instance();
			$preference = $_ci->db->select('*');
			$preference = $_ci->db->get('preference');

			$prefs=$preference->result_array();
			foreach($prefs as $key=>$pref){
				$p[$pref['uri_title']]=$pref['value'];
			}

			ini_set('MAX_EXECUTION_TIME', -1);
			$subject=$data['subject'];
			//$email_user=$this->_ci->session->userdata('email_user');
			$config = Array(
						'protocol' => $p['email_protocol'],
						'smtp_host' => $p['email_smtp_host'],
						'smtp_port' => $p['email_smtp_port'],
						'smtp_user' => $p['email_smtp_user'],
						'smtp_pass' => $p['email_smtp_pass'],
						'mailtype' => $p['email_mailtype'],
						'charset' => $p['email_charset'],
						'wordwrap' => $p['email_wordwrap']
						);

			$_ci->email->clear();
		
			if (array_key_exists('file', $data)){
				if (is_array($data['file'])){
					foreach($data['file'] as $row){
						$_ci->email->attach($row);
					}
				}else{
					$_ci->email->attach($data['file']);
				}
			}
			
			$_ci->load->library('email', $config);
			$_ci->email->set_newline("\r\n");
			$_ci->email->set_mailtype("html");

			if (array_key_exists('from', $data)){
				$_ci->email->from($data['from'][0],$data['from'][1]);
			}else{
				$_ci->email->from($p['email_smtp_user'],$p['email_title']);
			}
			
			if (array_key_exists('reply', $data)){
				$_ci->email->reply_to($data['reply'][0],$data['reply'][1]);
			}else{
				$_ci->email->reply_to($p['email_smtp_user'],$p['email_title']);
			}
		
			$_ci->email->to($data['email']);
			if (array_key_exists('cc', $data)){
				$_ci->email->cc($data['cc']);
			}
			if (array_key_exists('bcc', $data)){
				$_ci->email->bcc($data['bcc']);
			}
			
			$_ci->email->subject($subject);
			$_ci->email->message($data['content']);
			if (array_key_exists('content_text', $data)){
				$_ci->email->set_alt_message($data['content_text']);
			}
			
			if($_ci->email->send())
			{
				$hasil= 'success';
			}
			else
			{
				$hasil= $_ci->email->print_debugger();
			}
		} catch (\Exception $e) {
			echo 'Error : '.$e;
		}
		return $hasil;

	}
}

// END Template class