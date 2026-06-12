function handleRegister(event) {
    event.preventDefault(); 
    
    const newUser = {
        name: document.getElementById('regName').value,
        email: document.getElementById('regEmail').value,
        password: document.getElementById('regPassword').value,
        role: document.querySelector('input[name="userType"]:checked').value
    };

    let users = JSON.parse(localStorage.getItem('registeredUsers')) || [];
    users.push(newUser);
    localStorage.setItem('registeredUsers', JSON.stringify(users));
    
    alert("Registration successful! Please log in.");
    window.location.href = "login.html";
}

function handleLogin(event) {
    event.preventDefault();
    
    const email = document.querySelector('input[name="email"]').value;
    const password = document.querySelector('input[name="password"]').value;
    const selectedRole = document.querySelector('input[name="userRole"]:checked').value;
    const username = document.getElementById('usernameInput').value;
    
    const registeredUsers = JSON.parse(localStorage.getItem('registeredUsers')) || [];
    
    const user = registeredUsers.find(u => u.email === email && u.password === password && u.role === selectedRole);

    if (user) {
        localStorage.setItem('loggedInUser', username);
        
        let targetPage = "";
        switch(selectedRole) {
            case 'student': targetPage = "student-dashboard.html"; break;
            case 'admin': targetPage = "admin-dashboard.html"; break;
            case 'employer': targetPage = "employer-dashboard.html"; break;
            default: targetPage = "index.html";
        }
        window.location.href = targetPage;
    } else {
        alert("Access Denied: You are not registered or the credentials do not match.");
    }
}

document.addEventListener("DOMContentLoaded", () => {
    const username = localStorage.getItem('loggedInUser');
    const welcomeElement = document.getElementById('welcomeMessage');
    if (username && welcomeElement) welcomeElement.innerText = "Welcome, " + username;

    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', () => {
            sidebar.style.display = (sidebar.style.display === 'none') ? 'flex' : 'none';
        });
    }

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
    
    function checkSession() {
        const loggedInUser = localStorage.getItem('loggedInUser');
        const restrictedPages = ['student-dashboard.html', 'admin-dashboard.html', 'employer-dashboard.html', 'student-applications.html'];
        
        if (!loggedInUser && restrictedPages.some(page => window.location.pathname.includes(page))) {
            window.location.href = 'login.html';
        }
    }
    checkSession();

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
