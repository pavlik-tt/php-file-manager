<?php
    // Config
    $metadata_path = dirname(__FILE__).'/.metadata';
    $uploads_path = dirname(__FILE__).'/uploads';
    $ignore_upload_path = true; // Always upload files to the $uploads_path.
    $auto_mkdir = false; // Automatically create subfolders if they don't exist
                         // instead of throwing an error.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        function random_hex_string($length = 10) {
            $result = "";
            for ($i=0; $i < $length; $i++) { 
                $hexbyte = random_int(0,255);
                if ($hexbyte < 16)
                    $result .= "0"+dechex($hexbyte);
                else
                    $result .= dechex($hexbyte);
            }
            return $result;
        }
        function upload_file($file, $path): bool {
            global $metadata_path, $uploads_path, $ignore_upload_path;
            $upload_path = "";
            if ($ignore_upload_path || $path == '')
            {
                $upload_path = $uploads_path . "/" . $file['name'][0];
            }
            else
            {
                if (is_dir($path))
                {
                    $upload_path = $path . "/" . $file['name'][0];
                }
                else
                {
                    $upload_path = $path;
                }
            }
            return move_uploaded_file($file['tmp_name'][0], $upload_path);
        }
        if (isset($_GET['path']))
            $path = $_GET['path'];
        else
            $path = '';
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
            $result = [];
            if (!is_dir($path)) {
                $path = basename($path);
            }
            foreach ($_FILES as $file) {
                $result[] = upload_file($file, $path);
            }
        } elseif (strtolower($action) == 'upload_form') {
            echo '<form action="" method="post" enctype="multipart/form-data">';
            echo '<p>File upload form:<br/>';
            echo '<input type="text" name="path"/><br/>';
            echo '<input type="file" name="pictures[]" /><br/>';
            echo '<input type="submit" value="Send" />';
            echo '</p>';
            echo '</form>';
        } else {

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