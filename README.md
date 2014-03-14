Processing For Wordpress
========================

processing for wordpress is a plugin that allow you to easily manager and include processing sketches into your wordpress post or page. 

##Installation
dowload the plugin into worpdress plugins folder and activate it in wordpress admin page

##Features
* include processing.js librairy into the wordpress page
* include Jprocessing.js librairy into the wordpress page (allow you to enable responsive, fullscreen and div overlay)
* upload entire sketch forlder 
* generate shortcode that you can include anywhere in your wordpress
* provide a simple template to presente your sketch ( title , author name, author website )description)

##How to use 
### Simple example

### Enable responsiveness
replace the size function usualy in the setup() of the sketch by 
```
 jProcessingJS(this);
```
Make sure to put the width and/or the height of the skecth at 100% in the admin panel

### show all the sketches per author


##Special thanks 
Thank you to Gildas Paubert for providing JProcessing.js (https://github.com/GildasP/jProcessingJS)

##warning
The function load image is not yet supported in this version as it is still a beta
