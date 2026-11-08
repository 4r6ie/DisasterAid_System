"""Generate an Obsidian code map for the DisasterAid monorepo.

Emits one note per source file (backend PHP + frontend TS/TSX) under docs/code/,
plus hub notes, with wiki-links derived from imports and shared names.
"""

import os
import re
import glob

BACKEND_APP = "backend/app"
FRONTEND_SRC = "frontend/src"
OUT = "docs/code"

LAYER_BACKEND = {
    "Controllers": "controller",
    "Services": "service",
    "Repositories": "repository",
    "Models": "model",
    "Policies": "policy",
    "Requests": "request",
    "Resources": "resource",
    "Providers": "provider",
    "seeders": "seeder",
    "factories": "factory",
}

LAYER_FRONTEND = {
    "pages": "page",
    "components": "component",
    "hooks": "hook",
    "services": "service",
    "stores": "store",
    "routes": "routes",
    "utils": "util",
    "types": "type",
    "layouts": "layout",
    "src": "entry",
}

MODULES = [
    "DisasterIncident", "EmergencyReport", "AssistanceRequest", "EvacuationCenter",
    "ReliefItem", "InventoryTransaction", "Responder", "Volunteer",
    "VolunteerAssignment", "AffectedPerson", "Announcement", "AuditLog",
    "Dashboard", "Auth", "User",
]


def layer_of(path, mapping):
    parts = path.replace("\\", "/").split("/")
    for part in reversed(parts):
        if part in mapping:
            return mapping[part]
    return "module"


def module_of(name):
    for m in sorted(MODULES, key=len, reverse=True):
        if name == m or name.startswith(m):
            return m
    return ""


def parse_php(path):
    src = open(path, encoding="utf-8").read()
    kindm = re.search(r"\b(class|interface|trait|enum)\s+(\w+)", src)
    if not kindm:
        return None
    kind, cls = kindm.group(1), kindm.group(2)
    ns = re.search(r"^namespace\s+([^;]+);", src, re.M)
    extends = re.search(r"\bextends\s+([\w\\]+)", src)
    implements = re.search(r"\bimplements\s+([^\{]+)", src)
    methods = []
    for mm in re.finditer(
        r"(?:(public|protected|private)\s+)?(?:(static)\s+)?function\s+(\w+)\s*\(([^)]*)\)\s*(?::\s*([^\{;]+))?",
        src,
    ):
        vis, static, name, params, ret = mm.groups()
        methods.append({
            "vis": vis or "public",
            "static": bool(static),
            "name": name,
            "params": re.sub(r"\s+", " ", params).strip(),
            "return": (ret or "").strip(),
        })
    uses = [u.strip() for u in re.findall(r"^use\s+([^;]+);", src, re.M)]
    return {
        "kind": kind,
        "cls": cls,
        "ns": ns.group(1) if ns else "",
        "extends": extends.group(1) if extends else "",
        "implements": re.sub(r"\s+", " ", implements.group(1)).strip() if implements else "",
        "methods": methods,
        "uses": uses,
        "src": src,
        "path": path,
    }


def parse_ts(path):
    src = open(path, encoding="utf-8").read()
    exports = []
    for mm in re.finditer(
        r"export\s+(?:default\s+)?(?:async\s+)?(function|const|class|interface|type|enum)\s+(\w+)", src
    ):
        exports.append({"kind": mm.group(1), "name": mm.group(2)})
    imports = []
    for mm in re.finditer(r"import\s+(?:type\s+)?([^'\";]+?)\s+from\s+['\"]([^'\"]+)['\"]", src):
        imports.append({"what": mm.group(1).strip(), "from": mm.group(2)})
    return {"exports": exports, "imports": imports, "src": src, "path": path}


def collect():
    backend = []
    backend_files = (
        glob.glob(f"{BACKEND_APP}/**/*.php", recursive=True)
        + glob.glob("backend/database/seeders/*.php")
        + glob.glob("backend/database/factories/*.php")
    )
    for path in sorted(backend_files):
        info = parse_php(path)
        if info:
            layer = layer_of(path, LAYER_BACKEND)
            info["layer"] = layer
            backend.append(info)

    frontend = []
    seen_names = {}
    for path in sorted(glob.glob(f"{FRONTEND_SRC}/**/*.ts", recursive=True)
                       + glob.glob(f"{FRONTEND_SRC}/**/*.tsx", recursive=True)):
        info = parse_ts(path)
        layer = layer_of(path, LAYER_FRONTEND)
        info["layer"] = layer
        base = os.path.splitext(os.path.basename(path))[0]
        parent = os.path.basename(os.path.dirname(path.replace("\\", "/")))
        name = base
        if name in seen_names or base == "index":
            name = f"{parent}-{base}"
            counter = 2
            while name in seen_names:
                name = f"{parent}-{base}-{counter}"
                counter += 1
        seen_names[name] = path
        info["name"] = name
        frontend.append(info)

    return backend, frontend


def note_name_backend(info):
    return info["cls"]


def note_name_frontend(info):
    return info["name"]


def linkable_names(backend, frontend):
    names = {}
    for b in backend:
        names[b["cls"].lower()] = f"docs/code/backend/{b['cls']}"
    for f in frontend:
        names[f["name"].lower()] = f"docs/code/frontend/{f['name']}"
    return names


def find_links(text, names, exclude):
    found = set()
    for lower, target in names.items():
        if lower == exclude:
            continue
        if re.search(r"\b" + re.escape(lower) + r"\b", text, re.I):
            found.add(target)
    return sorted(found)


def render_backend(info, names, all_names):
    cls = info["cls"]
    layer = info["layer"]
    module = module_of(cls)
    lines = []
    lines.append("---")
    lines.append("tags: [code, backend, backend-%s]" % layer)
    lines.append("---")
    lines.append("")
    lines.append(f"# {cls}")
    lines.append("")
    lines.append(f"`{info['path'].replace(chr(92), '/')}`")
    lines.append("")
    bits = [f"**Layer:** {layer}"]
    if module:
        bits.append(f"**Module:** {module}")
    lines.append(" · ".join(bits))
    lines.append("")
    if info["extends"]:
        lines.append(f"Extends `{info['extends']}`.")
        lines.append("")
    if info["implements"]:
        lines.append(f"Implements `{info['implements']}`.")
        lines.append("")
    if info["methods"]:
        lines.append("## Methods")
        lines.append("")
        lines.append("| Visibility | Method | Parameters | Returns |")
        lines.append("| --- | --- | --- | --- |")
        for m in info["methods"]:
            ret = m["return"].replace("|", "\\|")
            params = m["params"].replace("|", "\\|")
            lines.append(
                f"| {m['vis']}{' static' if m['static'] else ''} | `{m['name']}` | `{params}` | `{ret}` |"
            )
        lines.append("")
    deps = find_links(info["src"], names, cls.lower())
    if deps:
        lines.append("## References")
        lines.append("")
        for d in deps:
            lines.append(f"- [[{d}]]")
        lines.append("")
    lines.append("## Hubs")
    lines.append("")
    lines.append("- [[Code Map]] · [[docs/code/backend]]")
    lines.append("")
    return "\n".join(lines)


def render_frontend(info, names, all_names):
    name = info["name"]
    layer = info["layer"]
    lines = []
    lines.append("---")
    tags = ["code", "frontend", "frontend-%s" % layer]
    if info["path"].endswith(".tsx"):
        tags.append("tsx")
    lines.append("tags: [%s]" % ", ".join(tags))
    lines.append("---")
    lines.append("")
    lines.append(f"# {name}")
    lines.append("")
    lines.append(f"`{info['path'].replace(chr(92), '/')}`")
    lines.append("")
    lines.append(f"**Layer:** {layer}")
    lines.append("")
    if info["exports"]:
        lines.append("## Exports")
        lines.append("")
        lines.append("| Kind | Name |")
        lines.append("| --- | --- |")
        for e in info["exports"]:
            lines.append(f"| {e['kind']} | `{e['name']}` |")
        lines.append("")
    if info["imports"]:
        lines.append("## Imports")
        lines.append("")
        for i in info["imports"]:
            lines.append(f"- `{i['what']}` from `{i['from']}`")
        lines.append("")
    deps = find_links(info["src"], names, name.lower())
    if deps:
        lines.append("## References")
        lines.append("")
        for d in deps:
            lines.append(f"- [[{d}]]")
        lines.append("")
    lines.append("## Hubs")
    lines.append("")
    lines.append("- [[Code Map]] · [[docs/code/frontend]]")
    lines.append("")
    return "\n".join(lines)


def main():
    backend, frontend = collect()
    names = linkable_names(backend, frontend)

    os.makedirs(f"{OUT}/backend", exist_ok=True)
    os.makedirs(f"{OUT}/frontend", exist_ok=True)

    for info in backend:
        with open(f"{OUT}/backend/{info['cls']}.md", "w", encoding="utf-8") as fh:
            fh.write(render_backend(info, names, names))
    for info in frontend:
        with open(f"{OUT}/frontend/{info['name']}.md", "w", encoding="utf-8") as fh:
            fh.write(render_frontend(info, names, names))

    # ---- hubs ----
    by_layer = {}
    for info in backend:
        by_layer.setdefault(info["layer"], []).append(info)
    lines = [
        "---", "tags: [code, backend, hub]", "---", "",
        "# Backend code map", "",
        "`backend/app/` — Laravel application layer.", "",
        "Related notes: [[Code Map]] · [[docs/code/frontend]] · [[docs/architecture/overview]]",
        "", "## By layer", "",
    ]
    for layer in ["controller", "service", "repository", "model", "policy", "request", "resource", "provider", "seeder", "factory"]:
        items = by_layer.get(layer, [])
        if not items:
            continue
        lines.append(f"### {layer.capitalize()} ({len(items)})")
        lines.append("")
        for info in sorted(items, key=lambda i: i["cls"]):
            lines.append(f"- [[docs/code/backend/{info['cls']}]] — `{info['path']}`")
        lines.append("")
    open(f"{OUT}/backend.md", "w", encoding="utf-8").write("\n".join(lines))

    by_layer_fe = {}
    for info in frontend:
        by_layer_fe.setdefault(info["layer"], []).append(info)
    lines = [
        "---", "tags: [code, frontend, hub]", "---", "",
        "# Frontend code map", "",
        "`frontend/src/` — React application layer.", "",
        "Related notes: [[Code Map]] · [[docs/code/backend]] · [[docs/architecture/overview]]",
        "", "## By layer", "",
    ]
    for layer in ["page", "component", "hook", "service", "store", "routes", "util", "type", "layout"]:
        items = by_layer_fe.get(layer, [])
        if not items:
            continue
        lines.append(f"### {layer.capitalize()} ({len(items)})")
        lines.append("")
        for info in sorted(items, key=lambda i: i["name"]):
            lines.append(f"- [[docs/code/frontend/{info['name']}]] — `{info['path']}`")
        lines.append("")
    open(f"{OUT}/frontend.md", "w", encoding="utf-8").write("\n".join(lines))

    lines = [
        "---", "tags: [code, home, moc]", "---", "",
        "# Code map", "",
        "Related notes: [[README]] · [[docs/code/backend]] · [[docs/code/frontend]] · [[docs/code/infrastructure]]",
        "",
        "Every source file in the repository, one note each, linked by imports and shared names.",
        "",
        "## Hubs", "",
        "| Hub | Contents |",
        "| --- | --- |",
        f"| [[docs/code/backend]] | {len(backend)} PHP classes under `backend/app/` |",
        f"| [[docs/code/frontend]] | {len(frontend)} TypeScript/TSX modules under `frontend/src/` |",
        "| [[docs/code/infrastructure]] | Docker images and server configuration |",
        "",
        "## Layers", "",
        "| Layer | Backend | Frontend |",
        "| --- | --- | --- |",
        "| Entry points | Controllers, Providers | pages, routes |",
        "| Logic | Services, Repositories | services, hooks, stores |",
        "| Data | Models, Requests, Resources | types, utils |",
        "| Rules | Policies | — |",
        "",
        "## Conventions", "",
        "- Backend notes are named after the class; frontend notes after the file.",
        "- Every note lists its methods or exports, so a function can be found from the graph.",
        "- \"References\" lists other notes whose names appear in the file's source.",
        "",
        "## Regenerating", "",
        "After changing the code, rebuild every note:", "",
        "```bash",
        "python3 infrastructure/scripts/generate-code-map.py",
        "```",
        "",
    ]
    open(f"{OUT}/Code Map.md", "w", encoding="utf-8").write("\n".join(lines))

    lines = [
        "---", "tags: [code, infrastructure, operations]", "---", "",
        "# Infrastructure code map", "",
        "Related notes: [[Code Map]] · [[docs/operations/docker]] · [[docs/setup/configuration]]",
        "",
        "## Docker images", "",
        "| File | Purpose |",
        "| --- | --- |",
        "| `infrastructure/docker/php/Dockerfile` | PHP 8.4 CLI + composer + pdo_pgsql, builds `backend/` |",
        "| `infrastructure/docker/node/Dockerfile` | Node 20 + `npm ci`, runs the Vite dev server |",
        "| `infrastructure/docker/nginx/` | Nginx configuration (placeholder) |",
        "| `infrastructure/docker/postgres/` | PostgreSQL initialization (placeholder) |",
        "",
        "## Orchestration", "",
        "| File | Purpose |",
        "| --- | --- |",
        "| `docker-compose.yml` | postgres + backend + frontend on one network |",
        "| `backend/router.php` | Front-controller router used by `php -S` |",
        "| `backend/bootstrap/app.php` | Middleware, rate limit, JSON error envelope |",
        "| `frontend/vite.config.ts` | Dev server and `/api` proxy |",
        "| `frontend/tailwind.config.js` | Theme and content paths |",
        "| `.dockerignore` | Keeps node_modules, vendor and .git out of the build context |",
        "| `.gitignore` | Ignores dependencies, env files, build output |",
        "",
    ]
    open(f"{OUT}/infrastructure.md", "w", encoding="utf-8").write("\n".join(lines))

    print(f"backend notes: {len(backend)}")
    print(f"frontend notes: {len(frontend)}")
    print(f"total: {len(backend) + len(frontend) + 4}")


if __name__ == "__main__":
    main()
