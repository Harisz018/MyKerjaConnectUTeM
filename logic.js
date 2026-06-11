
document.addEventListener("DOMContentLoaded", () => {
    
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');

    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', () => {
            if (sidebar.style.display === 'none') {
                sidebar.style.display = 'flex';
            } else {
                sidebar.style.display = 'none';
            }
        });
    }

    const searchJobsBtn = document.getElementById('searchJobsBtn');
    if (searchJobsBtn) {
        searchJobsBtn.addEventListener('click', () => {
            alert("Redirecting to Job Search...");
        });
    }

    const signOutBtn = document.getElementById('signOutBtn');
    if (signOutBtn) {
        signOutBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const confirmLogout = confirm("Sign Out of MyKerjaConnectUTeM?");
            if (confirmLogout) {
                window.location.href = 'index.html';
            }
        });
    }
});