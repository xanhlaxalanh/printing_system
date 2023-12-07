
function _(id) {
    return document.getElementById(id)
}

var timer = null;
function auto_reload(path) {
    window.location = path;
}
function OpenPopup(id) {
    _(id).classList.add("open-popup");
}
function ClosePopup(id, path) {
    _(id).classList.add("close-popup");
    auto_reload(path);
}
/* Design Calendar */
const daysTag = document.querySelector(".days"),
    currentDate = document.querySelector(".current-date"),
    prevNextIcon = document.querySelectorAll(".icons i");

// getting new date, current year and month
let date = new Date(),
    currYear = date.getFullYear(),
    currMonth = date.getMonth();

// storing full name of all months in array
const months = ["January", "February", "March", "April", "May", "June", "July",
    "August", "September", "October", "November", "December"];

const renderCalendar = () => {
    let firstDayofcurMonth = new Date(currYear, currMonth, 1).getDay(), // getting first day of current month
        lastDateofcurMonth = new Date(currYear, currMonth + 1, 0).getDate(), // getting last date of current month
        lastDayofcurMonth = new Date(currYear, currMonth, lastDateofcurMonth).getDay(), // getting last day of currentmonth
        lastDateofLastMonth = new Date(currYear, currMonth, 0).getDate(); // getting last date of previous month
    /*getDay(): get weekday from 0-6; getDate(): get day from 1-31 */
    let liTag = "";
    /*Firstly, push last days of previous month */
    for (let i = firstDayofcurMonth; i > 0; i--) { // creating li of previous month last days
        liTag += `<li onclick="delParticularDay(this)" class="inactive">${lastDateofLastMonth - i + 1}<span> ${currMonth + 1}</span><span> ${currYear}</span></li>`;
    }
    /*Secondly, push days of current month */
    for (let i = 1; i <= lastDateofcurMonth; i++) { // creating li of all days of current month
        // adding active class to li if the current day, month, and year matched
        let isToday = i === date.getDate() && currMonth === new Date().getMonth()
            && currYear === new Date().getFullYear() ? "today" : "";
        liTag += `<li onclick=" delParticularDay(this)" class="${isToday}">${i}<span> ${currMonth + 1}</span><span> ${currYear}</span></li>`;
    }
    /*Lastly, push days of next month */
    for (let i = lastDayofcurMonth; i < 6; i++) { // creating li of next month first days
        liTag += `<li onclick=" delParticularDay(this)" class="inactive">${i - lastDayofcurMonth + 1}<span> ${currMonth + 1}</span><span> ${currYear}</span></li>`
    }
    currentDate.innerText = `${months[currMonth]} ${currYear}`; // passing current mon and yr as currentDate text
    daysTag.innerHTML = liTag;
}
renderCalendar(); //display current month

prevNextIcon.forEach(icon => { // getting prev and next icons
    icon.addEventListener("click", () => { // adding click event on both icons
        // if clicked icon is previous icon then decrement current month by 1 else increment it by 1
        currMonth = icon.id === "prev" ? currMonth - 1 : currMonth + 1;

        if (currMonth < 0 || currMonth > 11) { // if current month is less than 0 or greater than 11
            // creating a new date of current year & month and pass it as date value
            date = new Date(currYear, currMonth, new Date().getDate());
            currYear = date.getFullYear();// updating current year with new date year
            currMonth = date.getMonth(); // updating current month with new date month
        } else {
            date = new Date(); // pass the current date as date value
        }
        renderCalendar(); // calling renderCalendar function to display selected month
    });
});
function delParticularDay(obj) {
    obj.classList.toggle('active');
}
function search(numcol, tablename) {
    // Declare variables
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("searchInput");
    filter = input.value.toUpperCase();
    table = document.getElementById(tablename);
    tr = table.getElementsByTagName("tr");

    // Loop through all table rows, and hide those who don't match the search query
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[numcol];
        if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}