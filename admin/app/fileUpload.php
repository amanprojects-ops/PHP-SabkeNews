<?php
session_start();
include_once("../../system/connection.php");
if (isset($_POST["fileUploadBtn"])) {
    $fileTitle = mysqli_real_escape_string($conn, $_POST["fileTitle_Keyword"]);
    $selectFileTitle = mysqli_query($conn, "SELECT file_title FROM filelist WHERE file_title = '{$fileTitle}'");
    if (mysqli_num_rows($selectFileTitle) > 0) {
        $_SESSION['error'] = 'File are already existed.';
        echo 'File are already existed.';
        echo "<script>window.location.href='../dashboard/new-upload-file.php';</script>";
    } else {
        if (!empty($_FILES['fileUpload']['name'])) {
            $error = array();
            $file_name = $_FILES['fileUpload']['name'];
            $file_size = $_FILES['fileUpload']['size'];
            $file_type = $_FILES['fileUpload']['type'];
            $file_tem_loc = $_FILES['fileUpload']['tmp_name'];
            // extract file exetention
            @$file_exetention = end(explode('.', $file_name));
            $exetention_lists = array("jpg", "jpeg", "png", "webp", "pdf");
            if (in_array($file_exetention, $exetention_lists) === false) {
                $error[] = "This extension file not allowed, Please choose a JPG, PNG, JPEG, WEBP & PDF file.";
            }
            if ($file_size > 1048576) {
                $error[] = "File Size Must Be 1MB Or Lower.";
            }
            $target_loc = "../../assets/files/" . $file_name;
            if (empty($error) == true) {
                move_uploaded_file($file_tem_loc, $target_loc);
            } else {
                $_SESSION['error'] = $error[0];
                //echo $error[0];
                echo "<script>window.location.href='../dashboard/new-upload-file.php';</script>";
            }
        } else {
            $_SESSION['error'] = "File are not selected.";
            echo "<script>window.location.href='../dashboard/new-upload-file.php';</script>";
        }
        $last_update = date("Y-m-d H:i:s");
        $insertQ = "INSERT INTO filelist (file_title,file_name,file_last_update)VALUES('{$fileTitle}','{$file_name}','{$last_update}')";
        if (mysqli_query($conn, $insertQ)) {
            $_SESSION["success"] = "File Uploaded Successful.";
            echo "<script>window.location.href='../dashboard/view-upload-file.php';</script>";
        } else {
            $_SESSION["error"] = "Query Fail";
            echo "<script>window.location.href='../dashboard/new-upload-file.php';</script>";
        }
    }
}
?>
