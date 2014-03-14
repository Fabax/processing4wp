Processing For Wordpress
========================

Processing for wordpress is a plugin that allow you to easily manage and include processing sketches into a wordpress post or page. It automaticly adds the right librairy for you and will offer differents features and templates to presente your création in a minimalist and elegant way

##Installation
dowload the plugin into the worpdress plugins folder and activate it in the wordpress admin page

##Features
* include processing.js librairy into the wordpress page
* include Jprocessing.js librairy into the wordpress page. It allow you to enable responsive, fullscreen and div overlay for yout sketch. 
* upload entire sketch forlder 
* Make the integration into your wordpress page easily by allowing you to change the width and the height of the sketch direclty in the wordpress back office
* generate shortcode that you can include anywhere in your wordpress
* provide a simple template to presente your sketch ( title , author name, author website, description)

##How to use 
### Simple example
*Create a new sketch
*set up all the informations you need 
*upload a zip folder containing your processing project 
*copy and paste the shortcode into your page. You can find it in the sketches list or you can respect the following synthaxe 
```
[processing sketch="title-of-your-sketch"]
```

### Enable size settings
*replace the size function usualy in the setup() of the sketch by 
```
 jProcessingJS(this);
```
*you can then edit the width and the height of the sketch in the admin panel. It supports sizes in pixels as well as percentages
*If you want to make you sketch responsive, make sure to set the width or the height to 100%


##Special thanks 
Thank you to Gildas Paubert for providing JProcessing.js (https://github.com/GildasP/jProcessingJS)

##warning
The function load image is not yet supported in this version as it is still a beta
