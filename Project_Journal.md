Project Journal
===============

Write little snippets of what was done with this project, dreams and research, issues and resolutions.

> Author: 	David Lighty
> Project: 	Note2Myself clone
> For:		Jason Harrison | BCIT | COMP2920 PHP

---

### Goal

The main site to clone http://note-to-myself.com is a simple note keeper, with the ability to keep text notes, website, and todos.  The goal is to re-create this, using whatever PHP we would prefer.  This is our final project for the class.

#### Personal Vision

I took this and wanted to make something more, bigger, grander....like I do with everything.  It's a canvas to expand upon and Jason is very welcoming of that.  Below here will be small snippets of things in an attempt to highlight what was done.

---

#### Architecture

- Backend : PHP API 
	Slim Framework for a robust and quick approach to an API based system.  Has the ability to easily implement complex routes, middleware and validation.
	> Site: http://www.slimframework.com/

- Backend Data Layer : MongoDB
	Again for a quick and simple db approach that works very well the JSON, we'll use MongoDB.  The data layer is very simple and we don't need many relationships.

- Frontend : BackboneJS 
	To use the API backend, will use the event driven frontend MVC framework BackboneJS.  This allows a nicely data driven approach to consume and use the API backend.
	> Site: http://backbonejs.org/

---

##### Creating the API

Using Slim was pretty easy and straight-forward.  Had some issues with getting the namespacing and autoloader working correctly.

##### Site Init...

Used [Yeoman](http://yeoman.io/) to quickly scaffold out a backbone site.  Yeoman is a great tool to generate all the boilerplate stuff needed for a site.  The community is great and thriving to create custom generators for various types of project.  Requires [npm](https://www.npmjs.org/)

##### Site change...

Originaly started with AngularJS.  Due to various outside forces, I've switched to BackboneJS...  I like both frameworks, but this project will allow me to ramp up my knowledge and experince with BackboneJS.