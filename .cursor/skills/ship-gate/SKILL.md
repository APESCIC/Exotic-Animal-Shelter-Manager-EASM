---
name: ship-gate
description: Gated ship lifecycle — pre-commit verification, when-to-commit guidance, next-actions menus, optional GitHub Release. Use when work is ready to commit, PR checks are green, after merge to main, or when the user asks to ship or release. No hosting deploy.
---

# Ship-gate

Use this skill for shipping gates on **Exotic Animal Shelter Manager**. There is no agent-driven deploy: operators install on their own hosting (zip/clone, Composer, `/install`).

## Gate reply format

End the turn with:

```markdown
## Next actions
**Recommended:** <one clear choice>

1. ...
2. ...
```

Do not proceed past a gate without an explicit user pick (or a prior explicit order that covers that gate).

## Pre-commit verification (required)

Run **before** the commit gate. On Windows, source `scripts/local/use-laragon.ps1` first. Do **not** run Pint during feature work — only here.

| Step | Command / check | Pass criteria |
|------|-----------------|---------------|
| Tests | `composer test` | Exit 0 (full suite) |
| Lint | `composer lint` | Exit 0; if fail → one `composer format` → re-lint once |
| UI smoke | Laragon up; browser or HTTP | Only when HTTP/views/routes changed: `/health` ok; changed path loads |

If any step fails: report output, fix or stop. **Do not** offer commit options. WIP checkpoint commits only when the user explicitly asks to save broken work.

Also confirm: no secrets (`.env`, credentials) staged; slice work on the `cursor/...` branch is complete for this slice.

## When committing is best

Commit when **all** apply:

1. Pre-commit verification passed (above).
2. Feature/fix work on the `cursor/...` branch is complete for this slice (or user explicitly requested a WIP checkpoint).
3. No secrets staged.

Then present the **commit gate** before the first push / PR update for that slice.

## Commit gate

**Recommended** when the slice is reviewable: commit and open/update PR.

```powershell
git status
git diff
git log -5 --oneline
```

Follow the repo commit protocol (user-approved only via this gate or an earlier explicit order). Then push and open/update the PR with `Fixes #<n>` when option 2 is chosen.

## After PR CI is green

```powershell
gh pr view --json number,url,headRefName,mergeable
gh pr checks --json name,bucket,state,workflow,link
```

Watch pending checks:

```powershell
gh pr checks --watch --fail-fast
```

**Recommended** when checks are green: merge.

```powershell
gh pr merge --merge --delete-branch
```

Prefer `--squash` only if the user asks. Confirm merge landed on `main` before the release gate.

## After merge — no deploy

Do **not** run Cloudron workflows, `workflow_dispatch` deploys, or remote hosting updates. CI on `main` is tests only (`.github/workflows/ci.yml`).

Shelters install themselves: download/clone → `composer install` → document root `public/` → `/install`. Agents may point at README install steps; they do not deploy.

Confirm main CI for the merge commit if useful:

```powershell
gh run list --branch main --workflow "CI" --limit 3
```

## After merge — GitHub Release gate

**Recommended:** skip release unless the user already asked to cut one.

If the user chooses create release:

1. Confirm tag/version with the user if unclear. Today version lives in `APP_VERSION` / `config('app.version')` (see `.env.example`). There is no root `VERSION` or `releases.json` yet — do not invent MyAPES release-metadata scaffolding unless that is a separate task.
2. Skip if the tag or release already exists:

```powershell
gh release view "v0.1.0"
```

3. Otherwise create (replace version, title, and notes with the agreed public-safe text from the PR/issue):

```powershell
gh release create "v0.1.0" --title "<short public title>" --notes "<summary from PR/issue>" --target main
```

Use public-safe notes only (no credentials, personal data, or exploitable security detail).

## Completion

Only after merge is verified, and after any chosen release step succeeds, give the completion confirmation (PR URL/number, merge SHA, target branch, release URL if created, linked issue closed/updated). Offer to archive the agent; do not archive silently.
