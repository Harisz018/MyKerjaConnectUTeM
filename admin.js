const adminPages = [
    "admin-dashboard.html",
    "admin-users.html",
    "admin-vacancies.html",
    "admin-reports.html",
    "admin-signout.html"
];

function getCurrentPageIndex() {
    let currentPage = window.location.pathname.split("/").pop();
    return adminPages.indexOf(currentPage);
}

function previousPage() {
    let index = getCurrentPageIndex();

    if (index > 0) {
        window.location.href = adminPages[index - 1];
    } else {
        alert("You are already on the first page.");
    }
}

function nextPage() {
    let index = getCurrentPageIndex();

    if (index < adminPages.length - 1) {
        window.location.href = adminPages[index + 1];
    } else {
        alert("You are already on the last page.");
    }
}

function contactUs() {
    alert("Contact Us: mykerjaconnect@utem.edu.my");
}

function editItem(itemName) {
    alert("Edit selected: " + itemName);
}

function deleteRow(button) {
    let confirmDelete = confirm("Are you sure you want to delete this item?");

    if (confirmDelete) {
        let row = button.parentElement.parentElement;
        row.remove();
        alert("Item deleted successfully.");
    }
}

function generateReport() {
    alert("System report generated successfully.");
}

function cancelSignOut() {
    window.location.href = "admin-dashboard.html";
}

function confirmSignOut() {
    alert("You have signed out successfully.");
    window.location.href = "home.html";
}

function searchUsers() {
    let searchInput = document.getElementById("userSearch").value.toLowerCase();
    let filterValue = document.getElementById("userFilter").value.toLowerCase();
    let table = document.getElementById("userTable");
    let rows = table.getElementsByTagName("tr");

    for (let i = 1; i < rows.length; i++) {
        let name = rows[i].getElementsByTagName("td")[0].innerText.toLowerCase();
        let role = rows[i].getElementsByTagName("td")[1].innerText.toLowerCase();
        let email = rows[i].getElementsByTagName("td")[2].innerText.toLowerCase();

        let matchSearch = name.includes(searchInput) || email.includes(searchInput);
        let matchFilter = filterValue === "all" || role === filterValue;

        rows[i].style.display = matchSearch && matchFilter ? "" : "none";
    }
}

function searchJobs() {
    let jobInput = document.getElementById("jobSearch").value.toLowerCase();
    let employerInput = document.getElementById("employerSearch").value.toLowerCase();
    let table = document.getElementById("jobTable");
    let rows = table.getElementsByTagName("tr");

    for (let i = 1; i < rows.length; i++) {
        let job = rows[i].getElementsByTagName("td")[0].innerText.toLowerCase();
        let employer = rows[i].getElementsByTagName("td")[1].innerText.toLowerCase();

        let matchJob = job.includes(jobInput);
        let matchEmployer = employer.includes(employerInput);

        rows[i].style.display = matchJob && matchEmployer ? "" : "none";
    }
}