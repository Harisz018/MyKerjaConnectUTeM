// Form Handling Logic for Login
function handleLogin(event) {
    event.preventDefault();
    const username = document.getElementById('usernameInput').value;
    const selectedRole = document.querySelector('input[name="userRole"]:checked').value;
    localStorage.setItem('loggedInUser', username);
    
    let targetPage = "";
    switch(selectedRole) {
        case 'student': targetPage = "student-dashboard.html"; break;
        case 'admin': targetPage = "admin-dashboard.html"; break;
        case 'employer': targetPage = "employer-dashboard.html"; break;
        default: targetPage = "index.html";
    }
    window.location.href = targetPage;
}

document.addEventListener("DOMContentLoaded", () => {
    // 1. Inject Username
    const username = localStorage.getItem('loggedInUser');
    const welcomeElement = document.getElementById('welcomeMessage');
    if (username && welcomeElement) welcomeElement.innerText = "Welcome, " + username;

    // 2. Sidebar Toggle
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', () => {
            sidebar.style.display = (sidebar.style.display === 'none') ? 'flex' : 'none';
        });
    }

    // 3. Sign Out logic (Updated to clear applications)
    const signOutBtn = document.getElementById('signOutBtn');
    if (signOutBtn) {
        signOutBtn.addEventListener('click', (e) => {
            e.preventDefault();
            if (confirm("Sign Out of MyKerjaConnectUTeM?")) {
                localStorage.removeItem('loggedInUser');
                sessionStorage.removeItem('myApplications'); 
                window.location.href = 'login.html';
            }
        });
    }
    
    // 4. Session Protection
    function checkSession() {
        const loggedInUser = localStorage.getItem('loggedInUser');
        if (!loggedInUser && !window.location.pathname.includes('login.html') && !window.location.pathname.includes('index.html')) {
            window.location.href = 'login.html';
        }
    }
    checkSession();

    // 5. Application Handling Logic
    window.applyForJob = function(jobTitle, faculty) {
        if (confirm("Are you sure you want to apply for " + jobTitle + "?")) {
            const newApplication = {
                job: jobTitle,
                faculty: faculty,
                date: new Date().toLocaleDateString(),
                status: "Pending"
            };

            let applications = JSON.parse(sessionStorage.getItem('myApplications')) || [];
            applications.push(newApplication);
            
            sessionStorage.setItem('myApplications', JSON.stringify(applications));
            alert("Application for " + jobTitle + " submitted successfully!");
        }
    };

    function loadApplications() {
        const tableBody = document.getElementById('applicationTableBody');
        if (!tableBody) return;

        const applications = JSON.parse(sessionStorage.getItem('myApplications')) || [];
        
        tableBody.innerHTML = ''; 
        
        applications.forEach(app => {
            const row = `<tr>
                <td>${app.job}</td>
                <td>${app.faculty}</td>
                <td>${app.date}</td>
                <td>${app.status}</td>
            </tr>`;
            tableBody.innerHTML += row;
        });
    }

    if (window.location.pathname.includes('student-applications.html')) {
        loadApplications();
    }

    // 6. Profile Change Detection Logic
    const profileForm = document.getElementById('profileForm');
    const updateBtn = document.getElementById('updateBtn');

    if (profileForm && updateBtn) {
        profileForm.addEventListener('input', () => {
            updateBtn.style.display = 'inline-block';
        });
    }

    window.updateProfile = function() {
        if (confirm("Are you sure you want to update your profile?")) {
            alert("Profile updated successfully!");
            if (updateBtn) updateBtn.style.display = 'none';
        }
    };
});