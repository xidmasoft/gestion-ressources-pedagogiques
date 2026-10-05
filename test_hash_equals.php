<?php
try {
    hash_equals("string", ["array"]);
} catch (TypeError $e) {
    echo "TypeError caught for hash_equals!\n";
}

try {
    trim(["array"]);
} catch (TypeError $e) {
    echo "TypeError caught for trim!\n";
}
