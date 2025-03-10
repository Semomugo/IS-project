document.addEventListener('DOMContentLoaded', function() {
    const signinForm = document.getElementById('signinForm');
    
    signinForm.addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Sign in successful!');
    });
});