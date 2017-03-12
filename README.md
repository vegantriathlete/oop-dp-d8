# OOP, Design Patterns and Drupal 8
This repository contains all the necessary pieces for a presentation on
OOP, Design Patterns and Drupal 8. It's the presentation that I first delivered
at DBUG on March 28, 2017 and subsequently delivered at the 2017 Midwest
DrupalCamp.

## Sponsor me to speak at your camp!
If you'd like me to deliver the presentation at your camp I'm happy to make
arrangements to do so if you can find sponsors to cover my travel expenses.

## Using the repository
There is a setup directory that contains compressed files for the database
and for the site configuration. You'll probably just want to use the
database backup.

* Clone the repository into your environment
* Extract the database
* Copy the database into a database in your local environment
* Update the settings.php file to reflect the proper credentials

Credentials to sign in to the site:
user: admin 
password: admin 

## Using the contributed module
I have created a D8 version of the presentation module. I use it to deliver
the presentation and for my code examples; it's quite meta and recursive ;)
Note that it includes some javascript to reveal `<li>` entries. It looks for
`<ul class="reveal">`. For example, node 1 (the Welcome slide) has such a list.
Click a list item to reveal the next item in the list.

## Providing feedback
I welcome your feedback on both the presentation and the module. If you'd like
to give feedback on the presentation, create an issue in this repository. If
you'd like to give feedback (or contribute) to the module, then use the
[repository for the module](https://github.com/vegantriathlete/presentation).
