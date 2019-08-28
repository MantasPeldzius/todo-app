<?php

namespace App;

use Illuminate\Mail\Mailable;

class ChangePasswordEmail extends Mailable {
	
	public $hash;
	
	public function __construct($hash) {
		$this->hash = $hash;
	}
	
	public function build() {
		return $this->view('ChangePasswordEmail');
    }
}
