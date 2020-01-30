<?php

use PHPUnit\Framework\TestCase;

class AdminTest extends TestCase{
	protected $admin;
	public function setUp(){
		$this->admin = new \App\action\adminAction;

	}
	/** @test */
	public function That_We_Get_Parameters(){
		$postArray = array('firstname' => "Jaryd", 'lastname' => "Fisherman", 'email' => "jf@percept.system", 'password' => "GTI123ph");
		$this->admin->form_submition($postArray);
		
		$this->assertEquals($this->admin->getFirstName(), "Jaryd");
		$this->assertEquals($this->admin->getLastName(), "Fisherman");
		$this->assertEquals($this->admin->getEmail(), "jf@percept.system");
		$this->assertEquals($this->admin->getPass(), sha1("GTI123ph"));

	}

	/** @test */
	public function If_We_Get_Null_On_Empty_Array(){
		$postArray = ['firstname' => "", 'lastname' => "", 'email' => "", 'password' => ""];

		$this->admin->form_submition($postArray);
		
		$this->assertEquals($this->admin->getFirstName(), null);
		$this->assertEquals($this->admin->getLastName(), null);
		$this->assertEquals($this->admin->getEmail(), null);
		$this->assertEquals($this->admin->getPass(), null);

	}

	/** @test */
	public function If_We_Get_The_Right_Method(){
		
	}


}

?>