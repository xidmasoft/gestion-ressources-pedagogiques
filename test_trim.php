<?php
try {
    trim(['a']);
    echo "Success\n";
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
