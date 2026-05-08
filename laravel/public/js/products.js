
setTimeout(() => {

    let alerts = document.querySelectorAll(
        '.alert-success, .alert-search'
    );

    alerts.forEach(alert => {
        alert.style.display = 'none';
    });

}, 3000);
