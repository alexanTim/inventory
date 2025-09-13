// Collapsible Menu Functionality
// This file handles the collapsible sidebar navigation menus

document.addEventListener('DOMContentLoaded', function() {
    initializeCollapsibleMenus();
});

document.addEventListener('livewire:navigated', function() {
    // Re-initialize after Livewire navigation
    setTimeout(initializeCollapsibleMenus, 100);
});

function initializeCollapsibleMenus() {
    // Find all navlist groups with expandable attribute - multiple selectors for Flux UI
    const expandableGroups = document.querySelectorAll(
        '[data-flux-navlist-group], ' +
        'flux\\:navlist\\.group[expandable], ' +
        '[data-expandable="true"], ' +
        '.navlist-group[data-expandable]'
    );
    
    expandableGroups.forEach(group => {
        const button = group.querySelector('button, [role="button"], .disclosure-button');
        const content = group.querySelector('[data-open], .navlist-items, .group-content');
        const chevronDown = group.querySelector('[data-flux-chevron-down], .chevron-down, .rotate-180');
        const chevronRight = group.querySelector('[data-flux-chevron-right], .chevron-right, .rotate-0');
        
        if (button && content) {
            // Remove any existing event listeners
            button.removeEventListener('click', handleMenuClick);
            
            // Add click event listener
            button.addEventListener('click', handleMenuClick);
            
            function handleMenuClick(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const isOpen = group.hasAttribute('open');
                
                if (isOpen) {
                    // Close the menu
                    group.removeAttribute('open');
                    group.removeAttribute('data-open');
                    if (content) {
                        content.removeAttribute('data-open');
                        content.style.display = 'none';
                        content.classList.add('hidden');
                    }
                    
                    // Update chevron icons
                    if (chevronDown) {
                        chevronDown.style.display = 'none';
                        chevronDown.classList.add('hidden');
                    }
                    if (chevronRight) {
                        chevronRight.style.display = 'block';
                        chevronRight.classList.remove('hidden');
                    }
                } else {
                    // Open the menu
                    group.setAttribute('open', '');
                    group.setAttribute('data-open', '');
                    if (content) {
                        content.setAttribute('data-open', '');
                        content.style.display = 'block';
                        content.classList.remove('hidden');
                    }
                    
                    // Update chevron icons
                    if (chevronDown) {
                        chevronDown.style.display = 'block';
                        chevronDown.classList.remove('hidden');
                    }
                    if (chevronRight) {
                        chevronRight.style.display = 'none';
                        chevronRight.classList.add('hidden');
                    }
                }
            }
            
            // Set initial state based on expanded attribute or class
            const expanded = group.getAttribute('data-expanded') === 'true' || 
                           group.hasAttribute('expanded') ||
                           group.getAttribute(':expanded') === 'true' ||
                           group.classList.contains('expanded') ||
                           group.querySelector('[data-expanded="true"]') !== null;
            
            if (expanded) {
                group.setAttribute('open', '');
                group.setAttribute('data-open', '');
                if (content) {
                    content.setAttribute('data-open', '');
                    content.style.display = 'block';
                    content.classList.remove('hidden');
                }
                if (chevronDown) {
                    chevronDown.style.display = 'block';
                    chevronDown.classList.remove('hidden');
                }
                if (chevronRight) {
                    chevronRight.style.display = 'none';
                    chevronRight.classList.add('hidden');
                }
            } else {
                group.removeAttribute('open');
                group.removeAttribute('data-open');
                if (content) {
                    content.removeAttribute('data-open');
                    content.style.display = 'none';
                    content.classList.add('hidden');
                }
                if (chevronDown) {
                    chevronDown.style.display = 'none';
                    chevronDown.classList.add('hidden');
                }
                if (chevronRight) {
                    chevronRight.style.display = 'block';
                    chevronRight.classList.remove('hidden');
                }
            }
        }
    });
}

// Alternative approach using Alpine.js if available
if (window.Alpine) {
    Alpine.data('collapsibleMenu', () => ({
        open: false,
        toggle() {
            this.open = !this.open;
        }
    }));
}

// Export for use in other files
window.initializeCollapsibleMenus = initializeCollapsibleMenus; 