<?php
    // Config
    $metadata_path = dirname(__FILE__).'/.metadata';
    $uploads_path = dirname(__FILE__).'/uploads';
    $ignore_upload_path = true; // Always upload files to the $uploads_path.
    $auto_mkdir = false; // Automatically create subfolders if they don't exist
                         // instead of throwing an error.

    $max_files = 5;
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
        function safe_path($path): string {
            return str_ireplace(['./', '../', '.\\', '..\\'], '', $path);
        }
        function upload_file($file, $path): string {
            global $metadata_path, $uploads_path, $ignore_upload_path;
            $uploadpath = "";
            switch ($file['error']) {
                case UPLOAD_ERR_OK:
                    http_response_code(200);
                    break;
                case UPLOAD_ERR_INI_SIZE:
                    http_response_code(400);
                    return "Error: file too large\n";
                case UPLOAD_ERR_FORM_SIZE:
                    http_response_code(400);
                    return "Error: file too large\n";
                case UPLOAD_ERR_PARTIAL:
                    http_response_code(400);
                    return "Error: file was only partially uploaded\n";
                case UPLOAD_ERR_NO_FILE:
                    http_response_code(400);
                    return "Error: no file\n";
                case UPLOAD_ERR_NO_TMP_DIR:
                    http_response_code(500);
                    return "Server error: no temp directory\n";
                case UPLOAD_ERR_CANT_WRITE:
                    http_response_code(500);
                    return "Server error: can\'t write to disk\n";
                case UPLOAD_ERR_EXTENSION:
                    http_response_code(500);
                    return "Server error: A PHP extension stopped the file upload\n";
                default:
                    http_response_code(500);
                    return "Unknown error #".dechex($file["error"][0])."\n";
            }
            if ($ignore_upload_path || $path == '')
            {
                $uploadpath = $uploads_path . "/" . $file['name'][0];
            }
            else
            {
                if (is_dir($path))
                    $uploadpath = $path . "/" . $file['name'][0];
                else
                    $uploadpath = $path;
            }
            return move_uploaded_file($file['tmp_name'][0],$uploadpath) ? $uploadpath : 'Error';
        }
        if (isset($_GET['path']))
            $path = $_GET['path'];
        else
            $path = '';
        $method = $_SERVER['REQUEST_METHOD'];
        $action = '';

        if (isset($_GET['action']))
            $action = strtolower($_GET['action']);
        elseif (isset($_POST['action']))
            $action = strtolower($_POST['action']);
        else
            $action = strtolower($method);

        if ($method == 'POST') // Upload a file
        {
            $result = [];
            if (!is_dir($path)) {
                $path = basename($path);
            }
            $filecount = 1;
            foreach ($_FILES as $filename => $file) {
                if ($filecount > $max_files)
                    break;
                echo $filename.': ';
                upload_file($file, $path);
                $filecount++;
            }
        } elseif (strtolower($action) == 'upload_form') {//Show the upload form
            echo '<form action="" method="post" enctype="multipart/form-data">';
            echo '<p>File upload form:<br/>';
            if ($path === '') echo '<input type="text" name="path"/><br/>';
            echo '<div id="files">';
            echo '<p><input type="file" name="file" /></p>';
            echo '</div>';
            echo '<script>';
            echo 'var fileinputs = 1;';
            echo 'var maxfileinputs = '.strval($max_files).';';
            echo 'function addFileInput(){';
            echo 'if(fileinputs >= maxfileinputs) {';
            echo 'document.getElementById("addFileBtn").disabled = true;';
            echo 'return;';
            echo '}';
            echo 'document.getElementById(\'files\').innerHTML += \'<p>';
            echo '<input type=\\"file\\" name=\\"file\\" /></p>\';';
            echo 'fileinputs += 1;';
            echo 'if(fileinputs >= maxfileinputs) {';
            echo 'document.getElementById("addFileBtn").disabled = true;';
            echo 'return;';
            echo '}';
            echo '}';
            echo '</script>';
            echo '<button onclick="addFileInput()" id="addFileBtn"';
            echo 'type="button">Add file</button>&nbsp;&nbsp;';
            echo '<input type="submit" value="Send" />';
            echo '</p>';
            echo '</form>';
        } elseif ($method == 'DELETE')
        {
            // Delete the file
            if(unlink($path)) {
                http_response_code(200);
                echo '<p>Success.</p>';
            }
            else
            {
                http_response_code(500);
                echo '<p>Failure.</p>';
            }
        } else {
            $userpath = $uploads_path.safe_path($path);
            $files = scandir($userpath);
            echo '<p><a href="?action=upload_form&path=">Upload file</a></p>';
            echo '<table>';
            foreach ($files as $file) {
                $filePath = $dirPath . '/' . $file;
                if (is_file($filePath)) {
                    echo "<tr><td><a href=\"?action=download&path=\">" . $file;
                    echo "</a></td></tr>";
                }
            }
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