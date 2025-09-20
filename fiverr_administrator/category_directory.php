<?php  
require_once 'classloader.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Directory</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<?php include 'includes/navbar.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Categories</h3>
        <button class="btn btn-primary" data-toggle="modal" data-target="#createCategoryModal">Add Category</button>
    </div>
    <div class="list-group">
        <?php $categories = $categoriesObj->getCategories(); ?>
        <?php if (!empty($categories)) { ?>
            <?php foreach ($categories as $category) { ?>
                <div class="list-group-item" id="cat-<?php echo $category['category_id']; ?>">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1"><?php echo htmlspecialchars($category['name']); ?></h5>
                            <small class="text-muted"><?php echo htmlspecialchars($category['description']); ?></small>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-info mr-2" data-toggle="collapse" data-target="#subs-<?php echo $category['category_id']; ?>">Subcategories</button>
                            <button class="btn btn-sm btn-secondary mr-2" data-toggle="modal" data-target="#editCategoryModal" data-id="<?php echo $category['category_id']; ?>" data-name="<?php echo htmlspecialchars($category['name']); ?>" data-description="<?php echo htmlspecialchars($category['description']); ?>">Edit</button>
                            <form action="core/handleForms.php" method="POST" class="d-inline" onsubmit="return confirm('Delete this category? This will also delete its subcategories.');">
                                <input type="hidden" name="category_id" value="<?php echo $category['category_id']; ?>">
                                <button type="submit" name="deleteCategoryBtn" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                    <div class="collapse mt-3" id="subs-<?php echo $category['category_id']; ?>">
                        <div class="mb-2">
                            <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#createSubcategoryModal" data-category_id="<?php echo $category['category_id']; ?>">Add Subcategory</button>
                        </div>
                        <?php $subs = $categoriesObj->getSubcategoriesByCategory($category['category_id']); ?>
                        <?php if (!empty($subs)) { ?>
                            <ul class="list-group">
                                <?php foreach ($subs as $sub) { ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong><?php echo htmlspecialchars($sub['name']); ?></strong>
                                            <div class="text-muted small"><?php echo htmlspecialchars($sub['description']); ?></div>
                                        </div>
                                        <div>
                                            <button class="btn btn-sm btn-secondary mr-2" data-toggle="modal" data-target="#editSubcategoryModal" data-subcategory_id="<?php echo $sub['subcategory_id']; ?>" data-name="<?php echo htmlspecialchars($sub['name']); ?>" data-description="<?php echo htmlspecialchars($sub['description']); ?>">Edit</button>
                                            <form action="core/handleForms.php" method="POST" class="d-inline" onsubmit="return confirm('Delete this subcategory?');">
                                                <input type="hidden" name="subcategory_id" value="<?php echo $sub['subcategory_id']; ?>">
                                                <input type="hidden" name="category_id" value="<?php echo $category['category_id']; ?>">
                                                <button type="submit" name="deleteSubcategoryBtn" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </li>
                                <?php } ?>
                            </ul>
                        <?php } else { ?>
                            <div class="text-muted">No subcategories yet.</div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="alert alert-info">No categories yet. Create one!</div>
        <?php } ?>
    </div>
</div>
<div class="modal fade" id="createCategoryModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="core/handleForms.php" method="POST">
        <div class="modal-body">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" name="createCategoryBtn" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
 </div>
<div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="core/handleForms.php" method="POST">
        <input type="hidden" name="category_id" id="edit_category_id">
        <div class="modal-body">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" id="edit_category_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" id="edit_category_description" class="form-control" rows="3"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" name="updateCategoryBtn" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
 </div>
<div class="modal fade" id="createSubcategoryModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Subcategory</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="core/handleForms.php" method="POST">
        <input type="hidden" name="category_id" id="create_sub_category_id">
        <div class="modal-body">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" name="createSubcategoryBtn" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
 </div>
<div class="modal fade" id="editSubcategoryModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Subcategory</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="core/handleForms.php" method="POST">
        <input type="hidden" name="subcategory_id" id="edit_subcategory_id">
        <div class="modal-body">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" id="edit_subcategory_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" id="edit_subcategory_description" class="form-control" rows="3"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" name="updateSubcategoryBtn" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
 </div>
<script>
$('#editCategoryModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget);
  $('#edit_category_id').val(button.data('id'));
  $('#edit_category_name').val(button.data('name'));
  $('#edit_category_description').val(button.data('description'));
});

$('#createSubcategoryModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget);
  $('#create_sub_category_id').val(button.data('category_id'));
});

$('#editSubcategoryModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget);
  $('#edit_subcategory_id').val(button.data('subcategory_id'));
  $('#edit_subcategory_name').val(button.data('name'));
  $('#edit_subcategory_description').val(button.data('description'));
});
</script>
</body>
</html>
