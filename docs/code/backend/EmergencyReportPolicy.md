---
tags: [code, backend, backend-policy]
---

# EmergencyReportPolicy

`backend/app/Policies/EmergencyReportPolicy.php`

**Layer:** policy · **Module:** EmergencyReport

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `viewAny` | `User $user` | `bool` |
| public | `view` | `User $user, EmergencyReport $report` | `bool` |
| public | `create` | `User $user` | `bool` |
| public | `update` | `User $user, EmergencyReport $report` | `bool` |
| public | `delete` | `User $user, EmergencyReport $report` | `bool` |
| public | `assignResponder` | `User $user, EmergencyReport $report` | `bool` |
| public | `updateStatus` | `User $user, EmergencyReport $report` | `bool` |

## References

- [[docs/code/backend/EmergencyReport]]
- [[docs/code/backend/User]]
- [[docs/code/frontend/app]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
