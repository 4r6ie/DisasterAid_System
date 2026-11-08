---
tags: [code, backend, backend-service]
---

# AssistanceRequestService

`backend/app/Services/AssistanceRequestService.php`

**Layer:** service · **Module:** AssistanceRequest

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `__construct` | `protected AssistanceRequestRepository $repository` | `` |
| public | `getAll` | `array $filters = [], int $perPage = 15` | `LengthAwarePaginator` |
| public | `getById` | `int $id` | `?AssistanceRequest` |
| public | `getByIdOrFail` | `int $id` | `AssistanceRequest` |
| public | `create` | `array $data` | `AssistanceRequest` |
| public | `update` | `AssistanceRequest $assistanceRequest, array $data` | `AssistanceRequest` |
| public | `delete` | `AssistanceRequest $assistanceRequest` | `bool` |
| public | `approve` | `AssistanceRequest $assistanceRequest` | `AssistanceRequest` |
| public | `dispatch` | `AssistanceRequest $assistanceRequest` | `AssistanceRequest` |
| public | `complete` | `AssistanceRequest $assistanceRequest` | `AssistanceRequest` |
| public | `reject` | `AssistanceRequest $assistanceRequest, string $reason` | `AssistanceRequest` |
| public | `getStatistics` | `` | `array` |
| protected | `generateRequestNumber` | `` | `string` |

## References

- [[docs/code/backend/AssistanceRequest]]
- [[docs/code/backend/AssistanceRequestRepository]]
- [[docs/code/frontend/Pagination]]
- [[docs/code/frontend/app]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
