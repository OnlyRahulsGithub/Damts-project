<?php
$video_path = '../video/'; // Adjust path as needed

// Check if the file exists
if (!file_exists($video_path)) {
    header("HTTP/1.1 404 Not Found");
    echo "File not found!";
    exit;
}

// Get file size
$filesize = filesize($video_path);
$mime = "video/mp4"; // Set the MIME type (adjust if using other formats)

header("Content-Type: $mime");
header("Accept-Ranges: bytes");

// Handle Range requests for streaming
if (isset($_SERVER['HTTP_RANGE'])) {
    $range = $_SERVER['HTTP_RANGE'];
    list(, $range) = explode("=", $range, 2);
    list($start, $end) = explode("-", $range);

    $start = intval($start);
    $end = ($end == "") ? ($filesize - 1) : intval($end);
    $length = ($end - $start) + 1;

    header("HTTP/1.1 206 Partial Content");
    header("Content-Length: $length");
    header("Content-Range: bytes $start-$end/$filesize");

    $fp = fopen($video_path, "rb");
    fseek($fp, $start);
    echo fread($fp, $length);
    fclose($fp);
} else {
    // Normal file delivery
    header("Content-Length: $filesize");
    readfile($video_path);
}
exit;
?>
