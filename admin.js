const adminPages = [
    "admin-dashboard.html",
    "admin-users.html",
    "admin-vacancies.html",
    "admin-reports.html"
];

window.onload = function () {
    let adminName = localStorage.getItem("adminName");

    if (!adminName) {
        adminName = localStorage.getItem("username");
    }

    if (!adminName) {
        adminName = "Admin";
    }

    let adminText = document.getElementById("adminName");

    if (adminText) {
        adminText.innerHTML = "Welcome, " + adminName;
    }
};

function getCurrentPageIndex() {
    let currentPage = window.location.pathname.split("/").pop();
    return adminPages.indexOf(currentPage);
}

function previousPage() {
    let index = getCurrentPageIndex();

    if (index > 0) {
        window.location.href = adminPages[index - 1];
    } else {
        alert("Already on first page.");
    }
}

function nextPage() {
    let index = getCurrentPageIndex();

    if (index < adminPages.length - 1) {
        window.location.href = adminPages[index + 1];
    } else {
        alert("Already on last page.");
    }
}

function contactUs() {
    alert("MyKerjaConnectUTeM\n\nEmail: mykerjaconnect@utem.edu.my\nPhone: 06-1234567");
}

function editItem(name) {
    alert("Editing: " + name);
}

function deleteRow(button) {
    let confirmDelete = confirm("Are you sure you want to delete this record?");

    if (confirmDelete) {
        button.closest("tr").remove();
    }
}

function generateReport() {
    alert("Report Generated Successfully!");
}

function confirmSignOut() {
    let confirmLogout = confirm("Are you sure you want to sign out?");

    if (confirmLogout) {
        localStorage.removeItem("adminName");
        window.location.href = "home.html";
    }
}

function searchUsers() {
    let search = document.getElementById("userSearch").value.toLowerCase();
    let filter = document.getElementById("userFilter").value.toLowerCase();
    let rows = document.querySelectorAll("#userTable tr");

    for (let i = 1; i < rows.length; i++) {
        let name = rows[i].children[0].innerText.toLowerCase();
        let role = rows[i].children[1].innerText.toLowerCase();
        let email = rows[i].children[2].innerText.toLowerCase();

        rows[i].style.display =
            (name.includes(search) || email.includes(search)) &&
            (filter === "all" || role === filter)
            ? ""
            : "none";
    }
}

function searchJobs() {
    let jobSearch = document.getElementById("jobSearch").value.toLowerCase();
    let employerSearch = document.getElementById("employerSearch").value.toLowerCase();
    let rows = document.querySelectorAll("#jobTable tr");

    for (let i = 1; i < rows.length; i++) {
        let job = rows[i].children[0].innerText.toLowerCase();
        let employer = rows[i].children[1].innerText.toLowerCase();

        rows[i].style.display =
            job.includes(jobSearch) && employer.includes(employerSearch)
            ? ""
            : "none";
    }
}