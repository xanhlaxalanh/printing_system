
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