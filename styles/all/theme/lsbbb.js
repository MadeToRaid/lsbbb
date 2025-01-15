var mapthumbs = document.getElementById("mapthumbs");
if(!!mapthumbs) {
	var mapimgs = mapthumbs.getElementsByClassName("mapimg");
}
else {
	var mapimgs = [];
}
var modalimg = document.getElementById("modalimg");
var modalimglink = document.getElementById("modalimglink");
var mapmodal = document.getElementById("mapmodal");
var maptooltip = document.getElementById('maptooltip');
var maptooltipimg = document.getElementById('maptooltipimg');
var counter=0;

for(let i = 0; i < mapimgs.length; i++){
	let mapimg = mapimgs[i];
   	mapimg.addEventListener("click",function(){
		modalimg.src=this.src;
		modalimglink.href=this.src;
                mapmodal.style.display = "block";
	});
/*
   	mapimg.addEventListener("mouseover",function(){
		maptooltipimg.src=this.src;
                maptooltip.style.visibility = "visible";
	});
*/
}

var span = document.getElementsByClassName("mapclose")[0];
if(!!span) {
	span.onclick = function() {
  		mapmodal.style.display = "none";
	}
}
if(!!mapmodal) {
	mapmodal.onclick = function() {
		mapmodal.style.display = "none";
	}
}

document.addEventListener('keydown', function(e) {
	let keyCode = e.keyCode;
	if (keyCode === 27) {//keycode is an Integer, not a String
		mapmodal.style.display = "none";
	}
});

/*
let mapToolTip = document.getElementById('maptooltip');
window.addEventListener('mousemove', toolTipXY);
function toolTipXY(e) {
    let x = e.clientX, y = e.clientY;
    mapToolTip.style.top = (y + 20) + 'px';
    mapToolTip.style.left = (x + 20) + 'px';
};
*/

const animationDuration = 2000;
const frameDuration = 1000 / 60;
const totalFrames = Math.round( animationDuration / frameDuration );
const easeOutQuad = t => t * ( 2 - t );

// The animation function, which takes an Element
const animateCountUp = el => {
  let frame = 0;
  const countTo = parseInt( el.innerHTML, 10 );
  // Start the animation running 60 times per second
  const counter = setInterval( () => {
    frame++;
    // Calculate our progress as a value between 0 and 1
    // Pass that value to our easing function to get our
    // progress on a curve
    const progress = easeOutQuad( frame / totalFrames );
    // Use the progress value to calculate the current count
    const currentCount = Math.round( countTo * progress );

    // If the current count has changed, update the element
    if ( parseInt( el.innerHTML, 10 ) !== currentCount ) {
      el.innerHTML = Number(currentCount).toLocaleString();
    }

    // If we’ve reached our last frame, stop the animation
    if ( frame === totalFrames ) {
      clearInterval( counter );
    }
  }, frameDuration );
};

// Run the animation on all elements with a class of ‘countup’
const runCountAnimations = () => {
  const countupEls = document.querySelectorAll( '.countup' );
  countupEls.forEach( animateCountUp );
};
window.addEventListener('load', function () {
  runCountAnimations();
})
