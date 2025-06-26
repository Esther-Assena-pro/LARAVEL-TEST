import './bootstrap';
import flatpickr from './flatpickr';

document.addEventListener('DOMContentLoaded', () => {
   
    const darkModeToggle = document.getElementById('darkModeToggle');
    const moonIcon = document.getElementById('moon-icon');
    const sunIcon = document.getElementById('sun-icon');
    const html = document.documentElement;

    if (!darkModeToggle || !moonIcon || !sunIcon) {
        console.error('Dark mode elements not found:', { darkModeToggle, moonIcon, sunIcon });
        return;
    }

    const savedTheme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    if (savedTheme === 'dark') {
        html.classList.add('dark');
        moonIcon.classList.remove('hidden');
        sunIcon.classList.add('hidden');
    } else {
        html.classList.remove('dark');
        moonIcon.classList.add('hidden');
        sunIcon.classList.remove('hidden');
    }

    darkModeToggle.addEventListener('click', () => {
        const isDark = html.classList.toggle('dark');
        moonIcon.classList.toggle('hidden', !isDark);
        sunIcon.classList.toggle('hidden', isDark);
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
    });

 // Initialisation de Flatpickr
    flatpickr('.datepicker', {
        dateFormat: 'Y-m-d',
        minDate: 'today',
        locale: 'fr',
        onChange: function(selectedDates, dateStr, instance) {
            const startDateInput = document.getElementById('start-date');
            const endDateInput = document.getElementById('end-date');
            if (instance.element.id === 'start-date' && selectedDates.length > 0) {
                endDateInput._flatpickr.set('minDate', dateStr);
            }
        }
    });

    // Gestion du formulaire de réservation
    const form = document.querySelector('#booking-form');
    if (form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const startDate = document.getElementById('start-date').value;
            const endDate = document.getElementById('end-date').value;
            const resultDiv = document.getElementById('reservation-result');
            const totalPriceSpan = document.getElementById('total-price');
            if (startDate && endDate) {
                resultDiv.textContent = 'Réservation en cours...';
                const formData = new FormData(form);
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                }).then(response => response.json()).then(data => {
                    console.log('Réponse du serveur (store):', data);
                    resultDiv.textContent = data.message || 'Erreur inconnue';
                    
                    if (data.total_price) {
                        totalPriceSpan.textContent = `${data.total_price || 0} €`;
                    }
                    if (data.success && data.redirect) {
                        setTimeout(() => window.location.reload(), 2000);
                    }
                }).catch(error => {
                    resultDiv.textContent = 'Erreur: ' + error;
                    console.error('Erreur fetch (store):', error);
                });
            } else {
                resultDiv.textContent = 'Veuillez sélectionner des dates.';
            }
        });
    }

    document.querySelectorAll('[id^="delete-form-"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const bookingId = form.getAttribute('id').replace('delete-form-', '');
            fetch(form.action, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            }).then(response => response.json()).then(data => {
                console.log('Réponse du serveur (destroy):', data);
                if (data.success) {
                    document.getElementById('delete-result-' + bookingId).textContent = data.message;
                    setTimeout(() => window.location.reload(), 2000);
                } else {
                    document.getElementById('delete-result-' + bookingId).textContent = data.message || 'Erreur';
                }
            }).catch(error => {
                document.getElementById('delete-result-' + bookingId).textContent = 'Erreur: ' + error;
                console.error('Erreur fetch (destroy):', error);
            });
        });
    });
});