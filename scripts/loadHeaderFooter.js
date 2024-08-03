document.addEventListener("DOMContentLoaded", function() {
    // Load the common head content
    fetch('/globalContent/head.html')
        .then(response => response.text())
        .then(headContent => {
            // Create a temporary div to parse the head content
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = headContent;

            // Append each <link> and <script> to the head
            const links = tempDiv.querySelectorAll('link');
            links.forEach(link => document.head.appendChild(link));

            const scripts = tempDiv.querySelectorAll('script');
            scripts.forEach(script => {
                const newScript = document.createElement('script');
                newScript.src = script.src; // Use the src from the fetched script
                newScript.async = true; // Optional: load scripts asynchronously
                document.head.appendChild(newScript);
            });
        })
        .catch(error => console.error('Error loading head content:', error));

    // Load the header content
    fetch('/globalContent/header.html')
        .then(response => response.text())
        .then(headerContent => {
            // Inject header content
            document.getElementById('header-container').innerHTML = headerContent;

            // Load any scripts that are part of the header
            const headerScripts = document.querySelectorAll('#header-container script');
            headerScripts.forEach(script => {
                const newScript = document.createElement('script');
                newScript.src = script.src; // Use the src from the fetched script
                newScript.async = true; // Optional: load scripts asynchronously
                document.body.appendChild(newScript);
            });
        })
        .catch(error => console.error('Error loading header content:', error));

    // Load the footer content
    fetch('/globalContent/footer.html')
        .then(response => response.text())
        .then(footerContent => {
            // Inject footer content
            document.getElementById('footer-container').innerHTML = footerContent;

            // Load any scripts that are part of the footer
            const footerScripts = document.querySelectorAll('#footer-container script');
            footerScripts.forEach(script => {
                const newScript = document.createElement('script');
                newScript.src = script.src; // Use the src from the fetched script
                newScript.async = true; // Optional: load scripts asynchronously
                document.body.appendChild(newScript);
            });
        })
        .catch(error => console.error('Error loading footer content:', error));
});
