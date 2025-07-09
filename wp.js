async function getPages(post_type, limit, rest) {
    let result;
    try {
        result = await $.ajax({
            url: 'wp.php',
            type: 'POST',
            dataType: "json",
            data: {
                action: "list",
                post_type: post_type,
                limit: limit,
                rest: rest
            }
        });
    }
    catch (error) {
        console.error(action, error);
    }
    return result;
}
async function getPageByName(post_type, name) {
    let result;
    try {
        result = await $.ajax({
            url: 'wp.php',
            type: 'POST',
            dataType: "json",
            data: {
                action: "get",
                post_type: post_type,
                name: name
            }
        });
    }
    catch (error) {
        console.error("get", post_type, name, error);
    }
    return result;
}

// Function to fetch and load styles for a specific page
async function loadWordPressStyles(pageId) {
    try {
        // Get the WordPress site URL from your configuration
        const siteUrl = window.wpApiSettings?.root || '/wp-json/';
        
        // Fetch page data including styles from WordPress REST API
        const response = await fetch(`${siteUrl}wp/v2/pages/${pageId}?_fields=id,styles,custom_css`);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const pageData = await response.json();
        
        // Load the styles
        if (pageData.styles) {
            // Create a style element
            const styleElement = document.createElement('style');
            styleElement.setAttribute('id', `page-${pageId}-styles`);
            styleElement.textContent = pageData.styles;
            
            // Remove any existing styles for this page
            const existingStyles = document.getElementById(`page-${pageId}-styles`);
            if (existingStyles) {
                existingStyles.remove();
            }
            
            // Append new styles to head
            document.head.appendChild(styleElement);
        }
        
        // Load any custom CSS if it exists
        if (pageData.custom_css) {
            const customStyleElement = document.createElement('style');
            customStyleElement.setAttribute('id', `page-${pageId}-custom-css`);
            customStyleElement.textContent = pageData.custom_css;
            
            const existingCustomStyles = document.getElementById(`page-${pageId}-custom-css`);
            if (existingCustomStyles) {
                existingCustomStyles.remove();
            }
            
            document.head.appendChild(customStyleElement);
        }
        
        return true;
    }
    catch (error) {
        console.error('Error loading WordPress styles:', error);
        return false;
    }
}

// Function to load styles for multiple pages
async function loadMultiplePageStyles(pageIds) {
    const loadPromises = pageIds.map(pageId => loadWordPressStyles(pageId));
    return Promise.all(loadPromises);
}

// Example usage:
// Single page
// loadWordPressStyles(123);

// Multiple pages
// loadMultiplePageStyles([123, 456, 789]);