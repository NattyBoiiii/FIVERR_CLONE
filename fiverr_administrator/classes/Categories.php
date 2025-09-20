<?php  
require_once 'Database.php';

class Categories extends Database {

    public function createCategory($name, $description) {
		$sql = "INSERT INTO categories (name, description) VALUES (?, ?)";
		return $this->executeNonQuery($sql, [$name, $description]);
    }

    public function updateCategory($category_id, $name, $description) {
		$sql = "UPDATE categories SET name = ?, description = ? WHERE category_id = ?";
		return $this->executeNonQuery($sql, [$name, $description, $category_id]);
    }

    public function deleteCategory($category_id) {
		$this->executeNonQuery("DELETE FROM subcategories WHERE category_id = ?", [$category_id]);
		$sql = "DELETE FROM categories WHERE category_id = ?";
		return $this->executeNonQuery($sql, [$category_id]);
    }

    public function getCategories() {
		$sql = "SELECT * FROM categories ORDER BY name ASC";
		return $this->executeQuery($sql);
    }

    public function getCategoryById($category_id) {
		$sql = "SELECT * FROM categories WHERE category_id = ?";
		return $this->executeQuerySingle($sql, [$category_id]);
    }

    public function createSubcategory($category_id, $name, $description) {
		$sql = "INSERT INTO subcategories (category_id, name, description) VALUES (?, ?, ?)";
		return $this->executeNonQuery($sql, [$category_id, $name, $description]);
    }

    public function getSubcategoriesByCategory($category_id) {
		$sql = "SELECT * FROM subcategories WHERE category_id = ? ORDER BY name ASC";
		return $this->executeQuery($sql, [$category_id]);
    }

    public function updateSubcategory($subcategory_id, $name, $description) {
		$sql = "UPDATE subcategories SET name = ?, description = ? WHERE subcategory_id = ?";
		return $this->executeNonQuery($sql, [$name, $description, $subcategory_id]);
    }

    public function deleteSubcategory($subcategory_id) {
		$sql = "DELETE FROM subcategories WHERE subcategory_id = ?";
		return $this->executeNonQuery($sql, [$subcategory_id]);
    }
}
?>