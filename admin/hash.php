<?php
$password = "ZooZ";
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
echo "<h3>Your Hashed Password:</h3>";
echo "<div style='background:#111; color:#00f2ff; padding:15px; border-radius:8px; border:1px solid #333; word-break: break-all;'>";
echo $hashed_password;
echo "</div>";
echo "<p style='color:white;'></p>";
