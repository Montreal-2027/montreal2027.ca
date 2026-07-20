#!/bin/sh

# --- DYNAMIC NAME DETECTION ---
# In POSIX sh, $0 is the only standard variable.
# Fallback to 'setup-script' if $0 is just the shell name (common when sourced).
raw_name=$(basename -- "$0")
case "$raw_name" in
    sh|dash|bash|zsh|ksh) SCRIPT_NAME="this script" ;;
    *) SCRIPT_NAME="$raw_name" ;;
esac

# --- GUARDRAIL 1: Project Root (Logic Check) ---
if [ ! -d ".git" ]; then
    printf "Error: %s must be run from the repository root.\n" "$SCRIPT_NAME" >&2
    exit 1
fi

PROJECT_ROOT=$(pwd)
HOOKS_DIR="$PROJECT_ROOT/.githooks"
POST_MERGE_FILE="$HOOKS_DIR/post-merge"

# --- GUARDRAIL 2: Idempotent Worktree Management ---
git worktree prune
if ! git worktree list | grep -q "graphify-out"; then
    if [ -d "graphify-out" ]; then
        rm -rf graphify-out
    fi
    printf "Registering 'graphify-out' worktree...\n"
    git worktree add graphify-out graph-data || {
        printf "Error: Ensure 'graph-data' branch exists on remote.\n" >&2
        exit 1
    }
fi

# --- GUARDRAIL 3: Idempotent Hook Injection ---
mkdir -p "$HOOKS_DIR"

BLOCK_START="# --- GRAPHIFY-CONTEXT-START ---"
BLOCK_END="# --- GRAPHIFY-CONTEXT-END ---"
BLOCK_CONTENT="if [ -d \"\$(git rev-parse --show-toplevel)/graphify-out\" ]; then
    printf \"Syncing AI Architecture Map...\\n\"
    git -C \"\$(git rev-parse --show-toplevel)/graphify-out\" pull origin graph-data --quiet 2>/dev/null
fi"

# Create file with shebang if it doesn't exist
if [ ! -f "$POST_MERGE_FILE" ]; then
    printf "#!/bin/sh\n" > "$POST_MERGE_FILE"
fi

# Idempotent Rewrite: Remove old block and append new one
# (POSIX sed does not support -i, so we use a temporary file)
TMP_HOOK="$POST_MERGE_FILE.tmp.$$"
sed "/$BLOCK_START/,/$BLOCK_END/d" "$POST_MERGE_FILE" > "$TMP_HOOK"
mv "$TMP_HOOK" "$POST_MERGE_FILE"

{
    printf "\n%s\n" "$BLOCK_START"
    printf "%s\n" "$BLOCK_CONTENT"
    printf "%s\n" "$BLOCK_END"
} >> "$POST_MERGE_FILE"

chmod +x "$POST_MERGE_FILE"

# --- GUARDRAIL 4: Configuration ---
# Only update config if it's not already set to avoid unnecessary disk writes
current_hooks_path=$(git config core.hooksPath)
if [ "$current_hooks_path" != ".githooks" ]; then
    git config core.hooksPath .githooks
fi

printf "✔ Environment is synchronized via %s.\n" "$SCRIPT_NAME"