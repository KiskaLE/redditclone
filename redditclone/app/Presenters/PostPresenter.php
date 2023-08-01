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
        $this->template->post_content = str_replace("\n", "<br>", $post->content);
        $this->template->post = $post;
        $this->template->comments = $post->related("comments")->order("created_at DESC");
        $this->template->usernames = $this->database->table("auth");
        
        
            
    }
    
    protected function createComponentCommentForm(): Form {
        $form = new Form;
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
            "author_id" => $this->getUser()->getId(),
            "content" => $data->content,
        ]);

        $this->redirect("this");
    }
    
    protected function createComponentPostForm(): Form {
        $form = new Form;
        $form->addText("title")->setRequired();
        $form->addTextArea("content")->setRequired();
        $form->addSubmit("submit", "publikovat");
        $form->onSuccess[] = [$this, "postFormSucceeded"];
        return $form;
    }

    public function postFormSucceeded(\stdClass $data): void {
        $post = $this->database->table("posts")->insert([
            "title" => $data->title,
            "content" => htmlspecialchars($data->content),
            "author_id" => $this->getUser()->getId(),
        ]);
        $this->redirect("Post:show", $post->id);
    }
}