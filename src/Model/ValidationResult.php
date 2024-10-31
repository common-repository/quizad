<?php


namespace QuizAd\Model;


class ValidationResult {
	protected $errors = array();

	public function addErrorMessage($msg) {
		$this->errors []= $msg;
	}

	public function isValid() {
		return count($this->errors) === 0;
	}

	public function getErrorMessages()
	{
		return array('errors' => $this->errors);
	}
}