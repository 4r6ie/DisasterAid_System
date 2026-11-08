---
tags: [code, backend, backend-policy]
---

# AssistanceRequestPolicy

`backend/app/Policies/AssistanceRequestPolicy.php`

**Layer:** policy · **Module:** AssistanceRequest

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `viewAny` | `User $user` | `bool` |
| public | `view` | `User $user, AssistanceRequest $request` | `bool` |
| public | `create` | `User $user` | `bool` |
| public | `update` | `User $user, AssistanceRequest $request` | `bool` |
| public | `delete` | `User $user, AssistanceRequest $request` | `bool` |
| public | `approve` | `User $user, AssistanceRequest $request` | `bool` |
| public | `dispatch` | `User $user, AssistanceRequest $request` | `bool` |
| public | `complete` | `User $user, AssistanceRequest $request` | `bool` |
| public | `reject` | `User $user, AssistanceRequest $request` | `bool` |

## References

- [[docs/code/backend/AssistanceRequest]]
- [[docs/code/backend/User]]
- [[docs/code/frontend/app]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
