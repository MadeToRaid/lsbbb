/**
 * lsbBB - LandSandBoat extension for phpBB
 * @author Ganiman <ganiman@ganiman.com>
 * @copyright (c) 2025, Made to Raid, https://madetoraid.com
 * @license GNU GPL-3.0
 */

function setCookie(cname, cvalue, exdays) {
  const d = new Date();
  d.setTime(d.getTime() + (exdays*24*60*60*1000));
  let expires = "expires="+ d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
  let name = cname + "=";
  let decodedCookie = decodeURIComponent(document.cookie);
  let ca = decodedCookie.split(';');
  for(let i = 0; i <ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}



/**
 * Append Vana'diel time to the phpBB date beneath the header
 * https://ffxiclopedia.fandom.com/wiki/Time
 */
function appendVanadielTime() {

  /**
   * The epoch is when Vana'diel began in Earth time: 2003-06-23 15:00:00.00
   * A Vana'diel day is 1/25 of an Earth Day
   */
  var epoch = new Date();
  epoch.setUTCFullYear(2002, 5, 23);
  epoch.setUTCHours(15, 0, 0, 0);

  /**
   * Names of the days in a Vana'diel week
   */
  var vanadielDayNames = new Array(
    "Firesday",
    "Earthsday",
    "Watersday",
    "Windsday",
    "Iceday",
    "Lightningday",
    "Lightsday",
    "Darksday"
  );

  /**
   * Generate the current Vana'diel date
   */
  var curDate = new Date();
  var earthDayMs = (24 * 60 * 60 * 1000); // Number of milliseconds in an Earth day
  var vanadielDate = ((898 * 360 + 30) * earthDayMs) + (curDate.getTime() - epoch.getTime()) * 25;

  /**
   * Assign variables for the different parts of the Vana'diel date
   */
  vanadielYear = Math.floor(vanadielDate / (360 * earthDayMs));
  vanadielMonth = Math.floor((vanadielDate % (360 * earthDayMs)) / (30 * earthDayMs)) + 1;
  vanadielDay = Math.floor((vanadielDate % (30 * earthDayMs)) / (earthDayMs)) + 1;
  vanadielHour = Math.floor((vanadielDate % (earthDayMs)) / (60 * 60 * 1000));
  vanadielMinute = Math.floor((vanadielDate % (60 * 60 * 1000)) / (60 * 1000));
  vanadielSecond = Math.floor((vanadielDate % (60 * 1000)) / 1000);
  vanadielDayIndex = Math.floor((vanadielDate % (8 * earthDayMs)) / (earthDayMs));

  /**
   * Pad the date variables if necessary
   */
  if (vanadielYear < 1000) { vanadielYear = "0" + vanadielYear; }
  if (vanadielMonth < 10) { vanadielMonth = "0" + vanadielMonth; }
  if (vanadielDay < 10) { vanadielDay = "0" + vanadielDay; }
  if (vanadielHour < 10) { vanadielHour = "0" + vanadielHour; }
  if (vanadielMinute < 10) { vanadielMinute = "0" + vanadielMinute; }
  if (vanadielSecond < 10) { vanadielSecond = "0" + vanadielSecond; }

  /**
   * Build HTML string of the complete Vana'diel time
   */
  vanadielTime = "<span class=\"" + vanadielDayNames[vanadielDayIndex] + "\">" + vanadielDayNames[vanadielDayIndex] + "</span>:  "
  vanadielTime += vanadielYear + "-" + vanadielMonth + "-" + vanadielDay + "  " + vanadielHour + ":" + vanadielMinute + ":" + vanadielSecond;

  /**
   * Append the Vana'diel time to the phpBB time
   */
  timeNodes = document.getElementsByClassName("time");
  timeNodes[1].innerHTML = timeNodes[1].innerHTML + " / " + vanadielTime;
}

/**
 * Map Stuff
 * @todo Clean this up and turn into a funciton 
 */

var mapthumbs = document.getElementById("mapthumbs");
if (!!mapthumbs) {
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
var counter = 0;

for (let i = 0; i < mapimgs.length; i++) {
  let mapimg = mapimgs[i];
  mapimg.addEventListener("click", function () {
    modalimg.src = this.src;
    modalimglink.href = this.src;
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
if (!!span) {
  span.onclick = function () {
    mapmodal.style.display = "none";
  }
}
if (!!mapmodal) {
  mapmodal.onclick = function () {
    mapmodal.style.display = "none";
  }
}

document.addEventListener('keydown', function (e) {
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


/**
 * Count Animation
 * @todo Clean this up and turn into a function 
 */

const animationDuration = 2000;
const frameDuration = 1000 / 60;
const totalFrames = Math.round(animationDuration / frameDuration);
const easeOutQuad = t => t * (2 - t);

// The animation function, which takes an Element
const animateCountUp = el => {
  let frame = 0;
  const countTo = parseInt(el.innerHTML, 10);
  // Start the animation running 60 times per second
  const counter = setInterval(() => {
    frame++;
    // Calculate our progress as a value between 0 and 1
    // Pass that value to our easing function to get our
    // progress on a curve
    const progress = easeOutQuad(frame / totalFrames);
    // Use the progress value to calculate the current count
    const currentCount = Math.round(countTo * progress);

    // If the current count has changed, update the element
    if (parseInt(el.innerHTML, 10) !== currentCount) {
      el.innerHTML = Number(currentCount).toLocaleString();
    }

    // If we’ve reached our last frame, stop the animation
    if (frame === totalFrames) {
      clearInterval(counter);
    }
  }, frameDuration);
};

// Run the animation on all elements with a class of ‘countup’
const runCountAnimations = () => {
  const countupEls = document.querySelectorAll('.countup');
  countupEls.forEach(animateCountUp);
};
window.addEventListener('load', function () {
  runCountAnimations();
})

