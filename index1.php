<?php
$video_dir = 'video/'; // Make sure this folder exists and contains your video files
$videos = array_diff(scandir($video_dir), ['.', '..']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Video List -----</title>
</head>
<body>
    <h2>Available Videos-----------</h2>
    <ul>
        <?php foreach ($videos as $video): ?>
            <li>
                <?php echo htmlspecialchars($video); ?>
                <br>
                <video width="320" height="240" controls>
                    <source src="stream.php?file=<?php echo urlencode($video); ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
