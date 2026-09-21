## 2024-05-24 - DoS / TypeError with hash_equals in PHP 8+
**Vulnerability:** The `verify_csrf` function passed `$_POST['csrf_token']` directly to `hash_equals()`. Since an attacker can submit an array (e.g., `csrf_token[]=1`), this could cause `hash_equals()` to throw a `TypeError` in PHP 8+, potentially leading to a Denial of Service.
**Learning:** Functions from `$_POST` and `$_GET` can be arrays, and strict PHP 8 functions will throw fatal `TypeError`s if provided unexpected types instead of silently converting them or returning false.
**Prevention:** Always use type checking functions (like `is_string()`) on variables derived from user input before passing them to type-strict functions like `hash_equals()`, `strlen()`, etc.
