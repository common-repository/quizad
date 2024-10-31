<?php


namespace QuizAd\Model;


interface RestResponseInterface
{
	public function getCode();
	public function getMessage();
	public function wasSuccessful();
}