const setting_btn = document.getElementById("setting_btn");
const log_btn = document.getElementById("log_btn");
const backup_btn = document.getElementById("backup_btn");
//const setting_screen = document.getElementById("setting_screen");
const log_screen = document.getElementById("log_screen");
const backup_screen = document.getElementById("backup_screen");
//const password_btn = document.getElementById("password_btn");
//const fee_btn = document.getElementById("fee_btn");
//const gym_btn = document.getElementById("gym_btn");

//setting_screen.style.display = "block";
log_screen.style.display = "block";
backup_screen.style.display = "none";

/*
setting_btn.addEventListener('click', function(){
    setting_screen.style.display = "none";
    log_screen.style.display = "none";
    backup_screen.style.display = "none";

    setting_screen.style.display = "block";
});
*/

log_btn.addEventListener('click', function(){
    //setting_screen.style.display = "none";
    log_screen.style.display = "none";
    backup_screen.style.display = "none";

    log_screen.style.display = "block";
});

backup_screen.addEventListener('click', function(){
    //setting_screen.style.display = "none";
    log_screen.style.display = "none";
    backup_screen.style.display = "none";

    backup_screen.style.display = "block";
});
