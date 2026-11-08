---
tags: [code, backend, backend-model]
---

# Announcement

`backend/app/Models/Announcement.php`

**Layer:** model · **Module:** Announcement

Extends `Model`.

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `getActivitylogOptions` | `` | `LogOptions` |
| public | `scopePublished` | `$query` | `` |
| public | `scopeByType` | `$query, string $type` | `` |
| public | `scopeByPriority` | `$query, string $priority` | `` |
| public | `scopeForAudience` | `$query, string $audience` | `` |
| public | `disasterIncident` | `` | `` |
| public | `creator` | `` | `` |
| public | `getIsActiveAttribute` | `` | `bool` |

## References

- [[docs/code/backend/DisasterIncident]]
- [[docs/code/backend/User]]
- [[docs/code/frontend/app]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
