<?php
namespace Repositories;

class VoteRepository {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    // Store the vote in the database
    public function storeVote($billId, $userId, $vote) {
        $query = "INSERT INTO votes (bill_id, user_id, vote) VALUES (?, ?, ?)";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("iis", $billId, $userId, $vote);
        return $stmt->execute();
    }

    // Retrieve voting results for a specific bill
    public function getVotingResults($billId) {
        $query = "SELECT vote, COUNT(*) as count FROM votes WHERE bill_id = ? GROUP BY vote";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $billId);
        $stmt->execute();
        $result = $stmt->get_result();

        // Initialize vote counts
        $voteCounts = [
            'For' => 0,
            'Against' => 0,
            'Abstain' => 0,
        ];

        // Populate vote counts based on database results
        while ($row = $result->fetch_assoc()) {
            $voteCounts[$row['vote']] = $row['count'];
        }

        return $voteCounts;
    }

    // Check if a specific user has already voted for a specific bill
    public function hasUserVoted($billId, $userId) {
        $query = "SELECT COUNT(*) as count FROM votes WHERE bill_id = ? AND user_id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("ii", $billId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        // Return true if the count is greater than 0, meaning the user has already voted
        return $row['count'] > 0;
    }
}
