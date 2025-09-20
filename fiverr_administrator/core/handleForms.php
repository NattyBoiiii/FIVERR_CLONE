<?php  
require_once '../classloader.php';

if (isset($_POST['insertNewUserBtn'])) {
	$username = htmlspecialchars(trim($_POST['username']));
	$email = htmlspecialchars(trim($_POST['email']));
	$contact_number = isset($_POST['contact_number']) ? htmlspecialchars(trim($_POST['contact_number'])) : '';
	$password = trim($_POST['password']);
	$confirm_password = trim($_POST['confirm_password']);

	if (!empty($username) && !empty($email) && !empty($password) && !empty($confirm_password)) {

		if ($password == $confirm_password) {

			if (!$userObj->usernameExists($username)) {

				if ($userObj->registerUser($username, $email, $password, $contact_number, 'administrator')) {
					header("Location: ../login.php");
				}

				else {
					$_SESSION['message'] = "An error occured with the query!";
					$_SESSION['status'] = '400';
					header("Location: ../register.php");
				}
			}

			else {
				$_SESSION['message'] = $username . " as username is already taken";
				$_SESSION['status'] = '400';
				header("Location: ../register.php");
			}
		}
		else {
			$_SESSION['message'] = "Please make sure both passwords are equal";
			$_SESSION['status'] = '400';
			header("Location: ../register.php");
		}
	}
	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../register.php");
	}
}

if (isset($_POST['loginUserBtn'])) {
	$email = trim($_POST['email']);
	$password = trim($_POST['password']);

	if (!empty($email) && !empty($password)) {

		if ($userObj->loginUser($email, $password, 'administrator')) {
			header("Location: ../index.php");
		}
		else {
			$_SESSION['message'] = "Username/password invalid";
			$_SESSION['status'] = "400";
			header("Location: ../login.php");
		}
	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../login.php");
	}

}

if (isset($_GET['logoutUserBtn'])) {
    $userObj->logout();
    header("Location: ../../index.php");
}

if (isset($_POST['createCategoryBtn'])) {
    $name = htmlspecialchars(trim($_POST['name']));
    $description = htmlspecialchars(trim($_POST['description']));
    if (!empty($name)) {
        $categoriesObj->createCategory($name, $description);
    }
    header("Location: ../category_directory.php");
}

if (isset($_POST['updateCategoryBtn'])) {
    $category_id = intval($_POST['category_id']);
    $name = htmlspecialchars(trim($_POST['name']));
    $description = htmlspecialchars(trim($_POST['description']));
    if ($category_id && !empty($name)) {
        $categoriesObj->updateCategory($category_id, $name, $description);
    }
    header("Location: ../category_directory.php");
}

if (isset($_POST['deleteCategoryBtn'])) {
    $category_id = intval($_POST['category_id']);
    if ($category_id) {
        $categoriesObj->deleteCategory($category_id);
    }
    header("Location: ../category_directory.php");
}

if (isset($_POST['createSubcategoryBtn'])) {
    $category_id = intval($_POST['category_id']);
    $name = htmlspecialchars(trim($_POST['name']));
    $description = htmlspecialchars(trim($_POST['description']));
    if ($category_id && !empty($name)) {
        $categoriesObj->createSubcategory($category_id, $name, $description);
    }
    header("Location: ../category_directory.php#cat-" . $category_id);
}

if (isset($_POST['updateSubcategoryBtn'])) {
    $subcategory_id = intval($_POST['subcategory_id']);
    $name = htmlspecialchars(trim($_POST['name']));
    $description = htmlspecialchars(trim($_POST['description']));
    if ($subcategory_id && !empty($name)) {
        $categoriesObj->updateSubcategory($subcategory_id, $name, $description);
    }
    header("Location: ../category_directory.php");
}

if (isset($_POST['deleteSubcategoryBtn'])) {
    $subcategory_id = intval($_POST['subcategory_id']);
    $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;
    if ($subcategory_id) {
        $categoriesObj->deleteSubcategory($subcategory_id);
    }
    $anchor = $category_id ? ("#cat-" . $category_id) : '';
    header("Location: ../category_directory.php" . $anchor);
}

if (isset($_POST['insertOfferBtn']) || (isset($_POST['proposal_id']) && isset($_POST['description']))) {
    $user_id = $_SESSION['user_id'];
    $proposal_id = $_POST['proposal_id'];
    $description = htmlspecialchars($_POST['description']);

    if ($offerObj->userAlreadyOffered($user_id, $proposal_id)) {
        $_SESSION['message'] = "You have already made an offer for this proposal.";
        $_SESSION['status'] = '400';
        header("Location: ../index.php");
        exit();
    }

    if ($offerObj->createOffer($user_id, $description, $proposal_id)) {
        header("Location: ../index.php");
        exit();
    }
}

if (isset($_POST['updateUserBtn'])) {
    $contact_number = htmlspecialchars($_POST['contact_number']);
    $bio_description = htmlspecialchars($_POST['bio_description']);
    if ($userObj->updateUser($contact_number, $bio_description, $_SESSION['user_id'])) {
        header("Location: ../profile.php");
    }
}

if (isset($_POST['updateOfferBtn'])) {
    $description = htmlspecialchars($_POST['description']);
    $offer_id = $_POST['offer_id'];
    if ($offerObj->updateOffer($description, $offer_id)) {
        $_SESSION['message'] = "Offer updated successfully!";
        $_SESSION['status'] = '200';
        header("Location: ../index.php");
    }
}

if (isset($_POST['deleteOfferBtn'])) {
    $offer_id = $_POST['offer_id'];
    if ($offerObj->deleteOffer($offer_id)) {
        $_SESSION['message'] = "Offer deleted successfully!";
        $_SESSION['status'] = '200';
        header("Location: ../index.php");
    }
}
?>