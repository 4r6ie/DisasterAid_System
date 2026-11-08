---
tags: [code, backend, backend-controller]
---

# AuthController

`backend/app/Http/Controllers/API/V1/AuthController.php`

**Layer:** controller · **Module:** Auth

Extends `Controller`.

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `register` | `RegisterRequest $request` | `JsonResponse` |
| public | `login` | `LoginRequest $request` | `JsonResponse` |
| public | `logout` | `Request $request` | `JsonResponse` |
| public | `me` | `Request $request` | `JsonResponse` |
| public | `forgotPassword` | `ForgotPasswordRequest $request` | `JsonResponse` |
| public | `resetPassword` | `ResetPasswordRequest $request` | `JsonResponse` |
| public | `changePassword` | `ChangePasswordRequest $request` | `JsonResponse` |
| public | `updateProfile` | `UpdateProfileRequest $request` | `JsonResponse` |

## References

- [[docs/code/backend/ChangePasswordRequest]]
- [[docs/code/backend/Controller]]
- [[docs/code/backend/ForgotPasswordRequest]]
- [[docs/code/backend/LoginRequest]]
- [[docs/code/backend/RegisterRequest]]
- [[docs/code/backend/ResetPasswordRequest]]
- [[docs/code/backend/UpdateProfileRequest]]
- [[docs/code/backend/User]]
- [[docs/code/backend/UserResource]]
- [[docs/code/frontend/app]]
- [[docs/code/frontend/http]]
- [[docs/code/frontend/resources]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
