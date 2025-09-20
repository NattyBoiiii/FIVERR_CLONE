<?php require_once 'classloader.php'; ?>
<?php 
if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
}

if (!$userObj->isAdmin()) {
  header("Location: ../freelancer/index.php");
} 
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <style> body { font-family: "Arial"; } </style>
</head>
<body>
  <?php include 'includes/navbar.php'; ?>
  <div class="container-fluid">
    <div class="display-4 text-center">Your Submitted Offers — Projects You Offered On</div>
    <div class="row justify-content-center mt-4">
      <div class="col-md-10">
        <?php 
          $offers = $offerObj->getOffersByUserID($_SESSION['user_id']);
          if (empty($offers)) {
            echo "<div class='alert alert-info'>You have not submitted any offers yet.</div>";
          } else {
            foreach ($offers as $item) { 
        ?>
        <div class="card shadow mb-4">
          <div class="card-body">
            <div class="row">
              <div class="col-md-4">
                <?php if (!empty($item['image'])) { ?>
                  <img src="<?php echo '../images/'.$item['image']; ?>" class="img-fluid" alt="">
                <?php } else { ?>
                  <img src="https://via.placeholder.com/300x200?text=No+Image" class="img-fluid" alt="">
                <?php } ?>
                <p class="mt-2"><strong>Project owner:</strong> <a href="other_profile_view.php?user_id=<?php echo $item['user_id']; ?>"><?php echo htmlspecialchars($item['proposal_owner_username']); ?></a></p>
                <p><small><i>Project posted: <?php echo $item['proposal_date_added']; ?></i></small></p>
              </div>
              <div class="col-md-8">
                <h4>Project description</h4>
                <p><?php echo nl2br(htmlspecialchars($item['description'])); ?></p>
                <p><strong>Budget:</strong> <?php echo number_format($item['min_price']) . " - " . number_format($item['max_price']); ?> PHP</p>
                <hr>
                <h5>Your Offer</h5>
                <p><?php echo nl2br(htmlspecialchars($item['description'] ?? $item['description'])); ?></p>
                <p><small><i>Offered on: <?php echo $item['offer_date_added']; ?></i></small></p>
              </div>
            </div>
          </div>
        </div>
        <?php } } ?>
      </div>
    </div>
  </div>
</body>
</html>