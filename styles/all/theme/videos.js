/**
 * lsbBB - LandSandBoat extension for phpBB
 * @author Ganiman <ganiman@ganiman.com>
 * @copyright (c) 2025, Made to Raid, https://madetoraid.com
 * @license GNU GPL-3.0
 */

const apiUrl = 'https://www.googleapis.com/youtube/v3/search';

async function getVideoData() {
    ytCache = localStorage['ytCache-' + encodeURIComponent(mobname)];
    if (!ytCache) {
        console.log('NOT using ytCache');
        try {
            const url = apiUrl + '?part=snippet&maxResults=3&key=' + apiKey + '&q=' + encodeURIComponent('ffxi ' + mobname);
            const response = await fetch(url)
            if (!response.ok) {
                throw new Error('Response status: ${response.status}');
            }
            const json = await response.json();
            localStorage['ytCache-' + encodeURIComponent(mobname)] = JSON.stringify(json);
            console.log(json);

            json.items.forEach(function (data, index) {
                let videohtml = '<td><iframe src="https://www.youtube.com/embed/' + data.id.videoId + '"></iframe></td>';
                document.querySelector('#mobvideos').insertAdjacentHTML('beforeend', videohtml);
            });
        } catch (error) {
            console.error(error.message);
        }
    } else {
        console.log('Using ytCache');
        const json = JSON.parse(ytCache);
        console.log(json);

        json.items.forEach(function (data, index) {
            let videohtml = '<td><iframe src="https://www.youtube.com/embed/' + data.id.videoId + '"></iframe></td>';
            document.querySelector('#mobvideos').insertAdjacentHTML('beforeend', videohtml);
        });
    }
}

function collapseVideos() {
    var coll = document.getElementsByClassName("videorow");
    var maxbutton = document.getElementById("maxbutton");
    var minbutton = document.getElementById("minbutton");
    var i;
    for (i = 0; i < coll.length; i++) {
        var content = coll[i];
        if (content.style.display === "table-row" || !content.style.display) {
            content.style.display = "none";
            maxbutton.style.display = "inline-block";
            minbutton.style.display = "none";
        } else {
            content.style.display = null;
            maxbutton.style.display = "none";
            minbutton.style.display = "inline-block";
        }
    }
}

if (mobname != '' && apiKey != false) {
    getVideoData();
}
  