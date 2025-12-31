# Openchan 🍀

_" A modern take on imageboard software."_

Openchan is a **simple** and **lightweight**, imageboard software with a focus
on modern techonologies. This project utilizes javascript for all frontend
rendering, and PHP for the backend API. This projects small codebase and few
dependencies allows for easy modification. Out of the box, Openchan is simple
and includes just the basics: thread creation, image uploading, and replies.
Apart from that, it is up to the site developer to implement other features.

If you are looking for a more "feature-rich" imageboard software, I would advise
you to checkout [vichan](https://github.com/vichan-devel/vichan). It is a proven
imageboard software also written in PHP with active development.

## User Features

- Thread Creation
- Image uploading
- Replies
- Overview of all posts

## Developer Features

- Few dependencies
- SQLite database
- Browser-side rendering/page generation (saves on server processing)

## Requires

- PHP (8+)
- SQLite
- Apache with mod_rewrite

## Installation

1. Clone this repo:

```
git clone https:/github.com/brodyking/openchan
```

2. Start your apache server in the root directory.

## Roadmap

- [x] Thread Creation
- [x] Replies
- [x] Image uploading
- [x] Boards
- [ ] Admin panel
