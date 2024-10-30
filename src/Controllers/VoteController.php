<?php
require_once '../src/Repositories/VoteRepository.php';

class VoteController {
    private $voteRepository;

    public function __construct($pdo) {
        $this->voteRepository = new VoteRepository($pdo);
    }

    public function recordVote($billId, $userId, $vote) {
        return $this->voteRepository->recordVote($billId, $userId, $vote);
    }

    public function getVotingResults($billId) {
        return $this->voteRepository->getResultsByBillId($billId);
    }
}
?>
