<nav class="navbar navbar-expand-lg navbar-dark p-4" style="background-color: #023E8A;">
  <a class="navbar-brand" href="index.php">Admin Panel</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="project_offers_submitted.php">Project Offers Submitted </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="profile.php">Profile</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="category_directory.php">Category Directory</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="core/handleForms.php?logoutUserBtn=1">Logout</a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <?php $allCategories = $categoriesObj->getCategories(); ?>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Categories</a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="categoriesDropdown" style="max-height:400px; overflow:auto;">
          <?php foreach ($allCategories as $cat) { ?>
            <div class="dropdown-submenu px-3 py-1">
              <a class="dropdown-item d-flex justify-content-between align-items-center" href="index.php?category_id=<?php echo $cat['category_id']; ?>">
                <?php echo htmlspecialchars($cat['name']); ?>
              </a>
              <?php $subs = $categoriesObj->getSubcategoriesByCategory($cat['category_id']); ?>
              <?php if (!empty($subs)) { ?>
                <div class="pl-3">
                  <?php foreach ($subs as $sub) { ?>
                    <a class="dropdown-item" href="index.php?category_id=<?php echo $cat['category_id']; ?>&subcategory_id=<?php echo $sub['subcategory_id']; ?>">- <?php echo htmlspecialchars($sub['name']); ?></a>
                  <?php } ?>
                </div>
              <?php } ?>
              <div class="dropdown-divider"></div>
            </div>
          <?php } ?>
        </div>
      </li>
    </ul>
  </div>
</nav>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>