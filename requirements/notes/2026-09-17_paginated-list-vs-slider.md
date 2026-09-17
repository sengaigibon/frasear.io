# Paginated list vs. slider: when to use which

Date: 2026-09-17

A recurring design decision in frasear.io: the app has two fundamentally
different visual patterns for showing phrases — the full-screen swipe
slider (Swiper) and a scannable paginated list — and it's easy to reach
for whichever one is already built rather than the one the task actually
needs. This note fixes the reasoning so future screens don't need to
re-litigate it.

## The core distinction

The slider and the list aren't stylistic alternatives — they serve
opposite user intents:

- **Slider = browsing, no goal.** The user has no specific target in
  mind. They want to be shown something, one thing at a time, in a way
  that invites lingering on it. This is what the app's "commonplace
  book" identity is actually for.
- **List = searching, has a goal.** The user is looking for something
  specific, or needs to scan/compare/manage multiple items at once.
  Forcing this into a one-at-a-time swipe interaction actively works
  against the task.

## Why the slider is right for Home and the personal fraseario page

- Fixed, small set (5 phrases) — a slider works well precisely because
  the set size is known and small.
- The goal is serendipity: "show me something," not "find me the thing
  I'm thinking of."
- Full-screen, one-at-a-time presentation is what makes a single phrase
  feel worth lingering on — that's the whole point of the design.

## Why a paginated list is right for search results

- **Unknown result count.** Search can return 0, 1, or 200 matches. A
  slider has no sane way to represent "you're on result 47 of 200"; a
  paginated list handles this natively.
- **Scanning vs. immersion.** Finding a specific match means comparing
  candidates at a glance. One-at-a-time swiping through unknown-length
  results is strictly worse for that than letting the eye scan a list.
- **Reusing the slider muddies its meaning.** Right now "full-screen
  swipe" reliably means "this is curated browsing" everywhere in the
  app. If search also swiped, that visual language would stop meaning
  one consistent thing.

## Why the same logic applies to managing your own saved phrases

- Management is a **complete, reliable, goal-directed** task: you need
  to see *everything* you've saved, not a filtered/curated subset, and
  you need to act on specific items (edit, delete).
- A slider can't represent "show me literally all of my phrases" the
  way a paginated list can — and burying edit/delete controls inside a
  one-at-a-time swipe interaction is worse UX than a scannable list with
  inline actions.
- Concretely: tag search (an auxiliary discovery feature) should never
  be the *only* path to managing your own data. If a phrase was tagged
  inconsistently or not tagged at all, tag search would never surface it
  — meaning some of your own phrases could become unreachable to edit or
  delete. A dedicated paginated list doesn't have this failure mode.

## The general rule going forward

> If the user has a specific goal (find, compare, manage, act on
> something) → list.
> If the user has no goal and the point is the experience of encountering
> something → slider.

When a new screen comes up, ask which of those two the user is doing
before picking the pattern — don't default to whichever component
already exists in the codebase.
