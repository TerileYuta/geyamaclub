const participants_number_obj = document.getElementById('participants_number');
const income_obj = document.getElementById('income');
const balance_obj = document.getElementById('balance');
const participation_fee_obj = document.getElementById('participation_fee');
const income_other_obj = document.getElementById('income_other');
const practice_date_obj = document.getElementById('practice_date');
const memo_obj = document.getElementById('memo');
const status_select_obj = document.getElementById('status_select');
const participant_list_obj = document.getElementById('participant_list');
const participants_number_parcent_obj = document.getElementById('participants_number_parcent');
const gym_input_obj = document.getElementById('gym_input');
const shattle_input_obj = document.getElementById('shattle_input');
const other_input_obj = document.getElementById('other_input');
const spending_obj = document.getElementById('spending');
const practice_balance_obj = document.getElementById('practice_balance');
const back_date = document.getElementById('back_date');
const next_date = document.getElementById('next_date');
const settlement_btn_obj = document.getElementById('settlement_btn');
const settlement_memo_obj = document.getElementById('settlement_memo');
const number_participants_obj = document.getElementById('number_participants');
const income_parent_obj = document.getElementById('income_parent');
const balance_parent_obj = document.getElementById('balance_parent');
const save_memo_obj = document.getElementById('save_memo');
const settlement_id_obj = document.getElementById('settlement_id');
const income_other_memo_obj = document.getElementById('income_other_memo');
const other_memo_obj = document.getElementById('other_memo');
const add_member_name = document.getElementById('add_member_name');
const add_member_btn = document.getElementById('add_member_btn');
const delete_member_btn = document.getElementById('delete_member_btn');
const name_list = document.getElementById('name_list');

spending = 0;
income = 0
practice_id = 0;
practice_balance = 0;
title = "";

practice_date = new Date("0000-00-00").getTime();
practice_date_pos = 0;

today_time = new Date().getTime();
dis = 0;
date = "";

plan_date.forEach(date => {
    date_time = new Date(date).getTime();

    if(date_time > today_time){
        if((dis >= Math.abs(today_time - date_time)) || (dis == 0)){
            practice_date = date;
            dis = Math.abs(today_time - date_time);
        }
    }
});

plan_date.sort((a, b) => {
	return (a > b ? 1 : -1);
});

if(!practice_date){
    practice_date = plan_date[plan_date.length - 1];
}

try{
    url = new URL(window.parent.window.location.href);
    params = url.searchParams;

    params.forEach((value, key) => {
        practice_date = value;
    });

    practice_date_pos = plan_date.indexOf(practice_date);
}catch(e){
    practice_date_pos = plan_date.indexOf(practice_date);
}

get_data(practice_date);

document.querySelectorAll(".income_obj").forEach(e=>{
    e.addEventListener("input", () => {
        income = ((member_number * Number(participation_fee_obj.value) || 0) + Number(income_other_obj.value))
        income_obj.innerHTML = "↑￥" + income;

        set_practice_balance(settlement_id);
        settlement_flag = false;
    });
});

document.querySelectorAll(".spending_obj").forEach(e =>{
    e.addEventListener("input", () => {
        spending = Number(gym_input_obj.value) + Number(shattle_input_obj.value) + Number(other_input_obj.value);
        spending_obj.innerHTML = "↓￥" + spending;
    
        set_practice_balance(settlement_id);
        settlement_flag = false;
    });
});

function get_data(date){
    settlement_set(false);

    settlement_id_obj.innerHTML = "";
    settlement_btn_obj.style.display = "block";

    const params = new URLSearchParams();
    params.append("date", date);

    axios.post("get_data.php", params)
    .then((response) => {
        update_data(response.data);
    });
}

function update_data(plan){
    member_list = plan["member"];
    title = plan["title"];
    member_number = Object.keys(member_list).length;
    practice_id = plan["id"];

    settlement_id = plan["settlement_id"];

    participants_number_obj.innerHTML = member_number || 0;
    participants_number_parcent_obj.innerHTML = "(" + (member_number / all_member_number) * 100 + "%)";

    income = member_number * plan["fee"] + plan["income_other"];
    spending = plan["gym"] + plan["shattle"] + plan["other"];
    income_obj.innerHTML = "↑￥" + income;
    spending_obj.innerHTML = "↓￥" + spending;
    
    window.parent.window.history.pushState(null,null, "/geyamaclub/?date=" + plan["natural_date"] + "#practice");

    set_practice_balance(settlement_id);

    practice_date_obj.innerHTML = title + "-" + plan["date"];
    memo_obj.innerHTML = plan["memo"];

    status_select_obj.value = plan["status"];
    change_status_color();

    participation_fee_obj.value = plan["fee"];
    income_other_obj.value = plan["income_other"];

    gym_input_obj.value = plan["gym"];
    shattle_input_obj.value = plan["shattle"];
    other_input_obj.value = plan["other"];

    while (participant_list_obj.rows.length > 0) participant_list_obj.deleteRow(0);

    count = 0;

    Object.keys(member_list).forEach(id => {
        count++;

        let newRow = participant_list_obj.insertRow();

        if(count % 2 == 0){
            newRow.className = "bg-gray-200 lg:table-cell hidden";
        }

        Object.keys(member_list[id]).forEach(key => {
            newCell1 = newRow.insertCell();
            newCell1.className = "border px-4 py-2 lg:table-cell hidden";
            newCell1.appendChild(document.createTextNode(member_list[id][key]));
        });
    });
}

status_select_obj.addEventListener("change", () => {
    change_status_color();

    if(status_select_obj.value == "0"){
        text = practice_date_obj.innerHTML + "の練習の参加を募集を終了しました。";
    }else{
        text = practice_date_obj.innerHTML + "の練習の参加を募集し始めました。";
    }

    const params =  new URLSearchParams();
    params.append("message", text);
    params.append("status", status_select_obj.value);
    params.append("id", practice_id);

    axios.post("https://ss1.xrea.com/geyamaclub.s203.xrea.com/LineBot/push_message.php", params)
    .then(result => {
        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: 'Status changed during recruitment',
            showConfirmButton: false,
            timer: 3000
        });
    }).catch(err => {
        Swal.fire({
            position: 'top-end',
            icon: 'error',
            title: "Status can't chang during recruitment",
            showConfirmButton: false,
            timer: 3000
        });
    });
});

function settlement_set(flag){
    document.querySelectorAll(".control").forEach(e =>{
        e.readOnly = flag;
    });

    add_member_name.style.display = 'none';
    add_member_btn.style.display = "none";
    delete_member_btn.style.display = 'none';flag;
    settlement_btn_obj.disabled = flag; 
    status_select_obj.disabled = flag;
}

function set_practice_balance(settlement_id){
    practice_balance = income - spending;
    practice_balance_obj.innerHTML = "￥" + practice_balance;

    if(practice_balance >= 0){
        practice_balance_obj.classList.add("text-green-500");
        practice_balance_obj.classList.remove("text-red-500");
    }else{
        practice_balance_obj.classList.remove("text-green-500");
        practice_balance_obj.classList.add("text-red-500");
    }

    if(settlement_id == 0){
        balance_obj.innerHTML = "(￥" + (balance  + practice_balance) + ")";
    }else{
        balance_obj.innerHTML = "￥" + balance;
        settlement_memo_obj.innerHTML = ""

        settlement_set(true);

        settlement_id_obj.innerHTML = "決済番号：" + settlement_id;
        settlement_btn_obj.style.display = "none";
    }
    
    if((balance + practice_balance) >= 0){
        balance_obj.classList.add("text-green-500");
        balance_obj.classList.remove("text-red-500");
    }else{
        balance_obj.classList.remove("text-green-500");
        balance_obj.classList.add("text-red-500");
    }   
}

function change_status_color(){
    switch(status_select_obj.value) {
        case "0":
            status_select_obj.classList.remove("border-green-400");
            status_select_obj.classList.remove("border-red-400");
            status_select_obj.classList.add("border-yellow-400");
            break;
        case "1":
            status_select_obj.classList.add("border-green-400");
            status_select_obj.classList.remove("border-red-400");
            status_select_obj.classList.remove("border-yellow-400");
            break;
    }
}

back_date.addEventListener("click", () => {
    if((plan_date.length >= (practice_date_pos - 2)) && ((practice_date_pos -1) >= 0)){
        practice_date_pos -= 1; 
        get_data(plan_date[practice_date_pos]);
    }else{
        Swal.fire({
            icon: 'error',
            title: 'No practice plan has been created',
            text: ""
          })
    }
    
});

next_date.addEventListener("click", () => {
    if(plan_date.length >= practice_date_pos + 2){
        practice_date_pos += 1;
        get_data(plan_date[practice_date_pos]);
    }else{
        Swal.fire({
            icon: 'error',
            title: 'No practice plan has been created',
          })
    }
});

settlement_btn_obj.addEventListener("click", () => {
    fee = participation_fee_obj.value * participants_number_obj.value;
    income_other = income_other_obj.value;
    income_other_memo = income_other_memo_obj.value;
    gym = gym_input_obj.value;
    shattle = shattle_input_obj.value;
    other = other_input_obj.value;
    other_memo = other_memo_obj.value;

    const params = new URLSearchParams();
    params.append("title", title);
    params.append("income", income);
    params.append("spending", spending);
    params.append("date", practice_date);
    params.append("practice_id", practice_id);
    params.append("fee", fee);
    params.append("income_other", income_other);
    params.append("income_other_memo", income_other_memo);
    params.append("gym", gym);
    params.append("shattle", shattle);
    params.append("other", other);
    params.append("other_memo", other_memo);

    axios.post("settlement.php", params)
    .then(result => {
        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: 'Decision!!',
            showConfirmButton: false,
            timer: 3000
        });

        settlement_set(true);

        settlement_id_obj.innerHTML = "決済番号：" + result.data;
        settlement_btn_obj.style.display = "none";
    })
    .catch(err => {
        Swal.fire({
            position: 'top-end',
            icon: 'error',
            title: "Can't Decision!!",
            showConfirmButton: false,
            timer: 3000
        });
    })
});

save_memo_obj.addEventListener("click", () => {
    memo = memo_obj.value;

    const params = new URLSearchParams();
    params.append("memo", memo);
    params.append("practice_id", practice_id);

    axios.post("save_memo.php", params)
    .then(result => {
        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: 'Save!!',
            showConfirmButton: false,
            timer: 3000
        });
    });
});

add_member_btn.addEventListener("click", () =>{
    member_name = add_member_name.value;

    const params = new URLSearchParams();
    params.append("name", member_name);
    params.append("id", practice_id);

    axios.post("add_member.php", params)
    .then(result => {
        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: 'Add  !!',
            showConfirmButton: false,
            timer: 3000
        });

        get_data(practice_date);
    });
});

add_member_name.addEventListener("input", () => {
    const params = new URLSearchParams();
    params.append("name", add_member_name.value);

    axios.post("member_search.php", params)
    .then(result => {
        res = result.data;

        while(name_list.firstChild ){
            name_list.removeChild(name_list.firstChild);
          }

        res.forEach(info => {
            var op = document.createElement("option");
            op.text = info.name;
            op.value = info.name;
            name_list.appendChild(op);
        });
    });
});

delete_member_btn.addEventListener("click", () => {
    member_name = add_member_name.value;

    const params = new URLSearchParams();
    params.append("name", member_name);
    params.append("id", practice_id);

    axios.post("delete_member.php", params)
    .then(result => {
        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: 'Delete!!',
            showConfirmButton: false,
            timer: 3000
        });

        get_data(practice_date);
    })
});