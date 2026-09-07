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

Users management should exists, at least on a very basic level.

User data can be the usual: 
- first name
- last name
- user/nickname
- email
- password
- short profile, 200 chars

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
3. Search results page
    - I have to think about it.


## Data design

An User has zero to many Phrases.  

A Phrase has:
 - one and only one body  
 - one and only one author
 - zero to many tags

A Phrase can be referenced by at least one User.  
*I'm thinking, it may happen that one user already wrote the phrase another user wants to save. So, to avoid duplication, the same Phrase can be referenced by multiple Users. This opens the door to see "matches" with other people...*

Author owns zero to many Phrases.

## Tech stack

TBD

## 

## Questions

| #     | Question | Answer |
| :---: | -------- | ------ |
|1      | How to display search results? | - |
|2      | How do users are registered? | Initially manually|
|3      | | |