<?php

namespace App\Presenters;

use Nette;
use Nette\Database\Context;
use Nette\Http\Session;
use Tracy\Debugger;


class BasePresenter extends Nette\Application\UI\Presenter
{

	private $database;
	private $session;
	private $visitedSection;

	public function __construct(Context $database, Session $session)
	{
		$this->database = $database;
		$this->session = $session;
		$this->visitedSection = $this->session->getSection("visited");
	}

	public function beforeRender()
	{
		if (!is_array($this->visitedSection->pages)) {
			$this->visitedSection->pages = [];
		}
		$currentLink = $this->getName() . ":" .$this->view;
		$this->visitedSection->pages[] = $currentLink;
		Debugger::dump($this->visitedSection->pages);
	}
}
