<?php
$ch = curl_init("https://www.googleapis.com/oauth2/v4/token");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);

if (curl_errno($ch)) {
    echo "CURL ERROR: " . curl_error($ch);
} else {
    echo "✅ Connection OK";
}
curl_close($ch);
