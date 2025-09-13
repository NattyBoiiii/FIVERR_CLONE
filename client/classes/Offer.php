<?php  
require_once 'Database.php';
class Offer extends Database {

    public function createOffer($user_id, $description, $proposal_id) {
        $sql = "INSERT INTO offers (user_id, description, proposal_id) VALUES (?, ?, ?)";
        return $this->executeNonQuery($sql, [$user_id, $description, $proposal_id]);
    }

    public function getOffers($offer_id = null) {
        if ($offer_id) {
            $sql = "SELECT * FROM offers WHERE offer_id = ?";
            return $this->executeQuerySingle($sql, [$offer_id]);
        }
        $sql = "SELECT 
                    offers.*, fiverr_clone_users.*, 
                    offers.date_added AS offer_date_added
                FROM offers JOIN fiverr_clone_users ON 
                offers.user_id = fiverr_clone_users.user_id 
                ORDER BY offers.date_added DESC";
        return $this->executeQuery($sql);
    }


    public function getOffersByProposalID($proposal_id) {
        $sql = "SELECT 
                    offers.*, fiverr_clone_users.*, 
                    offers.date_added AS offer_date_added 
                FROM Offers 
                JOIN fiverr_clone_users ON 
                    offers.user_id = fiverr_clone_users.user_id
                WHERE proposal_id = ? 
                ORDER BY Offers.date_added DESC";
        return $this->executeQuery($sql, [$proposal_id]);
    }

    public function updateOffer($description, $offer_id) {
        $sql = "UPDATE Offers SET description = ? WHERE Offer_id = ?";
        return $this->executeNonQuery($sql, [$description, $offer_id]);
    }
    

    /**
     * Deletes an Offer.
     * @param int $id The Offer ID to delete.
     * @return int The number of affected rows.
     */
    public function deleteOffer($id) {
        $sql = "DELETE FROM Offers WHERE Offer_id = ?";
        return $this->executeNonQuery($sql, [$id]);
    }

    public function userAlreadyOffered($user_id, $proposal_id) {
        $db = $this->pdo; 
        $stmt = $db->prepare("SELECT COUNT(*) FROM offers WHERE user_id = ? AND proposal_id = ?");
        $stmt->execute([$user_id, $proposal_id]);
        return $stmt->fetchColumn() > 0;
    }

    public function getOffersByUserID($user_id) {
        $sql = "SELECT 
                    offers.*, 
                    proposals.*, 
                    fiverr_clone_users.username AS proposal_owner_username,
                    offers.date_added AS offer_date_added,
                    proposals.date_added AS proposal_date_added
                FROM offers
                JOIN proposals ON offers.proposal_id = proposals.proposal_id
                JOIN fiverr_clone_users ON proposals.user_id = fiverr_clone_users.user_id
                WHERE offers.user_id = ?
                ORDER BY offers.date_added DESC";
        return $this->executeQuery($sql, [$user_id]);
    }
}
?>