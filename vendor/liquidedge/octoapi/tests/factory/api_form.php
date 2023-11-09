<?php

    require_once '../../vendor/autoload.php';

    session_start();

    function build_id($id){
        $filename = basename($_SERVER["SCRIPT_FILENAME"], '.php');
        return "{$filename}_{$id}";
    };

    function request_value($id, $default = false){
        $id = build_id($id);
        $cookie_value = isset($_COOKIE[$id]) ? $_COOKIE[$id] : $default;
        $session_value = isset($_SESSION[$id]) ? $_SESSION[$id] : $cookie_value;
        $value = isset($_REQUEST[$id]) ? $_REQUEST[$id] : $session_value;

        return $value ? $value : $default;
    }

    function set_value($id, $value){
        $id = build_id($id);
        $_SESSION[$id] = $value;
        setcookie($id, $value, time()+60*60*24*30);
    }

    /**
     * @return \octoapi\OctoApi
     */
    function get_api(){
        $url = request_value("url");
        $username = request_value("username");
        $password = request_value("password");

        if(!$url) return;
        if(!$username) return;
        if(!$password) return;

        return \octoapi\OctoApi::make([
            "url" => $url,
            "username" => $username,
            "password" => $password,
        ]);

    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Octo API Tests</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container my-5">
        <div class="row">
            <div class="col">
                <form method="post">
                    <div class="row align-items-center mb-2">
                        <div class="col">
                            <h3>API Test</h3>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-xl-6'>
                            <?php

                                $fn_input = function($label, $id, $default = false){

                                    $value = request_value($id, $default);
                                    set_value($id, $value);

                                    $id = build_id($id);

                                    echo "
                                        <div class='row align-items-center mb-2'>
                                            <div class='col-3'><label class='col-form-label'>{$label}</label></div>
                                            <div class='col'><input class='form-control' type='text' id='{$id}' name='{$id}' value='{$value}'></div>
                                        </div>
                                    ";
                                };

                                $fn_input("API URL", "url");
                                $fn_input("API Username", "username");
                                $fn_input("API Password", "password");
                                $fn_input("Page Size", "page_size");
                                $fn_input("Offset", "offset");
                                $fn_input("Filter ID's", "filter_id");

                            ?>
                            <div class="row mb-2">
                                <div class="col text-end"><button class="btn btn-primary" type="submit">Submit</button></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
    </script>
</body>

</html>
