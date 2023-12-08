document.addEventListener("DOMContentLoaded", function(e) {
    getItemInLocalStorage();
})

function getItemInLocalStorage() {
    let id = localStorage.getItem("ID");
    let username = localStorage.getItem("Username");
let role = localStorage.getItem("Role");
    $.ajax({
        type: "POST",
        url: "SetSESSION.php",
        data: {
            id: id,
            username: username,
            role: role
        },
        success: function (response) {
            console.log('Get Item Successfully!');
            window.location.href = 'UserHome.php';
        }
    });
}