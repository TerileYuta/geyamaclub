const practice = document.getElementById("practice");
const practice_icon = document.getElementById("practice_icon");
const payments = document.getElementById("payments");
const members = document.getElementById("members");
const plan = document.getElementById("plan");
const setting = document.getElementById("setting");
const menu_btn_check = document.getElementById("menu-btn-check");
const ad = document.getElementById("ad");
const menu = document.getElementById("menu");
const content_frame = document.getElementById("content_frame");

locations = this.location.hash;
locations = locations.substr(1);
if(locations != ""){
    form_post(locations);

    if(locations == "practice"){
        url = new URL(window.location.href);
        params = url.searchParams;
        request_date = params[0];
    }
}

practice.addEventListener("click", () => {change_view("practice")});
payments.addEventListener("click", () => {change_view("payments")});
members.addEventListener("click", () => {change_view("members")});
plan.addEventListener("click", () => {change_view("plan")});
setting.addEventListener("click", () => {change_view("setting")});

window.addEventListener("hashchange", () => {
    form_post((this.location.hash).substr(1))
});

function change_view(name){
    window.history.pushState(null, null, "/geyamaclub/");
    window.location.hash = name;

    form_post(name);
}

menu_btn_check.addEventListener("change", () => {
    menu.classList.toggle("animation");
    menu.classList.toggle("animation_reverse");

    document.querySelectorAll(".hide_object").forEach(e =>{
        e.classList.toggle("hidden");
    });
});

window.onload = () => {    
    ad_height = ad.offsetHeight;
    content_frame.style.height = (window.innerHeight - ad_height) + "px";
}

function form_post(name){
    var form = document.createElement("form");
    var request = document.createElement("input");

    form.method = "POST";
    form.action = "./key.php";
    form.target = "content_frame";

    request.type = "hidden";
    request.name = "name";
    request.value = name;

    form.appendChild(request);
    document.body.appendChild(form);
    
    form.submit();

    request.remove();
    form.remove();
}