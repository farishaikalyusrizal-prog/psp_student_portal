(() => {
    'use strict';

    document.querySelectorAll('.needs-validation').forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });

    document.querySelectorAll('.password-toggle').forEach(button => {
        button.addEventListener('click', () => {
            const input = document.getElementById(button.dataset.target);
            if (!input) return;
            const hidden = input.type === 'password';
            input.type = hidden ? 'text' : 'password';
            button.innerHTML = hidden ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
        });
    });

    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', event => {
            const name = form.dataset.name || 'this student';
            if (!window.confirm(`Delete ${name} permanently? This action cannot be undone.`)) {
                event.preventDefault();
            }
        });
    });
})();
