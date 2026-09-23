
// ---------------- MAP INITIALIZE ----------------
var map = L.map('map').setView([28.668, 77.290], 14);
// --------- DASHBOARD MAP THEMES ---------

var lightThemeLayer = L.tileLayer(
'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
{
    attribution: '© OpenStreetMap contributors'
});

var darkThemeLayer = L.tileLayer(
'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png',
{
    attribution: '© OpenStreetMap © CARTO'
});

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

var marker = L.marker([28.668, 77.290]).addTo(map);

marker.bindPopup('<b>My Location</b><br>Latitude: 28.668, Longitude: 77.290').openPopup();




 window.addEventListener("load", function () { 
     setTimeout(function () {
         map.invalidateSize(); }, 500); });
// ----------- GRID SAFE LOAD FIX -----------




function updateMapTheme(theme){

    if(theme === "dark"){
        if(map.hasLayer(lightThemeLayer)){
            map.removeLayer(lightThemeLayer);
        }
        darkThemeLayer.addTo(map);
    }
    else{
        if(map.hasLayer(darkThemeLayer)){
            map.removeLayer(darkThemeLayer);
        }
        lightThemeLayer.addTo(map);
    }

}















// ----------- SIDEBAR SAFE FUNCTIONS -----------



  function openSidebar() {
      document.getElementById('sidebar').classList.add('open');
      document.getElementById('overlay').style.display = 'block';
      document.getElementById('hamburger').classList.add('open'); // make icon white
    }

    function closeSidebar() {
      document.getElementById('sidebar').classList.remove('open');
      document.getElementById('overlay').style.display = 'none';
      document.getElementById('hamburger').classList.remove('open'); // make icon black
    }












// ----------- VIDEO AUTO NEXT -----------
document.getElementById('video-player')?.addEventListener('ended', function () {
  const select = document.getElementById('video-select');
  if(!select) return;
  const currentIndex = select.selectedIndex;
  if (currentIndex < select.options.length - 1) {
    select.selectedIndex = currentIndex + 1;
    select.form.submit();
  }
});


// ----------- HLS STREAM -----------
async function loadStream(deviceId){
const date = document.getElementById("datePicker").value;
  const API_URL = `https://damts.catalystgroups.in/api/video_sync/${deviceId}/${date}`;

  try {
    const res = await fetch(API_URL);
    const data = await res.json();

    console.log("API DATA:", data);

    if(currentMode === "live"){
      playVideo(data.video_live_url);
    } else {
      playVideo(data.video_record_url);
    }

    updateTelemetry(data.telemetry);
    updateDateTable(data.telemetry);

  } catch (err) {
    console.error("API Error:", err);
  }
  
}



let video = document.getElementById("video-player");
let hls = null;
function playVideo(url){

  if (!url) {
    console.warn("No video available");

    video.src = "";
    video.poster = "/images/no-video.png";   // ✅ correct path

    return;
  }

  // remove poster when video is available
  video.poster = "";

  if(hls){
    hls.destroy();
  }

  if (Hls.isSupported()) {
    hls = new Hls();
    hls.loadSource(url);
    hls.attachMedia(video);

    hls.on(Hls.Events.MANIFEST_PARSED, function () {
      video.play();
    });

  } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
    video.src = url;
    video.play();
  }
}

function updateTelemetry(telemetry){

  if(!telemetry || telemetry.length === 0){
    console.warn("No telemetry data");
    return;
  }

  const t = telemetry[0];

  const rows = document.querySelectorAll("#obdBody tr");

  if(rows.length < 3) return;

  rows[0].children[1].innerText = t.speed + " km/h";
  rows[1].children[1].innerText = "--"; // no RPM in API
  rows[2].children[1].innerText = t.fuel + "%";
}
function updateDateTable(telemetry){

  // ✅ HANDLE EMPTY DATA (IMPORTANT)
  if(!telemetry || telemetry.length === 0){

    document.getElementById("dateValue").innerText = "--";
    document.getElementById("timeValue").innerText = "--";
    document.getElementById("latlongValue").innerText = "--";
    document.getElementById("speedValue").innerText = "--";
    document.getElementById("fuelValue").innerText = "--";

    return;
  }

  const t = telemetry[0];

  const d = new Date(t.timestamp.replace(" ", "T"));

  document.getElementById("dateValue").innerText =
    d.toLocaleDateString("en-GB");

  document.getElementById("timeValue").innerText =
    d.toLocaleTimeString("en-IN");

  document.getElementById("latlongValue").innerText =
    `${t.lat} , ${t.lng}`;

  document.getElementById("speedValue").innerText =
    t.speed + " KMPH";

  document.getElementById("fuelValue").innerText =
    t.fuel + " %";
}


function changeDevice(){
  const deviceId = document.getElementById("deviceSelect").value;
  loadStream(deviceId);
}


document.querySelector('input[type="date"]').addEventListener("change", () => {
  changeDevice();
});

let currentMode = "record"; // default

function setMode(mode){
  currentMode = mode;

  document.querySelectorAll(".mode-btn").forEach(btn => btn.style.opacity = "0.6");

  if(mode === "live"){
    document.querySelector(".live").style.opacity = "1";
  } else {
    document.querySelector(".mode-btn").style.opacity = "1";
  }

  changeDevice();
}



function drawGauge(id,value,max,label,unit){

const canvas=document.getElementById(id);
const ctx=canvas.getContext("2d");

const w=canvas.width;
const h=canvas.height;

const cx=w/2;
const cy=h/2;
const r=60;

ctx.clearRect(0,0,w,h);

// outer glow
let glow=ctx.createRadialGradient(cx,cy,30,cx,cy,r+10);
glow.addColorStop(0,"#203a43");
glow.addColorStop(1,"#0f2027");

ctx.beginPath();
ctx.fillStyle=glow;
ctx.arc(cx,cy,r+10,0,Math.PI*2);
ctx.fill();

// outer ring
ctx.beginPath();
ctx.lineWidth=10;
ctx.strokeStyle="rgba(255,255,255,0.08)";
ctx.arc(cx,cy,r,0,Math.PI*2);
ctx.stroke();

// ticks
for(let i=0;i<30;i++){

let angle=(i/30)*Math.PI*2;

let x1=cx+(r-10)*Math.cos(angle);
let y1=cy+(r-10)*Math.sin(angle);

let x2=cx+r*Math.cos(angle);
let y2=cy+r*Math.sin(angle);

ctx.beginPath();
ctx.lineWidth=2;
ctx.strokeStyle="rgba(255,255,255,0.2)";
ctx.moveTo(x1,y1);
ctx.lineTo(x2,y2);
ctx.stroke();
}

// progress ring
let percent=value/max;

let grad=ctx.createLinearGradient(0,0,w,0);

if(label==="FUEL"){
grad.addColorStop(0,"#ffb300");
grad.addColorStop(1,"#ff6d00");
}
else{
grad.addColorStop(0,"#00e5ff");
grad.addColorStop(1,"#00aaff");
}

ctx.beginPath();
ctx.lineWidth=10;
ctx.strokeStyle=grad;
ctx.arc(cx,cy,r,-Math.PI/2,(percent*2*Math.PI)-Math.PI/2);
ctx.stroke();

// needle
let angle=(percent*2*Math.PI)-Math.PI/2;

let nx=cx+(r-15)*Math.cos(angle);
let ny=cy+(r-15)*Math.sin(angle);

ctx.beginPath();
ctx.lineWidth=3;
ctx.strokeStyle="#ffffff";
ctx.moveTo(cx,cy);
ctx.lineTo(nx,ny);
ctx.stroke();

// center circle
ctx.beginPath();
ctx.fillStyle="#ddd";
ctx.arc(cx,cy,4,0,Math.PI*2);
ctx.fill();

// value text
ctx.fillStyle="#fff";
ctx.font="bold 22px Segoe UI";
ctx.textAlign="center";
ctx.fillText(Math.round(value),cx,cy+8);

ctx.font="12px Segoe UI";
ctx.fillStyle="#aaa";
ctx.fillText(unit,cx,cy+25);

// label
ctx.fillStyle="#00e5ff";
ctx.font="bold 13px Segoe UI";
ctx.fillText(label,cx,cy+45);

}

let currentSpeed = 0;
let currentRPM   = 0;
let currentFuel  = 0;


function updateGauges(){

const rows = document.querySelectorAll("#obdBody tr");
if(rows.length < 3) return;

let targetSpeed = parseInt(rows[0].children[1].innerText) || 0;
let targetRPM   = parseInt(rows[1].children[1].innerText) || 0;
let targetFuel  = parseInt(rows[2].children[1].innerText.replace('%','')) || 0;
currentSpeed += (targetSpeed - currentSpeed) * 0.1;
currentRPM   += (targetRPM   - currentRPM)   * 0.1;
currentFuel  += (targetFuel  - currentFuel)  * 0.1;

drawGauge("speedCanvas",currentSpeed,180,"SPEED","km/h");
drawGauge("rpmCanvas",currentRPM,8000,"RPM","rpm");
drawGauge("fuelCanvas",currentFuel,100,"FUEL","%");

}
// IMPORTANT: RUN AFTER PAGE LOAD
window.addEventListener("DOMContentLoaded", function(){

  // gauges (already there)
  drawGauge("speedCanvas",0,180,"SPEED","km/h");
  drawGauge("rpmCanvas",0,8000,"RPM","rpm");
  drawGauge("fuelCanvas",0,100,"FUEL","%");

  setInterval(updateGauges,100);

  // 🔥 LOAD DEFAULT DEVICE
  changeDevice();
   

});

