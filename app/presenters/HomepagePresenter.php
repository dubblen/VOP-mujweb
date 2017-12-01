<?php

namespace App\Presenters;

use Nette;
use Nette\Database\Context;
use Tracy\Debugger;
use Nette\Http\Session;
use Nette\Security\User;



class HomepagePresenter extends BasePresenter
{

	private $database;
	private $user;

	public function __construct(Context $database, Session $session, User $user)
	{
		parent::__construct($database, $session, $user);
		$this->database = $database;
		$this->user = $user;
	}



	public function renderDefault() {
		$this->template->articles = $this->database->table('articles');

	}

	public function renderProfile() {

	}

	public function actionLogIn() {
		$this->user->login('admin', 'admin');
		$this->flashMessage("Příhlášení proběhlo v pořáku");
		$this->redirect('Homepage:default');
	}

	protected function createComponentAddArticleForm() {
		$form = new Nette\Application\UI\Form;

		$form->addText('title', 'Název: ')
			->setRequired('Prosím zadejte název článku.')
			->setAttribute('class', 'testclass');

		$form->addTextArea('content', 'Článek: ')
			->setRequired('Prosím zadejte obsah článku.');

		$form->addUpload('image','Obrázek: ')
			->setRequired("Prosím vyberte obrázek k nahrání.");

		$form->addSubmit('add', 'Přidat');

		$form->onSuccess[] = [$this, 'addArticleFormSuccess'];

		return $form;
	}

	public function addArticleFormSuccess($form, $values) {

		$file = $values->image;


		if ($values->title != "" && $file->isImage()) {

			$file_ext=strtolower(mb_substr($file->getSanitizedName(), strrpos($file->getSanitizedName(), ".")));

			$filename = Nette\Utils\Random::generate(15);
			$filename = $filename . $file_ext;


			$isUnique = ($this->database->table('articles')
					->where("img_filename", $filename)->count() <= 0);


			while(!$isUnique) {
				$filename = Nette\Utils\Random::generate(15);
				$filename = $filename . ".png";

				$isUnique = ($this->database->table('articles')
						->where("img_filename", $filename)->count() <= 0);

			}

			$file->move("images/".$filename);

			$this->database->table("articles")->insert([
				'img_filename' => $filename,
				'title' => $values->title,
				'content' => $values->content,
				'author' => 1,
				'created' => new Nette\Utils\DateTime,
				'updated' => new Nette\Utils\DateTime
			]);
			$form->getPresenter()->flashMessage('Článek byl úspěšně přidán', 'success');
		}
		else {
			$form->getPresenter()->flashMessage('Článek nebyl přidán', 'danger');
		}



		$form->getPresenter()->redirect('this');
	}
}
