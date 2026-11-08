---
tags: [code, home, moc]
---

# Code map

Related notes: [[README]] · [[docs/code/backend]] · [[docs/code/frontend]] · [[docs/code/infrastructure]]

Every source file in the repository, one note each, linked by imports and shared names.

## Hubs

| Hub | Contents |
| --- | --- |
| [[docs/code/backend]] | 141 PHP classes under `backend/app/` |
| [[docs/code/frontend]] | 41 TypeScript/TSX modules under `frontend/src/` |
| [[docs/code/infrastructure]] | Docker images and server configuration |

## Layers

| Layer | Backend | Frontend |
| --- | --- | --- |
| Entry points | Controllers, Providers | pages, routes |
| Logic | Services, Repositories | services, hooks, stores |
| Data | Models, Requests, Resources | types, utils |
| Rules | Policies | — |

## Conventions

- Backend notes are named after the class; frontend notes after the file.
- Every note lists its methods or exports, so a function can be found from the graph.
- "References" lists other notes whose names appear in the file's source.

## Regenerating

After changing the code, rebuild every note:

```bash
python3 infrastructure/scripts/generate-code-map.py
```
