Content Info
============

Extension adding a panel to the content editor screen containing additional info for the current entry, such as the URL, image or custom data using a twig template. The contents of this panel can be overridden for each contenttype. 

![Screenshot 1](/screenshots/screenshot1.png?raw=true "Screenshot 1")

![Screenshot 2](/screenshots/screenshot2.png?raw=true "Screenshot 2")

### Requirements

* PHP >=7.0
* Bolt =>3.2

### Extend

To add additional content for a contenttype, create a template _content-info/\_slug.yml_ in your theme directory, check out _content-info/\_default.twig_ for available blocks to override.

