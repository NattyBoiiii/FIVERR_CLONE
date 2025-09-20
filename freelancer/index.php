<?php require_once 'classloader.php'; ?>
<?php 
if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
}

if ($userObj->isClient()) {
  header("Location: ../client/index.php");
} 
?>
<!doctype html>
  <html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <style>
      body {
        font-family: "Arial";
      }
    </style>
  </head>
  <body>
    <?php include 'includes/navbar.php'; ?>
    <div class="container-fluid">
      <div class="display-4 text-center">Hello there and welcome! <span class="text-success"><?php echo $_SESSION['username']; ?></span>. Add Proposal Here!</div>
      <div class="row">
        <div class="col-md-5">
          <div class="card mt-4 mb-4">
            <div class="card-body">
              <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                  <?php  
                  if (isset($_SESSION['message']) && isset($_SESSION['status'])) {

                    if ($_SESSION['status'] == "200") {
                      echo "<h1 style='color: green;'>{$_SESSION['message']}</h1>";
                    }

                    else {
                      echo "<h1 style='color: red;'>{$_SESSION['message']}</h1>"; 
                    }

                  }
                  unset($_SESSION['message']);
                  unset($_SESSION['status']);
                  ?>
                  <h1 class="mb-4 mt-4">Add Proposal Here!</h1>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Description</label>
                    <input type="text" class="form-control" name="description" required>
                  </div>
                  <div class="form-group">
                    <label for="category">Category</label>
                    <select class="form-control" name="category_id" id="category" required>
                      <option value="">Select a Category</option>
                      <?php $categories = $categoriesObj->getCategories(); ?>
                      <?php foreach ($categories as $category) { ?>
                        <option value="<?php echo $category['category_id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="subcategory">Subcategory</label>
                    <select class="form-control" name="subcategory_id" id="subcategory" required>
                      <option value="">Select a Category first</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Minimum Price</label>
                    <input type="number" class="form-control" name="min_price" required>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Max Price</label>
                    <input type="number" class="form-control" name="max_price" required>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Image</label>
                    <input type="file" class="form-control" name="image" required>
                    <input type="submit" class="btn btn-primary float-right mt-4" name="insertNewProposalBtn">
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="col-md-7">
          <?php 
            $selectedCategoryId = isset($_GET['category_id']) ? intval($_GET['category_id']) : null;
            $selectedSubcategoryId = isset($_GET['subcategory_id']) ? intval($_GET['subcategory_id']) : null;
            if (!empty($selectedCategoryId) || !empty($selectedSubcategoryId)) {
              $getProposals = $proposalObj->getProposalsFiltered($selectedCategoryId, $selectedSubcategoryId);
            } else {
              $getProposals = $proposalObj->getProposals();
            }
          ?>
          <?php foreach ($getProposals as $proposal) { ?>
          <div class="card shadow mt-4 mb-4">
            <div class="card-body">
              <h2><a href="other_profile_view.php?user_id=<?php echo $proposal['user_id']; ?>"><?php echo $proposal['username']; ?></a></h2>
              <?php if (!empty($proposal['category_name'])) { ?>
                <div class="mb-2">
                  <span class="badge badge-primary"><?php echo htmlspecialchars($proposal['category_name']); ?></span>
                  <?php if (!empty($proposal['subcategory_name'])) { ?>
                    <span class="badge badge-secondary"><?php echo htmlspecialchars($proposal['subcategory_name']); ?></span>
                  <?php } ?>
                </div>
              <?php } ?>
              <img src="<?php echo '../images/' . $proposal['image']; ?>" alt="" class="img-fluid">
              <p class="mt-4"><i><?php echo $proposal['proposals_date_added']; ?></i></p>
              <p class="mt-2"><?php echo $proposal['description']; ?></p>
              <h4><i><?php echo number_format($proposal['min_price']) . " - " . number_format($proposal['max_price']); ?> PHP</i></h4>
              <div class="float-right">
            <a href="#">Check out services</a>
              </div>
            </div>
          </div>
          <?php } ?>
        </div>
      </div>
    </div>
    <script>
    $(document).ready(function() {
        $('#category').change(function() {
            var categoryId = $(this).val();
            var subcategorySelect = $('#subcategory');
            
            subcategorySelect.empty();
            subcategorySelect.append('<option value="">Select a Subcategory</option>');
            
            if (categoryId) {
                $.ajax({
                    url: 'get_subcategories.php',
                    method: 'POST',
                    data: { category_id: categoryId },
                    dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            $.each(data.subcategories, function(index, subcategory) {
                                subcategorySelect.append('<option value="' + subcategory.subcategory_id + '">' + subcategory.name + '</option>');
                            });
                        }
                    },
                    error: function() {
                        console.log('Error loading subcategories');
                    }
                });
            }
        });
    });
    </script>
  </body>
</html>