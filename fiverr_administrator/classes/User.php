<?php  

require_once 'Database.php';
/**
 * Class for handling User-related operations for Administrator role.
 * Inherits CRUD methods from the Database class.
 */
class User extends Database {

	public function startSession() {
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}
	}

	public function usernameExists($username) {
		$sql = "SELECT COUNT(*) as username_count FROM fiverr_clone_users WHERE username = ?";
		$count = $this->executeQuerySingle($sql, [$username]);
		return $count && $count['username_count'] > 0;
	}

	public function registerUser($username, $email, $password, $contact_number, $role = 'administrator') {
		$hashed_password = password_hash($password, PASSWORD_DEFAULT);
		$sql = "INSERT INTO fiverr_clone_users (username, email, password, role, contact_number) VALUES (?, ?, ?, ?, ?)";
		try {
			$this->executeNonQuery($sql, [$username, $email, $hashed_password, $role, $contact_number]);
			return true;
		} catch (\PDOException $e) {
			return false;
		}
	}

	public function loginUser($email, $password, $requiredRole = 'administrator') {
		$sql = "SELECT user_id, username, password, role FROM fiverr_clone_users WHERE email = ?";
		$user = $this->executeQuerySingle($sql, [$email]);

		if ($user && password_verify($password, $user['password']) && $user['role'] === $requiredRole) {
			$this->startSession();
			$_SESSION['user_id'] = $user['user_id'];
			$_SESSION['username'] = $user['username'];
			$_SESSION['role'] = $user['role'];
			return true;
		}
		return false;
	}

	public function isLoggedIn() {
		$this->startSession();
		return isset($_SESSION['user_id']);
	}

	public function isAdmin() {
		$this->startSession();
		return isset($_SESSION['role']) && $_SESSION['role'] === 'administrator';
	}

	public function logout() {
		$this->startSession();
		session_unset();
		session_destroy();
	}

	public function getUsers($id = null) {
		if ($id) {
			$sql = "SELECT * FROM fiverr_clone_users WHERE user_id = ?";
			return $this->executeQuerySingle($sql, [$id]);
		}
		$sql = "SELECT * FROM fiverr_clone_users";
		return $this->executeQuery($sql);
	}

	public function updateUser($contact_number, $bio_description, $user_id, $display_picture="") {
		if (empty($display_picture)) {
			$sql = "UPDATE fiverr_clone_users SET contact_number = ?, bio_description = ? WHERE user_id = ?";
			return $this->executeNonQuery($sql, [$contact_number, $bio_description, $user_id]);
		}
	}
}

?>