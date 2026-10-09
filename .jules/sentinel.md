
## 2026-10-09 - [Fix CSRF DoS via TypeError]
**Vulnerability:** A Denial of Service (DoS) vulnerability existed in `verify_csrf()` where an array passed to `$_POST['csrf_token']` (e.g. `csrf_token[]=1`) would cause `hash_equals()` to throw a `TypeError`, crashing the application instead of gracefully returning false.
**Learning:** Strict typing in PHP 8+ means standard functions like `hash_equals()` will throw a `TypeError` if given unexpected types (like an array). This can be easily exploited by an attacker submitting an array via POST.
**Prevention:** Always validate that user input from superglobals (like `$_POST`, `$_GET`) are of the expected primitive type using `is_string()`, `is_int()`, etc. before passing them to strict PHP functions.
