<style>
    [data-tyro-editor-field] {
        display: grid;
    }

    [data-tyro-editor-field] .EasyMDEContainer {
        display: block;
    }

    [data-tyro-editor-field] .EasyMDEContainer .CodeMirror {
        border: 1px solid var(--border);
        border-top: none;
        border-radius: 0 0 0.5rem 0.5rem;
        background: var(--background);
        color: var(--foreground);
        font-size: 0.9375rem;
    }

    [data-tyro-editor-field] .EasyMDEContainer .CodeMirror-focused {
        border-color: var(--border);
        outline: none;
    }

    [data-tyro-editor-field] .EasyMDEContainer .CodeMirror-cursor {
        border-left-color: var(--foreground);
    }

    [data-tyro-editor-field] .editor-toolbar {
        border: 1px solid var(--border);
        border-bottom: none;
        border-radius: 0.5rem 0.5rem 0 0;
        background: var(--muted);
    }

    [data-tyro-editor-field] .editor-toolbar button {
        color: var(--muted-foreground);
    }

    [data-tyro-editor-field] .editor-toolbar button.active,
    [data-tyro-editor-field] .editor-toolbar button:hover {
        background: var(--background);
        color: var(--foreground);
        border-color: var(--border);
    }

    [data-tyro-editor-field] .editor-toolbar i.separator {
        border-left-color: var(--border);
        border-right-color: var(--border);
    }

    [data-tyro-editor-field] .editor-statusbar {
        color: var(--muted-foreground);
    }

    [data-tyro-editor-field] .editor-preview,
    [data-tyro-editor-field] .editor-preview-side {
        background: var(--background);
        color: var(--foreground);
    }
</style>
