<?php

use \App\DB;
use \App\CONFIG;

function handleFormSubmit(&$form_error)
{
    if (isset($_POST['submit'])) {
        $form = isset($_POST['form']) ? $_POST['form'] : "no-form-field";
        switch ($form) {
            case "no-form-field";
                $form_error = "No form field set for this form can't handle";
                break;
            case 'db-form': {
                    $db_host = "localhost";
                    // $db_host = "localhost";
                    $db_name = $_POST["db-name"];
                    $db_username = $_POST["db-username"];
                    $db_password = $_POST["db-password"];
                    try {
                        $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
                    } catch (\Exception $e) {
                        $form_error = $e->getMessage();
                        break;
                    }

                    file_put_contents(APP_CONFIG_PATH . "db.php", "<?php
    // require_once APP_CONFIG_PATH.\"DB.php\";
    \$db_host = '{$db_host}';
    \$db_name = '{$db_name}';
    \$db_username = '{$db_username}';
    \$db_password = '{$db_password}';

    DB::connect($db_host, $db_username, $db_password, $db_name);

                    ");

                    redirectTo("install.php");
                }
                break;
            case 'site-config-form': {
                    require_once APP_CONFIG_PATH . "db.php";
                    // Handle form submission for site configuration
                    $sitename = $_POST['sitename'];
                    $site_email = $_POST['site_email'];

                    // Save configuration to the config table
                    $config_data = [
                        'sitename' => $sitename,
                        'site_email' => $site_email
                        // Add more configuration parameters as needed
                    ];
                    CONFIG::set('site', $config_data);
                }
            default:
                $form_error = "Not handle $form";
                break;
        }
    }
}

function db_form()
{
?>
    <form method="POST">

        <div class="row mb-3">
            <label for="db-name" class="col-sm-2 col-form-label">DB name <span class="text-danger">*</span></label>
            <div class="col-sm-10">
                <input required type="text" class="form-control" name="db-name" id="db-name">
            </div>
        </div>

        <div class="row mb-3">
            <label for="db-username" class="col-sm-2 col-form-label">DB username <span class="text-danger">*</span></label>
            <div class="col-sm-10">
                <input required type="text" class="form-control" name="db-username" id="db-username">
            </div>
        </div>

        <div class="row mb-3">
            <label for="db-password" class="col-sm-2 col-form-label">DB Password</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="db-password" name="db-password">
            </div>
        </div>

        <input type="text" name="form" value="db-form" />
        <input type="submit" class="btn btn-primary" value="Save" name="submit" />
    </form>
<?php
}


function setupTablesAndData()
{

    $tables = [];

    $files = glob(__DIR__ . "/setup/tables/*.table.php");

    foreach ($files as $file) {
        include $file;
    }
    foreach ($tables as $tableName => $table) {
        if (!DB::tableExists($tableName)) {
            DB::createTable(
                $tableName,
                $table['fields'],
                isset($table['config']) ? $table['config'] : [],
            );
            if (isset($table['data']) && count($table['data'])) {
                $fieldsNames = join(", ", array_keys($table['fields']));
                $insertQuery = "INSERT INTO `$tableName` ($fieldsNames) VALUES \n";

                $insertQuery .= join(", \n", array_map(function ($row) { // join rows
                    return "(" . join(", ", array_map(function ($value) { // join values
                        if ($value) {
                            return is_string($value) ? "'$value'" : $value;
                        } else {
                            return "null";
                        }
                    }, $row)) . ")";
                }, $table['data']));

                DB::query($insertQuery);
            }
        }
    }
}

function site_config_form()
{
    // Fetch existing configuration data if it exists
    $existing_config = CONFIG::get('site', []);

    // Pre-fill form fields with existing configuration data
    $sitename = isset($existing_config['sitename']) ? $existing_config['sitename'] : '';
    $site_email = isset($existing_config['site_email']) ? $existing_config['site_email'] : '';

?>
    <form method="POST">
        <div class="mb-3">
            <label for="sitename" class="form-label">Site Name</label>
            <input type="text" class="form-control" id="sitename" name="sitename" value="<?php echo htmlspecialchars($sitename); ?>" required>
        </div>
        <div class="mb-3">
            <label for="site_email" class="form-label">Site Email</label>
            <input type="email" class="form-control" id="site_email" name="site_email" value="<?php echo htmlspecialchars($site_email); ?>" required>
        </div>
        <input type="hidden" name="form" value="site-config-form">
        <input type="submit" class="btn btn-primary" value="Save" name="submit" />
    </form>
<?php
}
