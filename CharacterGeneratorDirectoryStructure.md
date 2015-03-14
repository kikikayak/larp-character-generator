When customizing the Generator, it is helpful to understand the basic layout and directory structure of the Generator files:

> -admin: Pages in the admin section
> -ajax: Files for handling ajax calls and dispatching to the appropriate PHP files
> -class: Class files containing all of the database calls and most of the rules logic.
> -images: Non-theme-specific images
> -includes: Included files, including header, footer, and configuration files
> -install: Files for installing and configuring the Generator.
> > Should be deleted from hosting server once install is complete.

> -js
> > |--chosen: jQuery plugin for creating more attractive and usable dropdown lists
> > > Used under MIT license.
> > > http://harvesthq.github.io/chosen

> > |--simplyCountable: jQuery plugin for providing character counters for input and textarea fields.
> > > Used under GPL license.
> > > https://github.com/aaronrussell/jquery-simply-countable

> > |--tablesorter: jQuery plugin for making sortable table columns.
> > > Used under GPL license.
> > > http://tablesorter.com/docs

> > |--ui: jQuery UI library.
> > > http://jqueryui.com

> -main: Pages for main player-facing area of the Generator.
> -PSDs: Photoshop (PSD) files for the basic Generator icons and graphics.
> > This directory does not need to be uploaded to your hosting provider.

> -theme: Contains themes for modifying the look and feel and layout of the Generator pages.
> > Each directory under this one represents a theme.
> > |--classic: Files for "classic" theme included with the Generator.
> > > |--images: theme-specific images
