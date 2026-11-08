---
tags: [code, backend, backend-service]
---

# ResponderService

`backend/app/Services/ResponderService.php`

**Layer:** service · **Module:** Responder

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `__construct` | `protected ResponderRepository $repository` | `` |
| public | `getAll` | `array $filters = [], int $perPage = 15` | `LengthAwarePaginator` |
| public | `getById` | `int $id` | `?Responder` |
| public | `getByIdOrFail` | `int $id` | `Responder` |
| public | `create` | `array $data` | `Responder` |
| public | `update` | `Responder $responder, array $data` | `Responder` |
| public | `delete` | `Responder $responder` | `bool` |
| public | `updateStatus` | `Responder $responder, string $status` | `Responder` |
| public | `getAvailable` | `string $specialization = '', int $limit = 50` | `Collection` |
| public | `getStatistics` | `` | `array` |

## References

- [[docs/code/backend/Responder]]
- [[docs/code/backend/ResponderRepository]]
- [[docs/code/frontend/Pagination]]
- [[docs/code/frontend/app]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
