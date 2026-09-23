<?php
echo "<pre>";

echo "Checking FFmpeg location...\n\n";
echo shell_exec("which ffmpeg");

echo "\n\nChecking version...\n\n";
echo shell_exec("ffmpeg -version");

echo "</pre>";
?>
