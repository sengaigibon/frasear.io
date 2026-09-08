# Project Fraseario

Name: Frasario  
Date: 2026-09-07

## Brainstorming

This is a web project, a website called "fraseario".  
The idea is to have a collection of phrases an user wants to save because they have heard it or read it somewhere; the saving process has to be something ultra simple, that's important.   
The website is not reactive.

Data to be saved per phrase:
- the phrase itself, text, max 200 chars, required
- creation date, timestamp, automatically created based on current date-time, required
- tags, comma separated values, optional but they will be used for searching, 100chars
- author, optional, 50 chars
- FOR THE FUTURE: font style and background.

Users management and authentication must exists, at least on a very basic level.

User data can be the usual: 
- first name
- last name
- user/nickname
- email
- password
- short profile, 200 chars
- fraseario's theme

Suggested domain name:  
- frasear.io
- fraseario.com
- fraseario.net
- fraseario.xyz
   
The website pages:  
1. Home: 
    - Displays a random phrase from a random user; the texts occupies the whole page, written in italics, wrapped on double quotes. If the phrase has an author, it's written bellow it, on small letters on the bottom left corner, on the bottom right there's a mention of who uploaded the phrase with a link to their fraseario's page. 
    - The page loads 5 random phrases and this full screen frame showing a phrase is actually a slider.
    - There's no vertical scroll only swipe left or right is allowed.
    - Slider is an infinite carrousel, within the 5 phrases.
    -  Reloading the page, reloads 5 more random phrases.
    - On the top, a navigation bar with a search field for searching by tag for all users
2. Someone fraseario's page:
    - Identified by domain.com/username
    - Exactly the same as Home, but all limited to the current username
    - The user can choose a Theme for their fraseario
3. Search results page
    - I have to think about it.


## Themes
A Theme defines the whole fraseario custom style for a single User.
For now customizable elements will be:
1. Font style 
2. Background color / Image


## Data design

An User has zero to many Phrases.  

A Phrase has:
 - one and only one body  
 - one and only one author
 - zero to many tags

A Phrase can be referenced by at least one User. See [Note 1](#note-1)

Author owns zero to many Phrases. [Note 2](#note-2)

There are Themes.
One User chooses one and only one Theme [Note 3](#note-3)


## Tech stack

### Core application

- Laravel, as a single monolithic application
- PHP, latest stable version supported by your chosen Laravel release
- PostgreSQL for the database
- Blade for server-rendered HTML
- Alpine.js or small vanilla JavaScript for the slider, swipe gestures, and author suggestions
- Tailwind CSS for styling, if you do not want to write all CSS manually

### Laravel packages and services

- Laravel Breeze for basic authentication
- Eloquent for database access
- Form Requests for validation
- Laravel queues and scheduler for future duplicate detection and notifications
- PostgreSQL full-text search and pg_trgm for tag search and later phrase similarity

### Testing and deployment

- Pest for unit and feature tests
- Podman + Cloudflare Tunnel

**This application will be self-hosted.**



## Self-Hosting Notes

### Container layout

Separate Podman containers for: 
- app (PHP-FPM) & Nginx
- PostgreSQL

Using a Podman pod or podman-compose/Quadlet so they share a network namespace cleanly.  
**Keep Postgres data on a named volume**, not inside the container's writable layer — non-negotiable.

### Backups — do this before real users exist

Cron job running pg_dump (custom format, -Fc) on a schedule (daily is fine at this scale), rotated locally.
Copy backups off the local machine — even something simple like syncing to Backblaze B2, an S3-compatible bucket, or another physical location. "Self-hosted" + "only backup lives on the same disk" is how projects die.
Test a restore once, early, so you know the process works before you need it under pressure.

### Cloudflare Tunnel specifics

Tunnel exposes Nginx only — Postgres and Redis should never be tunnel-exposed or bound to a public interface, 127.0.0.1/internal pod network only.
Since Cloudflare terminates TLS at their edge, Nginx can run plain HTTP internally; just make sure Laravel knows it's behind a proxy (TRUSTED_PROXIES in .env, trust Cloudflare's IP ranges) so APP_URL, redirects, and Str::random-based signed URLs generate correctly as https.
Cloudflare Access can gate /login or admin routes at the edge if you want a second layer, worth considering for early manual user registration.

### Updates/maintenance

Since there's no managed host doing OS patching for me, put Podman auto-update (podman auto-update with labeled containers) or at least a manual monthly patch routine on my calendar. Self-hosting silently accumulates security debt if nobody's watching it.



## Questions

| #     | Question | Answer |
| :---: | -------- | ------ |
|1      | How to display search results? | - |
|2      | How do users are registered? | Initially manually|
|3      | - | - |


## Notes On The Go

<a id="note-1">Note 1</a>: I'm thinking, it may happen that one user already wrote the phrase another user wants to save. So, to avoid duplication, the same Phrase can be referenced by multiple Users.  
This opens the door to see "matches" with other people...  
The search for duplications may happens:  
1. when the phrase is saved, identify a 1:1 match, do not create a new phrase, just link it to the user; it may display to the user that they made a match with other(s)
2. periodic automatic running script checking for duplications on not 1:1 phrases but more like a similarity text; it may notify the users about the posible merginf og the phrase, requiring authorization, showing the match with other users; notifications may be by email, maybe even allowing users to discuss which is the correct version, once agreed the phrases are merged; if there's no agreement then both phrases stay.
It may be interesting to allow these sort of interactions.

<a id="note-2">Note 2</a>: Authors should be added on the fly when the phrase is saved. When a phrase is being written, on the Author fields, an ajax bases suggestions dropdown is filled up so the User can choose an Author and avoids duplication. New Authors are added in this moment.

<a id="note-3">Note 3</a>: It may be possible for an User to choose a Theme for a specific Phrase, thus overrriding the User's fraseario Theme.

<a id="note-4">Note 4</a>: Setting an User custom background, allowing to upload an image for that, is not a free Feature, it may be developed on a later stage.


## Plan

### Iteration 1: POC

Work on a Proof-Of-Concept:  
[ ] Database design completed.  
[ ] Development environment up and running **locally**.  
[ ] Containers up and running.  
[ ] DB schema deployed on PostegreSQL.  
[ ] One user (myself) is added.  
[ ] User is able to add a phrase.  
[ ] Phrase saved on database.


### Iteration 2.
[ ] Home page works.  
[ ] Personal Fraseario works.  


### Iteration 3.
[ ] User authentication.  
[ ] Login page.  
[ ] Logout page.  
[ ] 404 page.  
[ ] Wrong login page.  


### Iteration 4.
[ ] Phrase duplication detection and handling.  
[ ] Cloudflare tunneling works.
[ ] Theming


