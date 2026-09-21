# Search requirements

Date: 2026-09-19

- Home search searches across all saved phrases.
- Personal fraseario search at `/username` searches only that user’s saved phrases.
- Search field includes a selector for: `word`, `author`, and `tags`.
- `word`: one word only; matches inside the phrase body.
- `author`: up to two words; matches author name.
- `tags`: up to two words; matches tag names.
- If the user is already inside a specific fraseario, the search stays scoped to that user unless they clear it.
