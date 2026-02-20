# Form Wizard

This plugin allows you to easily create AJAX forms for Winter CMS.

## Why Form Wizard?
Almost everyday we do forms for our clients, personal projects, etc

Sometimes we need to add or remove fields, change validations, store data and at some point, this can be boring and repetitive.

So, the objective was to find a way to just put the HTML elements on the page, skip the repetitive task of coding and (with some kind of mystic magic) store this data on a database or send by mail.

### What makes Form Wizard different?
Unlike the original plugin, Form Wizard includes a powerful **Visual Form Builder**. You can now create, manage, and validate forms entirely within the Winter CMS backend, dragging and dropping your way to perfect forms—no HTML required!

## Features
* **Visual Form Builder** (Create forms in the backend)
* Create any type of form: contact, feedback, uploads, etc
* Write only HTML
* Don't code forms logic
* Laravel validation
* Custom validation errors
* Use multiple forms on same page
* Store on database
* Export data in CSV
* Access database records from backend
* Send mail notifications to multiple recipients
* Auto-response email on form submit
* reCAPTCHA validation (v2 and v3)
* Support for Translate plugin
* Inline errors with fields (read documentation for more info)
* File uploads using Filepond

## Quick Start
```bash
composer require thewebsiteguy/wn-formwizard-plugin
php artisan winter:up
```

### Basic Usage
A basic contact is available out of the box. You can find it at:
> /plugins/thewebsiteguy/formwizard/components/contactform

To use it, follow these steps:
1. On your Backend, goto CMS page.
2. Click on the Components button on the left sidebar.
3. Drag selected component to your page.
4. Configure component parameters (like form validation, notification settings, etc)

![Form Wizard Preview](assets/images/screenshot.png)


## Documentation
Checkout our docs at:
> https://formwizard.thewebsiteguy.uk

## Credits
This plugin is a substantial rewrite and continuation based on the original work by Martin M. and Joseph Crowell.
