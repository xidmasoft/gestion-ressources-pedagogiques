## 2024-05-15 - [Array Injection TypeErrors in PHP 8+]
**Vulnerability:** Input fields like `$_POST['email']` or `$_POST['csrf_token']` could be submitted as arrays (e.g., `email[]=foo`), causing fatal `TypeError` when passed to string-only functions like `trim()`, `password_verify()`, or `hash_equals()`.
**Learning:** PHP 8+ is much stricter with types. Functions that previously warned and returned null now throw fatal exceptions, potentially leading to DoS or application crashes if unhandled.
**Prevention:** Always validate that inputs from `$_GET`, `$_POST`, or `$_REQUEST` are strings using `is_string()` before passing them to strict string functions.
