<?php
namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

final class PostPresenter extends BasePresenter {
    public function __construct(
        private Nette\Database\Explorer $database
    ){  
        parent::__construct($this->database);
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
        $this->template->numOfUpvotes = $this->getUpvotes($this->database,$postId, 1);
        $this->template->commentsReactionsDatabase = $this->database;
        
            
    }
    
    protected function createComponentCommentForm(): Form {
        $form = new Form;
        $form->addTextArea("content")
            ->setRequired()
            ->setHtmlAttribute("class", "comment-textarea")
            ->setHtmlAttribute("placeholder", "What are your thoughts?");
        $form->addSubmit("send", "Comment");
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
        $form->addTextArea("title")->setRequired()->setHtmlAttribute("placeholder", "Title");
        $form->addTextArea("content")->setRequired()->setHtmlAttribute("placeholder", "Text (optinal)");
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