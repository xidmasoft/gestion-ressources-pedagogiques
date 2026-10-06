## 2024-05-24 - [Fix TypeError DoS in verify_csrf]
**Vulnerability:** The application was passing `$_POST['csrf_token']` directly to `hash_equals()` in `core/helpers.php` without ensuring it was a string. PHP 8 strict types caused a Fatal Error (TypeError) when an array like `csrf_token[]=foo` was passed, leading to a potential Denial of Service (DoS).
**Learning:** Functions that enforce strict types (like `hash_equals()`, `trim()`, `htmlspecialchars()`) will crash the application if unexpected types are passed. An attacker can exploit this via HTTP parameters (e.g., passing arrays where strings are expected).
**Prevention:** Always validate user input types explicitly using functions like `is_string()` or `is_scalar()` before passing them to native PHP functions.
