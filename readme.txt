Processing For Wordpress
========================

Processing for wordpress is a plugin that allow you to easily manage and include processing sketches into a wordpress post or page. It automatically adds the right librairies for you and will offer different features and templates to presente your creation in a minimalist and elegant way.

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
* replace the size function usualy in the setup() of the sketch by 
```
 jProcessingJS(this);
```
* you can then edit the width and the height of the sketch in the admin panel. It supports sizes in pixels as well as percentages
* If you want to make you sketch responsive, make sure to set the width or the height to 100%
* For more informtions about the possibilities that offer Jprocessing.js please go to the official github page : https://github.com/GildasP/jProcessingJS

##It's still a beta 
The function loadimage() is not yet well supported in this version as it is still a beta. I'm still working on it and it will be available very soon. 
