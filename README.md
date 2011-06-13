Commits and Tickets
===================

A Yourls plugin to forward c- and t- to commits and tickets.

https://github.com/brettp/commits-and-tickets
(C) Brett Profitt 2011
Released under GPL 2


Requirements
------------
Yours: http://yourls.org/

Requires hyphens in keywords. Make sure your redirect rules allow for hyphens and not just alpha-numerics.

Installation
------------

 1. Drop in user/modules/. 
 2. Enable in the admin section.
 3. Visit the plugin config page and set the commits and tickets URLs. Replace the commit / ticket ID with %s. Examples:
  * Commit URL: http://trac.elgg.org/changeset/%s
  * Tickets URL: http://trac.elgg.org/ticket/%s

Usage
-----

Go to http://your-yours.org/t-<ticket id> or http://your-yours.org/c-<commit id>

Example commit: http://el.gg/c-9000
Example ticket: http://el.gg/t-3195