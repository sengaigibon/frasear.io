# Project Fraseario

Name: Frasario  
Date: 2026-09-07

## Brainstorming

This is a web project, a website called "fraseario";  
The idea is to have a collection of phrases an user wants to save because they have heard it or read it somewhere; the saving process has to be something ultra simple, that's important.   

Data to be saved per phrase:
- the phrase itself, text, max 200 chars, required
- creation date, timestamp, automatically created based on current date-time, required
- tags, comma separated values, optional but they will be used for searching, 100chars
- author, optional, 50 chars
- FOR THE FUTURE: font style and background.

Users management should exists, at least on a very basic level.

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

Laravel
PHP
PostgreSQL
Blade
Tailwind CSS
Alpine.js
Laravel Breeze
Pest


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

<a id="note-2">Note 3</a>: It may be possible for an User to choose a Theme for a specific Phrase, thus overrriding the User's fraseario Theme.