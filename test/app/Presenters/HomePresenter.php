<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;


final class HomePresenter extends Nette\Application\UI\Presenter
{
    public function __construct( private Nette\Database\Explorer $database)
    {
       
    }

    public function renderDefault(): void
    {
        $this->template->posts = $this->database
            ->table('posts')
            ->order('created_at DESC')
            ->limit(5);
    }

    protected function createComponentPostForm(): Form {
        $form = new Form;
        $form->addText("title", "nadpis")->setRequired();
        $form->addTextArea("content")->setRequired();
        $form->addSubmit("submit", "publikovat");
        $form->onSuccess[] = [$this, "postFormSucceeded"];
        return $form;
    }

    public function postFormSucceeded(\stdClass $data): void {
        $post = $this->database->table("posts")->insert([
            "title" => $data->title,
            "content" => $data->content,
        ]);
        $this->redirect("Post:show", $post->id);
    }
}