const employerPages = [
    "employer-dashboard.html",
    "employer-vacancies.html",
    "employer-review.html",
    "employer-profile.html",
    "employer-signout.html"
];

function getCurrentEmployerPageIndex() {
    let currentPage = window.location.pathname.split("/").pop();
    return employerPages.indexOf(currentPage);
}

function previousEmployerPage() {
    let index = getCurrentEmployerPageIndex();

    if (index > 0) {
        window.location.href = employerPages[index - 1];
    } else {
        alert("You are already on the first page.");
    }
}

function nextEmployerPage() {
    let index = getCurrentEmployerPageIndex();

    if (index < employerPages.length - 1) {
        window.location.href = employerPages[index + 1];
    } else {
        alert("You are already on the last page.");
    }
}

function contactEmployer() {
    alert(
        "MyKerjaConnectUTeM\n\n" +
        "Email: mykerjaconnect@utem.edu.my\n" +
        "Phone: +606-270 1000\n" +
        "Address: Universiti Teknikal Malaysia Melaka"
    );
}

function editEmployerItem(itemName) {
    alert("Editing: " + itemName);
}

function deleteEmployerRow(button) {
    let confirmDelete = confirm("Are you sure you want to delete this vacancy?");

    if (confirmDelete) {
        let row = button.parentElement.parentElement;
        row.remove();
        alert("Vacancy deleted successfully.");
    }
}

function addVacancy() {
    alert("Add Vacancy button clicked.");
}

function approveApplication(button) {
    alert("Application approved.");
}

function rejectApplication(button) {
    let confirmReject = confirm("Reject this application?");

    if (confirmReject) {
        let row = button.parentElement.parentElement;
        row.remove();
        alert("Application rejected.");
    }
}

function updateProfile() {
    alert("Profile updated successfully.");
}

function cancelEmployerSignOut() {
    window.location.href = "employer-dashboard.html";
}

function confirmEmployerSignOut() {
    alert("You have signed out successfully.");
    window.location.href = "home.html";
}