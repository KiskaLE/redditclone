<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

final class EditPresenter extends Nette\Application\UI\Presenter {
    public function __construct( private Nette\Database\Explorer $database)
    {
       
    }

    public function startup(): void{
        parent::startup();

        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:in');
	    }
    }


    public function renderEdit(int $postId): void
    {
        $post = $this->database
            ->table("posts")
            ->get($postId);
        
        if(!$post) {
            $this->error("příspěvek nebyl nalezen");
        }
    
    }

    protected function createComponentPostForm(): Form {
        $postId = $this->getParameter("postId");
        $post = $this->database
			->table('posts')
			->get($postId);

        $form = new Form;
        $form->addText("title", "nadpis")->setRequired()->setValue($post->title);
        $form->addTextArea("content")->setRequired()->setValue($post->content);
        $form->addSubmit("submit", "upravit");
        $form->onSuccess[] = [$this, "postFormSucceeded"];
        return $form;
    }
    
    public function postFormSucceeded(array $data): void {
        $postId = $this->getParameter("postId");
        $post = $this->database
			->table('posts')
			->get($postId);
		$post->update($data);

	$this->redirect('Post:show', $post->id);
}

}