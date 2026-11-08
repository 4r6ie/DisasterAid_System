---
tags: [code, backend, backend-policy]
---

# DisasterIncidentPolicy

`backend/app/Policies/DisasterIncidentPolicy.php`

**Layer:** policy · **Module:** DisasterIncident

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `viewAny` | `User $user` | `bool` |
| public | `view` | `User $user, DisasterIncident $incident` | `bool` |
| public | `create` | `User $user` | `bool` |
| public | `update` | `User $user, DisasterIncident $incident` | `bool` |
| public | `delete` | `User $user, DisasterIncident $incident` | `bool` |
| public | `assignResponder` | `User $user, DisasterIncident $incident` | `bool` |
| public | `updateStatus` | `User $user, DisasterIncident $incident` | `bool` |
| public | `updateCasualties` | `User $user, DisasterIncident $incident` | `bool` |

## References

- [[docs/code/backend/DisasterIncident]]
- [[docs/code/backend/User]]
- [[docs/code/frontend/app]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
