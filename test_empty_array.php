<?php
$postToken = ["foo"];
if (empty($postToken)) {
    echo "empty\n";
} else {
    echo "not empty\n";
    try {
        hash_equals("string", $postToken);
    } catch (TypeError $e) {
        echo "TypeError caught for hash_equals!\n";
    }
}
