## 2024-10-04 - [PHP 8 TypeError DoS]
**Vulnerability:** Passing array inputs (e.g., `email[]=foo`) to string functions like `trim()` or `hash_equals()` in PHP 8 throws a fatal `TypeError`, leading to a Denial of Service (HTTP 500) and potential information disclosure if errors are displayed.
**Learning:** In PHP 8+, internal string functions are strict. Unvalidated user input from `$_POST` or `$_GET` can easily cause fatal errors if an attacker passes an array instead of a scalar string.
**Prevention:** Always use `is_string()` or `is_scalar()` to explicitly validate the type of user input arrays (`$_POST`, `$_GET`, `$_COOKIE`) before passing them to native PHP string functions.
