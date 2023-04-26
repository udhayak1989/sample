<?php
/* branch newwww edited */
	class Users extends CI_Controller{
		

		// Log in user 
	public function login()
		{
			$data['title'] = 'Sign In';

			
			







	$this->form_validation->set_rules('username', 'Username', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required');

			if($this->form_validation->run() === FALSE)
			{
				////$this->load->view('templates/head');
				$this->load->view('users/login', $data);
				
			} 
			else 
			{
				
				//$use=$this->input->post('username');
			//$pass=$this->input->post('password');
			  // Prepare the SQL query
   //$sql = "SELECT * FROM branches WHERE username='$use' AND password='$pass'";
// Execute the query
//$query = $this->db->query($sql);

// Check if any rows were returned
//if ($query->num_rows() > 0) {
    // Output the data of each row
    
   // $row = $query->row();
   //      $row->start_date . "<br>";
	//	 $row->end_date . "<br>";
	//	$status=$row->status . "<br>";
		// $start_timestamp = strtotime($row->start_date);
   // $end_timestamp = strtotime($row->end_date);
   // $diff_days = round(($end_timestamp - $start_timestamp) / (60 * 60 * 24));
    
   // $today_timestamp = time();
   // $diff_days2 = round(($today_timestamp - $start_timestamp) / (60 * 60 * 24));
        
    //$diff = $diff_days . "<br>";
    //$diff2 = $diff_days2 . "<br>";
    
    //if ($end_timestamp > $today_timestamp && $status!=0) {

				
				// Get username 
				$username = $this->input->post('username');
				// Get and encrypt the password
				$password = $this->input->post('password');

				// Login user
				$user_id = $this->User_model->login($username, $password);
                $user_info=$this->User_model->companylogin($username,$password);
				$user_log = $this->User_model->regi($username,$password);
		
				$data = $this->Branch_model->get_branches($user_id);

				$data1=$this->Operator_employees_model->get_details($user_log);
				
				$data2=$this->Company_model->get_details($user_info);

				/////$role = $this->Branch_model->get_roles();
			//	$role=$this->User_model->get_role($data['role']);
				if($user_id){
					// Create session
					$user_data = array(
						
						'user_id' => $user_id,
						'name' => $data['bname'],
						'email' => $data['email'],
						'id' => $data['id'],
						////////'branch' => $data['branch_code'],
						'username' => $username,
						'logged_in' => true,
						'role' => $data['role']
						
					);

					$this->session->set_userdata('logged_in', $user_data);
					///echo $role   ;

					// Set message
					$this->session->set_flashdata('user_loggedin', 'You are now logged in');
					redirect('dashboards');
				} 
		


				elseif($user_log)
				{
					$userdata = array(
						'user_id' => $user_log,
						'username' => $username,
						'id' => $data1['id'],
						'name' => $data1['empl_name'],
						'email' => $data1['empl_mail'],
						'branch' => $data1['branch_code'],
						'logged_in' => true,
						'roles' => $data1['roles'],
						'role'=> $data1['role']
					);

					$this->session->set_userdata('logged_in',$userdata);

					//print_r($userdata);exit();

					// Set message
					$this->session->set_flashdata('user_loggedin', 'You are now logged in');

					redirect('dashboards');
				}
				
				elseif($user_info)
				{
					$user = array(
						'user_id' => $user_info,
						'username' => $username,
						'id' => $data2['id'],
						'name' => $data2['branch_name'],
				    	'logged_in' => true,
						//'branch_code' => $role,
						'role'=> $data2['role']
					);
					

					$this->session->set_userdata('logged_in',$user);

					//print_r($userdata);exit();

					// Set message
					$this->session->set_flashdata('user_loggedin', 'You are now logged in');

					redirect('dashboards');
				}


		//	}
			/*else{
				  $hh = "Your Account has been suspended";
				  $mm="please contact administrator!!";
    $data = array('hh' => $hh,'mm'=>$mm);
    $this->load->view('users/login', $data);
			}*/
			
			
		//}
			
				else 
				{
					// Set message
					 $hh = "Your Password or Username is incorrect/invalid";
					  $mm="";
					   $data = array('hh' => $hh,'mm'=>$mm);
   
    $this->load->view('users/login', $data);
				}			
			
			
			
			}
			
			
			}
		
		
		
	








		// Log user out
		public function logout()
		{
			
            

			// Unset user data
			$this->session->unset_userdata('logged_in');
			$this->session->unset_userdata('user_id');
			$this->session->unset_userdata('username');

			// Set message
			$this->session->set_flashdata('user_loggedout', 'You are now logged out');

			redirect('users/login');
		}

		// Check if username exists
		public function check_username_exists($username)
		{
			$this->form_validation->set_message('check_username_exists', 'That username is taken. Please choose a different one');
			if($this->User_model->check_username_exists($username)){
				return true;
			} else {
				return false;
			}
		}
	}