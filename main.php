<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        $path = $_GET['path'];
        $method = $_SERVER['REQUEST_METHOD'];
        $action = '';

        if (isset($_GET['Action']))
            $action = $_GET['Action'];
        elseif (isset($_POST['Action']))
            $action = $_POST['Action'];
        else {
            $action = $method;
        }

        if ($method == 'POST')
        {
            $entityBody = file_get_contents('php://input');
            echo $entityBody;
        }

        // if ($method == 'POST')
        // {
        //     if ($_FILES[])
        // }
        // if ($method == 'DELETE')
        // {
        //     // Delete the file
        //     if(unlink($path)) {
        //         http_response_code(200);
        //         echo 'Success.';
        //     }
        //     else
        //     {
        //         http_response_code(500);
        //         echo 'Failure.';
        //     }
        // }
    ?>
</body>
</html>