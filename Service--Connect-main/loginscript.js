const formTitle = document.getElementById('form-title');
const signupForm = document.getElementById('signup-form');
const loginForm = document.getElementById('login-form');
const toggleText = document.getElementById('toggle-link');
const toggleMessage = document.getElementById('toggle-message');

let isSignup = true;

toggleText.addEventListener('click', (e) => {
    e.preventDefault();
    isSignup = !isSignup;

    if (isSignup) {
        formTitle.textContent = 'Signup';
        signupForm.classList.remove('hidden');
        loginForm.classList.add('hidden');
        toggleMessage.textContent = 'Already have an account?';
        toggleText.textContent = 'Login here';
    } else {
        formTitle.textContent = 'Login';
        signupForm.classList.add('hidden');
        loginForm.classList.remove('hidden');
        toggleMessage.textContent = "Don't have an account?";
        toggleText.textContent = 'Signup here';
    }
});
