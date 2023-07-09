<?php
namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

final class PostPresenter extends BasePresenter {
    public function __construct(
        private Nette\Database\Explorer $database
    ){  
    }

    public function renderShow(int $postId): void {
        $post = $this->database
            ->table("posts")
            ->get($postId);
        if(!$post) {
            $this->error("stránka nebyla nalezena");
        }
        $this->template->post = $post;
        $this->template->comments = $post->related("comments")
            ->order("created_at");
    }

    protected function createComponentCommentForm(): Form {
        $form = new Form;
        $form->addText("name", "Jméno:")
            ->setRequired();
        $form->addEmail("email", "E-mail:");
        $form->addTextArea("content", "Komentář")
            ->setRequired();
        $form->addSubmit("send", "odeslat");
        $form->onSuccess[] = [$this, "commentFormSucceeded"];
        return $form;
    }

    public function commentFormSucceeded(\stdClass $data): void {
        $postId = $this->getParameter("postId");

        $this->database->table("comments")->insert([
            "post_id" => $postId,
            "name" => $data->name,
            "email" => $data->email,
            "content" => $data->content,
        ]);

        $this->redirect("this");
    }
}