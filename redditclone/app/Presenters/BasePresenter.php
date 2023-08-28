<?php
namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use App\Modules\ReactionControl;

abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    private $database;

    public function __construct(
        Nette\Database\Explorer $database
    ){  
        $this->database = $database;
    }

    protected function startup() {
        parent::startup();
    }

    public function getUpvotes($database ,$parent_id, $type){
        $upvotes = $database->table("upvotes")->where("parent_id = ? AND value = ? AND type=?", [$parent_id,"1",$type])->count();
        $downvotes = $database->table("upvotes")->where("parent_id = ? AND value = ? AND type=?", [$parent_id,"0", $type])->count();

        return $upvotes - $downvotes;
    }

    protected function createComponentReactionForm(): Form {
        $form = new Form;
        $form->addHidden("parent_id")->setRequired();
        $form->addHidden("reaction")->setRequired();
        $form->addHidden("type")->setRequired();
        $form->addSubmit("submitReaction");
        $form->onSuccess[] = [$this, "reactionFormSucceeded"];
        return $form;
    }

    public function reactionFormSucceeded(\stdClass $data) {
        $conn = $this->database->table("upvotes");
        $user_id = $this->getUser()->id;
        $conn->insert([
            "author_id" => $user_id,
            "parent_id" => $data->parent_id,
            "value" => $data->reaction,
            "type" => $data->type,
        ]);

        header("Refresh:0");
    }
}