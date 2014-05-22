<?php
class Position
{
	public $moveId;
	public $title;
	public $deposit;
	public $liftOff;
	public $comment;
	
	/* TODO nicht genutzt? */
	public function getMoveId() {
        return $this->moveId;
	}
	public function getTitle($title) {
        return $this->title;
	}
	public function getDeposit() {
        return $this->deposit;
	}
	public function getLiftOff() {
        return $this->liftOff;
	}
	public function getComment() {
        return $this->comment;
	}
	public function setMoveId($moveId) {
        $this->moveId = $moveId;
        return $this;
	}
	public function setTitle($title) {
        $this->title = $title;
        return $this;
	}
	public function setDeposit($deposit) {
        $this->deposit = $deposit;
        return $this;
	}
	public function setLiftOff($liftOff) {
        $this->liftOff = $liftOff;
        return $this;
	}
	public function setComment($comment) {
        $this->comment = $comment;
        return $this;
	}
	
	
}

?>