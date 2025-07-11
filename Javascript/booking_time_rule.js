document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('schedule-table');

    table.addEventListener('change', function(event) {
        if (event.target.type === 'radio') {
            const inputs = table.querySelectorAll('input[type="radio"]');
            inputs.forEach(input => {
                if (input !== event.target) {
                    input.checked = false;
                }
            });
        }
    });
});