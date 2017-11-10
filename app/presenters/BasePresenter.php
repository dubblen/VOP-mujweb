<?php

namespace App\Presenters;

use Nette;
use Nette\Database\Context;
use Nette\Security\SimpleAuthenticator;


class BasePresenter extends Nette\Application\UI\Presenter
{

	private $database;

	public function __construct(Context $database)
	{
		$this->database = $database;
	}

	public function beforeRender()
	{
		$authenticator = new Nette\Security\SimpleAuthenticator([
			'john' => 'IJ^%4dfh54*',
			'kathy' => '12345', // Kathy, this is a very weak password!
		]);

		$this->user->setAuthenticator($authenticator);
	}
}
