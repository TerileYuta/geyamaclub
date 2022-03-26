const history_btn = document.getElementById('history');
const info_btn = document.getElementById('info');
const record_btn = document.getElementById('record');
const history_screen = document.getElementById('history_screen');
const info_screen = document.getElementById('info_screen');
const record_screen = document.getElementById('record_screen');
const payments_list = document.getElementById('payments_list');
const balance_obj = document.getElementById('balance');
const select_obj = document.getElementById('select');
const income_obj = document.getElementById('income');
const spending_obj = document.getElementById('spending');
const ave_balance_obj = document.getElementById('ave_balance');
const balance_chart_obj = document.getElementById("balance_chart");
const income_chart_obj = document.getElementById("income_chart");
const spending_chart_obj = document.getElementById("spending_chart");
const income_data_obj = document.getElementById("income_data");
const spending_data_obj = document.getElementById("spending_data");
const name_input_obj = document.getElementById("name_input");
const type_input_obj = document.getElementById("type_input");
const action_input_obj = document.getElementById("action_input");
const decision_balance_obj = document.getElementById("decision_balance");
const decision_btn = document.getElementById("decision_btn");
const month_payment_list = document.getElementById("month_payment_list");

history_screen.style.display = "none";
info_screen.style.display = "block";
record_screen.style.display = "none";

history_btn.addEventListener('click', () => {
    history_screen.style.display = "none";
    info_screen.style.display = "none";
    record_screen.style.display = "none";

    history_screen.style.display = "block";
});

info_btn.addEventListener('click', () => {
    history_screen.style.display = "none";
    info_screen.style.display = "none";
    record_screen.style.display = "none";

    info_screen.style.display = "block";
});

record_btn.addEventListener('click', () => {
    history_screen.style.display = "none";
    info_screen.style.display = "none";
    record_screen.style.display = "none";

    record_screen.style.display = "block";
});

balance_list = [];
label_list = [];

rows = payments_list.rows;
last_id = 0;
last_balance = 0;
last_date = new Date();
color = "bg-white";

for(i=1, len = payments_list.rows.length; i<len; i++){
    balance = payments_list.rows[i].cells[3].innerHTML;
    action = payments_list.rows[i].cells[4].innerHTML;
    practice_id = payments_list.rows[i].cells[6].innerHTML;
    date = get_date(new Date(payments_list.rows[i].cells[7].innerHTML));

    if(balance < 0){
      payments_list.rows[i].cells[3].classList.add("text-red-700")
    }

    if(action[0] == "+"){
        payments_list.rows[i].cells[4].classList.add("text-green-500");
    }else{
        payments_list.rows[i].cells[4].classList.add("text-red-500");
    }

    if(practice_id != last_id){
        if(color == "bg-white"){
            color = "bg-gray-100";
        }else{
            color = "bg-white";
        }

        last_id = practice_id;
        balance_list.push(last_balance);
        label_list.push(last_date);
    }
    payments_list.rows[i].classList.add(color);

    last_balance = balance;
    last_date = date;
}

function get_date(date){
    year = date.getFullYear();
    month = date.getMonth() + 1;
    day = date.getDate()

    return year + "-" + month + "-" + day; 
}

month_list = [];
balance_list = [];
date_list = [];

month_from = new Date(2021, 7, 1);
month_to = new Date();
month_to.setMonth(month_to.getMonth());
month_list.push(["-", "-"])
month_list.push([month_from.getFullYear(), "-"]);

while (month_from.getTime() <= month_to.getTime()){
  if(month_list[month_list.length - 1][0] != month_from.getFullYear()){
    month_list.push([month_from.getFullYear(), "-"])
  }

  month = month_from.getMonth() + 1;
  month_from.setMonth(month);
}


Object.keys(date_id).forEach(key => {
  key_list = key.split("-");
  month_list.push([key_list[0], key_list[1]])
});

function add_option(){
  month_list.forEach(month => {
    var option = document.createElement("option");
    format_month = month[0] + "-" + month[1];

    option.value = format_month;
    option.text = month[0] + "年" + month[1] + "月";
    select_obj.appendChild(option);
    
    if((month[1] != "-") && (month[1] != "-")){
      month_name = month_from.getFullYear() + "年" + month_from.getMonth() + 1 + "月";
      date_list.push(format_month);
      balance_list.push(parseInt(get_total(format_month, "+")[0]) - parseInt(get_total(format_month, "-")[0]));
    }
  });
}

add_option();

var income_chart = new Chart(income_chart_obj,{
  type: "pie",
  data: {
    datasets: [{
      data: []
    }],
    labels: []
  }
});

var spending_chart = new Chart(spending_chart_obj,{
  type: "pie",
  data: {
    datasets: [{
      data: []
    }],
    labels: []
  }
});

select_change("---");

balance = payment[latest_id]["balance"];

balance_obj.innerHTML = "￥" + balance;

if(balance < 0){
  balance_obj.classList.add("text-red-400");
}

last_practice_id = 0;
balance = 0;

ave_balance = 0;

balance_list.forEach(value => {
  ave_balance += value;
});

ave_balance = Math.round(ave_balance / balance_list.length)

ave_balance_obj.innerHTML = "￥" + ave_balance;


var balance_chart = new Chart(balance_chart_obj,{
  type: "line",
  data: {
    labels: date_list.reverse(),
    datasets: [{
      label: "残高",
      data: balance_list.reverse(),
      lineTension: 0,
      fill: false,
    },
    {
      label: "0",
      data: Array(date_list.length).fill(0),
      lineTension: 0,
      borderColor: "#68d391",
      backgroundColor: "#68d391",
      pointRadius: 0,
      borderWidth: 0.75,
      fill: false,
    },
    {
      label: "月間平均値",
      data: Array(date_list.length).fill(ave_balance),
      lineTension: 0,
      borderColor: "#fc8181",
      backgroundColor: "#fc8181",
      pointRadius: 0,
      borderWidth: 0.75,
      fill: false,
    }],
  },
  options:{
    scales:{
      yAxes: [{
        ticks:{
          min: -50000
        }
      }]
    }
  }
});

header_row = month_payment_list.insertRow();
header_cell_1 = header_row.insertCell();
header_cell_1.innerHTML = "年月";
header_cell_1.classList.add("px-4", "py-2", "font-bold");
header_cell_2 = header_row.insertCell();
header_cell_2.innerHTML = "収益";
header_cell_2.classList.add("px-4", "py-2", "font-bold");

balance_list.forEach((val, i) => {
  row = month_payment_list.insertRow();

  if((i % 2) == 0){
    row.classList.add("bg-gray-100");
  }

  cell_1 = row.insertCell();
  cell_1.innerHTML = date_list[i];
  cell_1.classList.add("border", "px-4", "py-2");

  cell_2 = row.insertCell();
  if(val >= 0){
    cell_2.innerHTML = "+" + val;
    cell_2.classList.add("border", "px-4", "py-2", "text-green-400");
  }else{
    cell_2.innerHTML = val;
    cell_2.classList.add("border", "px-4", "py-2", "text-red-400");
  }
});

if(ave_balance < 0){
  ave_balance_obj.classList.add("text-red-700");
}

select_obj.addEventListener("change", () => {
  value = select_obj.value;

  select_change(value);
});

function select_change(value){
  income_get_total = get_total(value, "+")
  total_income = income_get_total[0];

  income_data = income_get_total[1][1];
  income_label = income_get_total[1][0];

  income_chart.data.datasets[0].data = income_data;
  income_chart.data.labels = income_label;
  income_chart.update();

  while(income_data_obj.firstChild){
    income_data_obj.removeChild(income_data_obj.firstChild);
  }

  income_label.forEach((val, i) => {
    type_income = income_data[i];
    var h = document.createElement("h1");
    h.innerHTML = val + " : ￥" + type_income + " (" + Math.round((type_income / total_income) * 100) + "%)";
    h.classList.add("text-base","m-2");
    income_data_obj.appendChild(h);
  });
  income_obj.innerHTML = "￥" + total_income;

  spending_get_total = get_total(value, "-");
  total_spending = spending_get_total[0];

  spending_data = spending_get_total[1][1]
  spending_label = spending_get_total[1][0]

  spending_chart.data.datasets[0].data = spending_data;
  spending_chart.data.labels = spending_label;
  spending_chart.update();

  while(spending_data_obj.firstChild){
    spending_data_obj.removeChild(spending_data_obj.firstChild);
  }

  spending_label.forEach((val, i) => {
    type_spending = spending_data[i];
    var h = document.createElement("h1");
    h.innerHTML = val + " : ￥" + type_income + " (" + Math.round((type_spending / total_spending) * 100) + "%)";
    h.classList.add("text-base","m-2");
    spending_data_obj.appendChild(h);
  });
  spending_obj.innerHTML = "￥" + total_spending;
}


function get_total(value, positive){
  value_list = value.split("-");
  total = 0;
  request_month_list = [];
  action_type = [];
  
  if(value == "---"){
    Object.keys(date_id).forEach(key => {
      request_month_list.push(date_id[key]);
    });

    request_month_list = request_month_list.flat();
  }else if(value_list[1] == ""){
    Object.keys(date_id).forEach(key => {
      if(key.substr(0,4) == value_list[0]){
        request_month_list.push(date_id[key]);
      }
    });

    request_month_list = request_month_list.flat();
  }else{
    request_month_list = date_id[value];
  }

  request_month_list.forEach(id => {
    action = payment[id]["action"];
    if(action[0] == positive){
      total += parseInt(action.substr(1));
      
      if(action_type[payment[id]["name"]]){
        action_type[payment[id]["name"]] += parseInt(action.substr(1));
      }else{
        action_type[payment[id]["name"]] = parseInt(action.substr(1));
      }
    }
  });

  output = [[],[]];

  Object.keys(action_type).forEach(key => {
    output[0].push(key);
    output[1].push(action_type[key]);
  });

  return [total, output];
}

action_input_obj.addEventListener("input", () => {
  action_input = action_input_obj.value;

  if(action_input == ""){
    action_input = 0;
  }

  if(action_input[0] == "-"){
    decision_balance_obj.innerHTML = "決済後の残高：￥" + (balance - parseInt(action_input.substr(1)));
  }else{
    decision_balance_obj.innerHTML = "決済後の残高：￥" + (balance + parseInt(action_input));
  }
});

decision_btn.addEventListener("click", () => {
  const params = new URLSearchParams();
  params.append("type", type_input_obj.value);
  params.append("name", name_input_obj.value);
  params.append("action", action_input_obj.value);

  axios.post("settlement.php", params)
  .then(result => {
    name_input_obj.value = "";
    action_input_obj.value = "";
    decision_balance_obj.innerHTML = "決済後の残高：";
  });
});