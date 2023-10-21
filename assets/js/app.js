/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.css';
import '../css/profile.css';
import '../css/reveal.css';



// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
 import $ from 'jquery';
 import 'bootstrap';
  $(document).ready(function(){
 
const ratio = 0.1;
var options = {
  root: null,
  rootMargin: '0px',
  threshold: 0.1
}

const handleIntersect = (entries, observer) =>{
  entries.forEach( function(entry) {
  	if (entry.intersectionRatio>ratio) {
  		entry.target.classList.add("reveal-visible");
  		observer.unobserve(entry.target);
  	}
  });
}


var observer = new IntersectionObserver(handleIntersect, options);

document.querySelectorAll('.reveal').forEach( function(r) {
	observer.observe(r);
});
 
 })



