## 2024-05-18 - [Fix] Prevent TypeError DoS in verify_csrf
**Vulnerability:** The `verify_csrf` function was directly passing user input `$_POST[csrf_token]` to the strict `hash_equals` function. If a user provided an array via `csrf_token[]=...`, `hash_equals` threw a fatal TypeError in PHP 8+, causing a 500 error.
**Learning:** Always explicitly validate the data types of user input (e.g. `is_string()`) before passing them to native PHP functions that expect a specific type, especially strict comparison functions like `hash_equals()`.
**Prevention:** Always sanitize/validate input types for array inputs. Check `is_string` for values sent in HTTP headers or POST bodies before passing them into native functions.
