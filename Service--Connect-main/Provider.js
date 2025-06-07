// Sample Data Arrays (for demo purposes)
let services = [];
let bookings = [
    { id: 1, service: 'Cleaning', customer: 'Kishore', status: 'Pending' },
    { id: 2, service: 'Cooking', customer: 'Avantika', status: 'Pending' }
];

// Show Only Selected Section
function showSection(section) {
    document.querySelectorAll('.main-content > div').forEach(div => {
        div.style.display = 'none';
    });
    document.querySelector(`.${section}`).style.display = 'block';
}

document.querySelector('.sidebar a[href="#"]:nth-child(3)').addEventListener('click', (e) => {
    e.preventDefault();
    showSection('payment-history');
});

document.querySelector('.sidebar a[href="#"]:nth-child(2)').addEventListener('click', (e) => {
    e.preventDefault();
    showSection('table-section');
});

document.querySelector('.sidebar a[href="#"]:nth-child(1)').addEventListener('click', (e) => {
    e.preventDefault();
    showSection('form-section');
});

// Add New Service Functionality
document.querySelector('.form-section button').addEventListener('click', () => {
    const name = document.querySelector('.form-section input[placeholder="Service Name"]').value;
    const details = document.querySelector('.form-section textarea[placeholder="Service Details"]').value;
    const price = document.querySelector('.form-section input[placeholder="Service Price"]').value;

    if (name && details && price) {
        services.push({ name, details, price });
        alert('Service added successfully!');
    } else {
        alert('Please fill all fields!');
    }
});

// Manage Bookings (Approve/Reject)
document.querySelectorAll('.table-section tbody tr').forEach((row) => {
    row.querySelectorAll('button').forEach((btn) => {
        btn.addEventListener('click', () => {
            const bookingId = parseInt(row.children[0].innerText);
            const action = btn.innerText;

            const booking = bookings.find(b => b.id === bookingId);
            booking.status = action === 'Approve' ? 'Approved' : 'Rejected';

            row.children[3].innerText = booking.status;
            alert(`Booking ${action.toLowerCase()}ed successfully!`);
        });
    });
});

// Notification System (Mock Example)
function showNotification(message) {
    const notification = document.createElement('div');
    notification.innerText = message;
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.backgroundColor = '#28a745';
    notification.style.color = 'white';
    notification.style.padding = '10px';
    notification.style.borderRadius = '4px';

    document.body.appendChild(notification);

    setTimeout(() => notification.remove(), 3000);
}

// Example Usage for Notification
showNotification('New booking received!');