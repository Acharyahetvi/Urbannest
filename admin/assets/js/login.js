const toggle = document.getElementById('togglePassword');
  const password = document.getElementById('password');
  toggle.addEventListener('click', () => {
    const isHidden = password.type === 'password';
    password.type = isHidden ? 'text' : 'password';
    toggle.classList.toggle('fa-eye');
    toggle.classList.toggle('fa-eye-slash');
  });