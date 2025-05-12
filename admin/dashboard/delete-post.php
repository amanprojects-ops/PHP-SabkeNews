<?php
session_start();
if (!isset($_GET['check']) || !isset($_GET['postid'])) {
    $_SESSION['error'] = "Invalid request parameters.";
    header("Location: ../dashboard/view-post.php");
    exit();
}

require_once("../../system/connection.php");

try {
    $postid = filter_var(base64_decode($_GET['postid']), FILTER_SANITIZE_NUMBER_INT);
    
    // Prepare statements to prevent SQL injection
    $checkActiveStmt = $conn->prepare("SELECT post_id FROM post WHERE post_id = ? AND postStatus = 'Y'");
    $checkActiveStmt->bind_param("i", $postid);
    $checkActiveStmt->execute();
    
    if ($checkActiveStmt->get_result()->num_rows > 0) {
        $_SESSION['warning'] = "Cannot delete an active post.";
        header("Location: ../dashboard/view-post.php");
        exit();
    }
    
    $checkDraftStmt = $conn->prepare("SELECT post_id FROM post WHERE post_id = ? AND postStatus = 'W'");
    $checkDraftStmt->bind_param("i", $postid);
    $checkDraftStmt->execute();
    
    if ($checkDraftStmt->get_result()->num_rows > 0) {
        $_SESSION['warning'] = "Cannot delete a draft post. Please reject it first.";
        header("Location: ../dashboard/view-post.php");
        exit();
    }
    
    // Get image filename before deletion
    $imageStmt = $conn->prepare("SELECT post_img FROM post WHERE post_id = ?");
    $imageStmt->bind_param("i", $postid);
    $imageStmt->execute();
    $result = $imageStmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $imagePath = "../images/" . $row['post_img'];
        if (file_exists($imagePath) && is_file($imagePath)) {
            unlink($imagePath);
        }
    }
    
    // Delete the post
    $deleteStmt = $conn->prepare("DELETE FROM post WHERE post_id = ?");
    $deleteStmt->bind_param("i", $postid);
    
    if ($deleteStmt->execute()) {
        $_SESSION['success'] = "Post deleted successfully.";
    } else {
        $_SESSION['error'] = "Failed to delete post. Please try again.";
    }
    
} catch (Exception $e) {
    $_SESSION['error'] = "An error occurred while processing your request.";
} finally {
    header("Location: ../dashboard/view-post.php");
    exit();
}


?>