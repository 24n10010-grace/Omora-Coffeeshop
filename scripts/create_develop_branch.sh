#!/usr/bin/env bash
set -euo pipefail

if ! git rev-parse --is-inside-work-tree >/dev/null 2>&1; then
  echo "Jalankan script ini dari dalam clone repository Git." >&2
  exit 1
fi

if [[ -n "$(git status --porcelain)" ]]; then
  echo "Working tree belum bersih. Commit atau stash perubahan terlebih dahulu." >&2
  exit 1
fi

git fetch origin --prune

if git show-ref --verify --quiet refs/heads/develop; then
  git switch develop
  if git show-ref --verify --quiet refs/remotes/origin/develop; then
    git pull --ff-only origin develop
  fi
elif git show-ref --verify --quiet refs/remotes/origin/develop; then
  git switch --track -c develop origin/develop
else
  git switch main
  git pull --ff-only origin main
  git switch -c develop
  git push -u origin develop
fi

echo "Branch aktif: $(git branch --show-current)"
