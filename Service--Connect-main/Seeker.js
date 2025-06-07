document.addEventListener("DOMContentLoaded", () => {
    const links = document.querySelectorAll('.navbar a');
    const mainContent = document.querySelector('.main-content');

    // Store booked services in localStorage for data persistence
    const bookedServices = JSON.parse(localStorage.getItem('bookedServices')) || [];

    // Handle navigation clicks
    links.forEach(link => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            const section = event.target.getAttribute('onclick').replace("fetchData('", "").replace("')", "");

            loadContent(section);
        });
    });

    // Function to load content dynamically
    function loadContent(section) {
        mainContent.innerHTML = `<p class="loading">Loading ${section}...</p>`;

        setTimeout(() => {
            switch (section) {
                case 'home':
                    mainContent.innerHTML = `
                        <h2>Welcome to Service Connect</h2>
                        <p>Explore our trusted services to meet your needs.</p>
                    `;
                    break;

                case 'available-services':
                    mainContent.innerHTML = `
                        <h2>Available Services</h2>
                        <div class="service-card" data-service="Cleaning Service">
                            <div>
                                <h3>Cleaning Service</h3>
                                <p>Provider: Jane Smith</p>
                                <p>Location: Downtown</p>
                            </div>
                            <button class="hire-btn">Hire</button>
                        </div>
                        <div class="service-card" data-service="Cooking Service">
                            <div>
                                <h3>Cooking Service</h3>
                                <p>Provider: Mary Johnson</p>
                                <p>Location: Uptown</p>
                            </div>
                            <button class="hire-btn">Hire</button>
                        </div>
                        <div class="service-card" data-service="Security Personnel">
                            <div>
                                <h3>Security Personnel</h3>
                                <p>Provider: Robert Brown</p>
                                <p>Location: Midtown</p>
                            </div>
                            <button class="hire-btn">Hire</button>
                        </div>
                    `;

                    // Add "Hire" button functionality
                    document.querySelectorAll('.hire-btn').forEach(button => {
                        button.addEventListener('click', (e) => {
                            const serviceName = e.target.closest('.service-card').getAttribute('data-service');

                            if (!bookedServices.includes(serviceName)) {
                                bookedServices.push(serviceName);
                                localStorage.setItem('bookedServices', JSON.stringify(bookedServices));
                                alert(`${serviceName} has been successfully booked!`);
                            } else {
                                alert(`${serviceName} is already booked.`);
                            }
                        });
                    });
                    break;

                case 'booked-services':
                    if (bookedServices.length === 0) {
                        mainContent.innerHTML = `
                            <h2>Booked Services</h2>
                            <p>You haven't booked any services yet. Browse our services to get started!</p>
                        `;
                    } else {
                        mainContent.innerHTML = `
                            <h2>Booked Services</h2>
                            <ul class="booked-list">
                                ${bookedServices.map(service => `<li>${service}</li>`).join('')}
                            </ul>
                        `;
                    }
                    break;

                default:
                    mainContent.innerHTML = `<p class="loading">Content not found. Please try again.</p>`;
            }
        }, 1000); // Simulated loading delay for better UX
    }
});
