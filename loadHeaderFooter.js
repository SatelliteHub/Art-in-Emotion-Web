// loadContent.js
document.addEventListener("DOMContentLoaded", function() {
    // Load the common head content and header/footer
    fetch('/header-footer.html')
        .then(response => response.text())
        .then(data => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(data, 'text/html');

            // Inject common head content (excluding <title>)
            const headContent = doc.head.innerHTML
                .replace(/<title>[^<]*<\/title>/, ''); // Remove <title> tag if it exists
            document.head.innerHTML = headContent;

            // Inject header and footer content
            document.getElementById('header-container').innerHTML = doc.querySelector('#header').outerHTML;
            document.getElementById('footer-container').innerHTML = doc.querySelector('#footer').outerHTML;

            // Set the page-specific title
            setPageTitle();
            
            // Highlight the current page
            highlightCurrentPage();
        })
        .catch(error => console.error('Error loading HTML:', error));
});

function setPageTitle() {
    // Example logic for setting the title based on the page
    const titles = {
        'index.html': 'Art in Emotion',
        'about.html': 'About - Art in Emotion',
        'contact.html': 'Contact - Art in Emotion',
        'privacypolicy.html': 'Privacy Policy - Art in Emotion'
    };

    const currentPath = window.location.pathname.split('/').pop();
    document.title = titles[currentPath] || 'Art in Emotion'; // Default title if not found
}

function highlightCurrentPage() {
    const currentPath = window.location.pathname;
    const links = document.querySelectorAll('.nav-link');

    links.forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        }
    });
}
