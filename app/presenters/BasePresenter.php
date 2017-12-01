<?php

namespace App\Presenters;

use Nette;
use Nette\Database\Context;
use Nette\Http\Session;
use Nette\Security\User;


class BasePresenter extends Nette\Application\UI\Presenter
{

	private $database;
	private $session;
	private $visitedSection;
	private $user;

	public function __construct(Context $database, Session $session, User $user)
	{
		$this->user = $user;
		$this->database = $database;
		$this->session = $session;
		$this->visitedSection = $this->session->getSection("visited");
	}

	public function inject()
	{
		$authenticator = new Nette\Security\SimpleAuthenticator([
			'admin' => 'admin'
		]);
		$this->user->setAuthenticator($authenticator);
	}
}
