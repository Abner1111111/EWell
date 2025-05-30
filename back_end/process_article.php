<?php
session_start();
include '../db_connection/database.php';
include 'session.php';

// Function to generate a URL-friendly slug
function generateSlug($title) {
    $slug = strtolower($title);
    $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    $slug = trim($slug, '-');
    return $slug;
}

// Handle AJAX requests
if (isset($_POST['action'])) {
    $response = ['success' => false];
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    switch ($_POST['action']) {
        case 'activate':
            $query = "UPDATE news_articles SET status = 'Active' WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            $response['success'] = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            break;

        case 'deactivate':
            // Check if article is archived
            $checkQuery = "SELECT status FROM news_articles WHERE id = ?";
            $stmt = mysqli_prepare($conn, $checkQuery);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $article = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);

            // If article is archived, don't allow deactivation
            if ($article && $article['status'] === 'Archived') {
                $response['success'] = false;
                $response['error'] = 'Cannot deactivate an archived article. Please activate it first.';
                break;
            }

            $query = "UPDATE news_articles SET status = 'Inactive' WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            $response['success'] = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            break;

        case 'archive':
            // Check if article is in draft status
            $checkQuery = "SELECT status FROM news_articles WHERE id = ?";
            $stmt = mysqli_prepare($conn, $checkQuery);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $article = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);

            // If article is in draft, don't allow archiving
            if ($article && $article['status'] === 'Draft') {
                $response['success'] = false;
                $response['error'] = 'Cannot archive a draft article. Please publish it first.';
                break;
            }

            $query = "UPDATE news_articles SET status = 'Archived' WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            $response['success'] = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            break;

        case 'delete':
            // First get the image URL to delete the file
            $query = "SELECT image_url FROM news_articles WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $article = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);

            // Delete the image file if it exists
            if ($article && $article['image_url']) {
                $image_path = '../' . $article['image_url'];
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }

            // Delete the article
            $query = "DELETE FROM news_articles WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            $response['success'] = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            break;

        case 'update':
            $title = mysqli_real_escape_string($conn, $_POST['title']);
            $content = mysqli_real_escape_string($conn, $_POST['content']);
            $category = mysqli_real_escape_string($conn, $_POST['category']);
            $status = mysqli_real_escape_string($conn, $_POST['status']);
            $publication_date = mysqli_real_escape_string($conn, $_POST['publication_date']);
            $summary = mysqli_real_escape_string($conn, $_POST['summary']);
            $author = mysqli_real_escape_string($conn, $_POST['author']);
            $news_link = mysqli_real_escape_string($conn, $_POST['news_link']);
            $slug = generateSlug($title);

            // Handle image upload
            $image_url = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // First get the old image to delete it
                $query = "SELECT image_url FROM news_articles WHERE id = ?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "i", $id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $old_article = mysqli_fetch_assoc($result);
                mysqli_stmt_close($stmt);

                // Delete the old image if it exists
                if ($old_article && $old_article['image_url']) {
                    $old_image_path = '../' . $old_article['image_url'];
                    if (file_exists($old_image_path)) {
                        unlink($old_image_path);
                    }
                }

                // Upload new image
                $upload_dir = '../uploads/news/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $file_name = uniqid() . '.' . $file_extension;
                $target_path = $upload_dir . $file_name;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                    $image_url = 'uploads/news/' . $file_name;
                }
            }

            // Update the article
            if ($image_url !== null) {
                $query = "UPDATE news_articles SET 
                         title = ?, content = ?, category = ?, status = ?, 
                         publication_date = ?, summary = ?, image_url = ?, slug = ?,
                         author = ?, news_link = ? 
                         WHERE id = ?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "ssssssssssi", $title, $content, $category, $status, 
                                     $publication_date, $summary, $image_url, $slug, 
                                     $author, $news_link, $id);
            } else {
                $query = "UPDATE news_articles SET 
                         title = ?, content = ?, category = ?, status = ?, 
                         publication_date = ?, summary = ?, slug = ?,
                         author = ?, news_link = ? 
                         WHERE id = ?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "sssssssssi", $title, $content, $category, $status, 
                                     $publication_date, $summary, $slug, 
                                     $author, $news_link, $id);
            }

            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['success_message'] = "Article updated successfully!";
            } else {
                $_SESSION['error_message'] = "Error updating article: " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);

            header("Location: ../admin/News.php");
            exit();
            break;
    }

    if (isset($response)) {
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
}

// Handle form submission for new article
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $publication_date = mysqli_real_escape_string($conn, $_POST['publication_date']);
    $summary = mysqli_real_escape_string($conn, $_POST['summary']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $news_link = mysqli_real_escape_string($conn, $_POST['news_link']);
    $slug = generateSlug($title);

    // Handle image upload
    $image_url = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/news/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $file_name = uniqid() . '.' . $file_extension;
        $target_path = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            $image_url = 'uploads/news/' . $file_name;
        }
    }

    // Insert the article
    $query = "INSERT INTO news_articles (title, content, category, status, publication_date, author, summary, image_url, slug, news_link) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssssssssss", $title, $content, $category, $status, $publication_date, $author, $summary, $image_url, $slug, $news_link);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success_message'] = "Article created successfully!";
    } else {
        $_SESSION['error_message'] = "Error creating article: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
    
    // Redirect back to the news management page
    header("Location: ../admin/News.php");
    exit();
}
?> 