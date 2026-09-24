## 2024-09-24 - [DoS via PHP 8 Strict Types in hash_equals]
**Vulnerability:** Passing an array via POST data (e.g., `csrf_token[]=123`) to the `verify_csrf()` function bypassed the `empty()` check but caused a fatal TypeError in `hash_equals()` under PHP 8+, leading to an application crash/Denial of Service.
**Learning:** PHP 8+ functions like `hash_equals()` are strict about parameter types. Relying solely on `empty()` is insufficient for security-critical functions when user input can easily be an array.
**Prevention:** Always explicitly validate the types of user inputs (e.g., using `is_string()`) before passing them to strict PHP 8+ functions to prevent TypeErrors and potential Denial of Service (DoS) attacks.
