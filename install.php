<?php



// $connn = mysqli_connect("localhost", "root", '');
// $query = mysqli_query($connn, "DROP DATABASE seo");
// $query = mysqli_query($connn, "CREATE DATABASE seo");
// exit;
require_once "vendor/autoload.php"; // Composer autoloader

require_once "app/init.php";
require_once "app/install-fns.php";


use \App\DB;
use \App\CONFIG;

$step = "db_form";
if (file_exists(APP_CONFIG_PATH . "db.php")) {
    require_once APP_CONFIG_PATH . "db.php";

    $conn = DB::getConn();

    if (!$conn) {
        $step = "db_error";
    } else {
        setupTablesAndData();
        $step = "site_config";
        if (CONFIG::get('site')) {
            echo "Already Setup";
            exit;
        }
    }
}
handleFormSubmit($form_error);


$form_error = "";



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body>

    <div class="container pt-5">
        <h1 class="mb-4">Database setup</h1>

        <?php
        if ($form_error) {
            echo "<div class='alert alert-danger'>Error: $form_error</div>";
        }

        switch ($step) {
            case "db_form":
                db_form();
                break;
            case "db_error":
                echo "<div class='alert alert-danger'>Database config error " . DB::$connError . "</div>";
                break;
            case "db_setup":

                break;
            case "site_config":
                site_config_form();
                break;
            default:
                echo "<div class='alert alert-danger'>Unkown step $step</div>";
        }

        ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>

</html>