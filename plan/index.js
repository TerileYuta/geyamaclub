const event_title = document.getElementById('event_title');
const event_start = document.getElementById('event_start');
const start_time = document.getElementById('start_time');
const end_time = document.getElementById('end_time');
const place = document.getElementById('place');
const add_event = document.getElementById('add_event');
const delete_event = document.getElementById('delete_event');
const practice_url = document.getElementById('practice_url');

calendar;
var date;

uri = new URL(window.location.href);
hostname = uri.hostname;

document.addEventListener('DOMContentLoaded', () => {
  var calendarEl = document.getElementById('calendar');
  calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    locale: 'ja',
    selectable: true,
    nowIndicator: true,
    eventClick: function(info) {
        click_title = info.event.title;
        click_start = info.event.start;
        date = formatDate(click_start);

        event_title.value = click_title;
        event_start.innerHTML = click_start.getFullYear() + "年" + (parseInt(click_start.getMonth()) + 1) + "月" + click_start.getDate() + "日";
        url = "http://" + hostname + "/?date=" + date + "#practice";
        practice_url.href = url;
        practice_url.innerHTML = "新しいタブで開く";
    },
    dateClick:info => {
        date = new Date(info.dateStr);

        event_title.value = "通常練習";
        event_start.innerHTML = date.getFullYear() + "年" + (parseInt(date.getMonth()) + 1) + "月" + date.getDate() + "日";
        practice_url.href = "";
        practice_url.innerHTML = "";
        place.value = "中央小学校";

        date = formatDate(date);
    },
    buttonText: {
        prev:     '<',
        next:     '>',
        prevYear: '<<',
        nextYear: '>>',
        today:    '今日',
        month:    '月',
        week:     '週',
        day:      '日',
        list:     '一覧'
    },
    events: plan_list
  });

  calendar.render();
});

add_event.addEventListener("click", () => {
    const params = new URLSearchParams();
    params.append("title", event_title.value);
    params.append("date", date);
    params.append("start_time", start_time);
    params.append("end_time", end_time);
    params.append("place", place.value);

    axios.post("add_event.php", params);

    calendar.addEvent({id: date, title : event_title.value, start: date});
    calendar.render();

    url = "http://" + hostname + "/?date=" + date + "#practice";
    practice_url.href = url;
    practice_url.innerHTML = "詳しく見る";
});

delete_event.addEventListener("click", () => {
    const params = new URLSearchParams();
    params.append("date", date);

    axios.post("remove_event.php", params);

    calendar.getEventById(date).remove();
    calendar.render();

    event_title.value = "通常練習";
    practice_url.href = "";
    practice_url.innerHTML = "詳しく知る";
    place.value = "中央小学校";
});

function formatDate(dt) {
    var y = dt.getFullYear();
    var m = ('00' + (dt.getMonth()+1)).slice(-2);
    var d = ('00' + dt.getDate()).slice(-2);
    return (y + '-' + m + '-' + d);
  }