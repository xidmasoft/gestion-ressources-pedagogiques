## 2024-05-24 - CSRF Verification TypeError DoS
**Vulnerability:** `verify_csrf` function used `hash_equals` directly on `$_POST['csrf_token']` without checking if it was a string. Sending an array like `csrf_token[]=1` caused a PHP Fatal Error (TypeError), leading to a Denial of Service.
**Learning:** PHP 8+ functions like `hash_equals` have strict typing. Failing to sanitize/type-check raw superglobal inputs (`$_POST`, `$_GET`) before passing them to typed functions creates easy DoS vectors.
**Prevention:** Always validate the type of user input before passing it to native PHP functions that expect specific types. Added `!is_string($sessionToken) || !is_string($postToken)` check before `hash_equals`.
