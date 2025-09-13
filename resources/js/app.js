import './bootstrap';
import Alpine from 'alpinejs';
import { initFlowbite } from 'flowbite';
import ApexCharts from 'apexcharts';
import Swal from 'sweetalert2';
import { Html5Qrcode } from 'html5-qrcode';
import './collapsible-menu.js';

// Initialize Alpine.js first - only if not already present
if (!window.Alpine) {
    window.Alpine = Alpine;
    console.log('[App.js] Alpine.js initialized');
} else {
    console.log('[App.js] Alpine.js already exists, using existing instance');
}

// Flux is loaded by @fluxScripts directive, so we don't need to load it here
// Just initialize the theme system when Flux becomes available
if (!window.fluxThemeInitialized) {
    window.fluxThemeInitialized = true;
    
    document.addEventListener('DOMContentLoaded', function() {
        // Check if Flux is already loaded
        if (window.Flux) {
            console.log('[App.js] Flux already loaded, initializing theme');
            initializeFluxTheme();
        } else {
            // Wait for Flux to be loaded by @fluxScripts
            const checkFlux = setInterval(() => {
                if (window.Flux) {
                    console.log('[App.js] Flux detected, initializing theme');
                    clearInterval(checkFlux);
                    initializeFluxTheme();
                }
            }, 100);
            
            // Fallback after 5 seconds if Flux doesn't load
            setTimeout(() => {
                clearInterval(checkFlux);
                if (!window.Flux) {
                    console.log('[App.js] Flux not loaded after timeout, using fallback');
                    initializeFallbackTheme();
                }
            }, 5000);
        }
    });
}

// Function to initialize Flux theme per official documentation
function initializeFluxTheme() {
    if (window.Flux) {
        console.log('[App.js] Flux utilities available');
        
        // Flux handles theme automatically via @fluxAppearance directive
        // No manual intervention needed per documentation
        
        // Optional: Listen for theme changes for debugging
        document.addEventListener('flux:appearance-changed', function(event) {
            console.log('[App.js] Flux theme changed:', event.detail);
        });
    } else {
        console.warn('[App.js] Flux not available, initializing fallback');
        initializeFallbackTheme();
    }
}

// Fallback theme system if Flux is not available
function initializeFallbackTheme() {
    const savedTheme = localStorage.getItem('flux.appearance') || 'system';
    console.log('[App.js] Fallback theme:', savedTheme);
    
    applyThemeDirectly(savedTheme);
    
    // Create fallback $flux global for Alpine compatibility
    if (!window.$flux) {
        window.$flux = {
            appearance: savedTheme,
            get dark() {
                return document.documentElement.classList.contains('dark');
            },
            set dark(value) {
                const newTheme = value ? 'dark' : 'light';
                this.appearance = newTheme;
                localStorage.setItem('flux.appearance', newTheme);
                applyThemeDirectly(newTheme);
            }
        };
    }
}

// Function to apply theme directly - now our primary theme application method
function applyThemeDirectly(theme) {
    console.log('[App.js] Applying theme directly:', theme);
    
    // Store the theme first
    localStorage.setItem('flux.appearance', theme);
    
    // Always clear existing classes first to ensure clean state
    document.documentElement.classList.remove('dark');
    document.body.classList.remove('dark');
    
    if (theme === 'dark') {
        document.documentElement.classList.add('dark');
        document.body.classList.add('dark');
        console.log('[App.js] Applied dark theme to html and body');
    } else if (theme === 'light') {
        console.log('[App.js] Applied light theme (removed dark classes)');
    } else if (theme === 'system') {
        const isDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        if (isDark) {
            document.documentElement.classList.add('dark');
            document.body.classList.add('dark');
            console.log('[App.js] Applied dark theme (system preference) to html and body');
        } else {
            console.log('[App.js] Applied light theme (system preference)');
        }
        
        // Listen for system theme changes
        if (window.matchMedia) {
            const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
            mediaQuery.addListener(function(e) {
                if (localStorage.getItem('flux.appearance') === 'system') {
                    console.log('[App.js] System theme changed:', e.matches ? 'dark' : 'light');
                    if (e.matches) {
                        document.documentElement.classList.add('dark');
                        document.body.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                        document.body.classList.remove('dark');
                    }
                }
            });
        }
    }
    
    // Verify theme was applied correctly
    const isCurrentlyDark = document.documentElement.classList.contains('dark');
    const bodyIsDark = document.body.classList.contains('dark');
    console.log('[App.js] Theme verification - HTML dark:', isCurrentlyDark, 'Body dark:', bodyIsDark);
}

// Debug logging
console.log('[App.js] App.js loaded successfully');

// Initialize Alpine.js for Livewire
if (!window.Alpine || !window.Alpine.version) {
    console.log('[App.js] Setting up Alpine.js for Livewire...');
    window.Alpine = Alpine;
    console.log('[App.js] Alpine.js ready for Livewire');
} else {
    console.log('[App.js] Alpine.js already exists, skipping initialization');
}

// Apply theme on Livewire events - always ensure theme persistence
document.addEventListener("livewire:load", () => {
    console.log("[App.js] Livewire loaded and ready");
    const savedTheme = localStorage.getItem('flux.appearance') || 'system';
    console.log("[App.js] Applying saved theme on Livewire load:", savedTheme);
    applyThemeDirectly(savedTheme);
});

document.addEventListener("livewire:navigating", () => {
    console.log("[App.js] 🔄 Livewire navigation starting - SPA mode active");
});

document.addEventListener("livewire:navigated", () => {
    console.log("[App.js] ✅ Livewire navigation completed - SPA transition successful");
    const savedTheme = localStorage.getItem('flux.appearance') || 'system';
    console.log("[App.js] Applying saved theme on navigation:", savedTheme);
    applyThemeDirectly(savedTheme);
    
    // Preserve sidebar state after navigation
    setTimeout(() => {
        preserveSidebarState();
    }, 100);
});

// Debug navigation issues
document.addEventListener("click", (event) => {
    const target = event.target.closest('a, flux\\:navlist\\:item');
    if (target && target.hasAttribute('wire:navigate')) {
        console.log("[App.js] 🔗 Livewire navigation link clicked:", target.href || target.getAttribute('href'));
    } else if (target && target.tagName === 'A') {
        console.log("[App.js] ⚠️ Regular link clicked (may cause full page refresh):", target.href);
    }
});

// Listen for custom theme change events
window.addEventListener('theme-changed', (event) => {
    console.log('[App.js] Theme changed event received:', event.detail);
    const theme = event.detail.appearance;
    applyThemeDirectly(theme);
});

// Ensure theme is applied on every page load
document.addEventListener('DOMContentLoaded', function() {
    const savedTheme = localStorage.getItem('flux.appearance') || 'system';
    console.log('[App.js] DOM ready, ensuring theme is applied:', savedTheme);
    applyThemeDirectly(savedTheme);
});

// Also apply on window load as a final safeguard
window.addEventListener('load', function() {
    const savedTheme = localStorage.getItem('flux.appearance') || 'system';
    console.log('[App.js] Window loaded, final theme check:', savedTheme);
    applyThemeDirectly(savedTheme);
});

// Configure CSRF token for all requests
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (csrfToken) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
    console.log('CSRF token configured for requests');
} else {
    console.warn('CSRF token not found');
}

// Configure Livewire to use our Alpine instance and prevent conflicts
if (typeof window.Alpine !== 'undefined') {
    window.livewireScriptConfig = {
        alpine: window.Alpine
    };
    console.log('Livewire configured to use existing Alpine instance');
    
    // Prevent Livewire from loading its own Alpine instance
    window.livewireScriptConfig = {
        ...window.livewireScriptConfig,
        skipAlpine: true
    };
} else {
    console.log('Alpine not available yet, Livewire will use its own instance');
}

// Apply theme on DOM ready (fallback only)
document.addEventListener('DOMContentLoaded', function() {
    if (!window.Flux) {
        const savedTheme = localStorage.getItem('flux.appearance') || 'system';
        console.log('[App.js] DOM ready, applying fallback theme:', savedTheme);
        applyThemeDirectly(savedTheme);
    }
    
    if (typeof window.Livewire !== 'undefined') {
        console.log('[App.js] Livewire found and ready');
    } else {
        console.log('[App.js] Livewire not found yet, will be loaded by @livewireScripts');
    }
});

// Initialize on DOM ready and Livewire navigation
function initializeApp() {
    console.log('Initializing app components');
    
    // Theme handled by Flux automatically via @fluxAppearance
    // Only apply fallback if Flux is not available
    if (!window.Flux) {
        const savedTheme = localStorage.getItem('flux.appearance') || 'system';
        console.log('[App.js] Applying fallback theme on page load:', savedTheme);
        applyThemeDirectly(savedTheme);
    }
    
    // Preserve sidebar state
    preserveSidebarState();
    
    // Initialize mobile-specific features
    initializeMobileFeatures();
    
    // Initialize Flowbite components
    if (typeof initFlowbite !== 'undefined') {
        initFlowbite();
    }
    
    // Initialize charts if available
    if (document.getElementById("area-chart") && typeof ApexCharts !== 'undefined') {
        const chart = new ApexCharts(document.getElementById("area-chart"), options);
        chart.render();
    }
    if (document.getElementById("column-chart") && typeof ApexCharts !== 'undefined') {
        const chart = new ApexCharts(document.getElementById("column-chart"), options2);
        chart.render();
    }
}

// Function to preserve sidebar state
function preserveSidebarState() {
    // Prevent sidebar from collapsing on navigation
    const sidebar = document.querySelector('flux-sidebar');
    if (sidebar) {
        // Handle sidebar state based on screen size
        if (window.innerWidth >= 1024) { // lg breakpoint - desktop
            sidebar.removeAttribute('collapsed');
            sidebar.setAttribute('expanded', 'true');
            sidebar.style.width = '16rem';
            sidebar.style.transform = 'translateX(0)';
            sidebar.style.position = 'relative';
        } else { // mobile
            // Don't force hide on mobile, let the toggle work naturally
            sidebar.style.position = 'fixed';
        }
        
        // Listen for resize events to maintain state
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                sidebar.removeAttribute('collapsed');
                sidebar.setAttribute('expanded', 'true');
                sidebar.style.width = '16rem';
                sidebar.style.transform = 'translateX(0)';
                sidebar.style.position = 'relative';
            } else {
                sidebar.style.position = 'fixed';
            }
        });
        
        // Prevent collapse events on desktop
        sidebar.addEventListener('collapse', function(e) {
            if (window.innerWidth >= 1024) {
                e.preventDefault();
                e.stopPropagation();
                sidebar.removeAttribute('collapsed');
                sidebar.setAttribute('expanded', 'true');
                sidebar.style.width = '16rem';
                sidebar.style.transform = 'translateX(0)';
            }
        });
        
        // Use MutationObserver to continuously monitor and maintain state
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (window.innerWidth >= 1024) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'collapsed') {
                        sidebar.removeAttribute('collapsed');
                        sidebar.setAttribute('expanded', 'true');
                        sidebar.style.width = '16rem';
                        sidebar.style.transform = 'translateX(0)';
                    }
                    if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                        if (sidebar.style.width !== '16rem' || sidebar.style.transform !== 'translateX(0)') {
                            sidebar.style.width = '16rem';
                            sidebar.style.transform = 'translateX(0)';
                        }
                    }
                }
            });
        });
        
        observer.observe(sidebar, {
            attributes: true,
            attributeFilter: ['collapsed', 'style']
        });
    }
    
    // Preserve navlist group expansion states
    const navlistGroups = document.querySelectorAll('flux-navlist-group');
    navlistGroups.forEach(group => {
        // Store expansion state in localStorage
        const groupId = group.getAttribute('heading') || group.textContent.trim();
        const isExpanded = group.hasAttribute('expanded');
        
        if (isExpanded) {
            localStorage.setItem(`sidebar-group-${groupId}`, 'expanded');
        }
        
        // Restore expansion state on page load
        const savedState = localStorage.getItem(`sidebar-group-${groupId}`);
        if (savedState === 'expanded') {
            group.setAttribute('expanded', 'true');
        }
    });
}

// Mobile-specific features initialization
function initializeMobileFeatures() {
    console.log('[App.js] Initializing mobile features');
    
    const sidebar = document.querySelector('flux-sidebar');
    if (!sidebar) return;
    
    // Create overlay for mobile sidebar
    let overlay = document.querySelector('.sidebar-overlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.className = 'sidebar-overlay fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden';
        overlay.style.display = 'none';
        document.body.appendChild(overlay);
    }
    
    // Function to handle mobile sidebar state
    function handleMobileSidebar() {
        if (window.innerWidth < 1024) {
            // On mobile, force sidebar to be hidden by default
            sidebar.removeAttribute('expanded');
            sidebar.style.transform = 'translateX(-100%)';
            sidebar.style.position = 'fixed';
            overlay.style.display = 'none';
            document.body.style.overflow = '';
            
            // Ensure main content takes full width
            const mainContent = document.querySelector('flux-main') || document.querySelector('main') || document.querySelector('[role="main"]');
            if (mainContent) {
                mainContent.style.marginLeft = '0';
                mainContent.style.width = '100%';
                mainContent.style.maxWidth = '100%';
            }
        } else {
            // On desktop, ensure sidebar is visible
            sidebar.setAttribute('expanded', 'true');
            sidebar.style.transform = 'translateX(0)';
            sidebar.style.position = 'relative';
            overlay.style.display = 'none';
            document.body.style.overflow = '';
        }
    }
    
    // Initialize mobile sidebar state
    handleMobileSidebar();
    
    // Close sidebar when clicking overlay
    overlay.addEventListener('click', function() {
        if (window.innerWidth < 1024) {
            sidebar.removeAttribute('expanded');
            sidebar.style.transform = 'translateX(-100%)';
            overlay.style.display = 'none';
            document.body.style.overflow = '';
        }
    });
    
    // Monitor sidebar state changes
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'expanded') {
                if (window.innerWidth < 1024) {
                    if (sidebar.hasAttribute('expanded')) {
                        // Sidebar is being opened
                        sidebar.style.transform = 'translateX(0)';
                        overlay.style.display = 'block';
                        document.body.style.overflow = 'hidden';
                    } else {
                        // Sidebar is being closed
                        sidebar.style.transform = 'translateX(-100%)';
                        overlay.style.display = 'none';
                        document.body.style.overflow = '';
                    }
                }
            }
        });
    });
    
    observer.observe(sidebar, {
        attributes: true,
        attributeFilter: ['expanded']
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
        handleMobileSidebar();
    });
    
    // Add swipe-to-close functionality for mobile
    let startX = 0;
    let currentX = 0;
    
    sidebar.addEventListener('touchstart', function(e) {
        if (window.innerWidth < 1024) {
            startX = e.touches[0].clientX;
        }
    });
    
    sidebar.addEventListener('touchmove', function(e) {
        if (window.innerWidth < 1024) {
            currentX = e.touches[0].clientX;
        }
    });
    
    sidebar.addEventListener('touchend', function(e) {
        if (window.innerWidth < 1024) {
            const diffX = startX - currentX;
            if (diffX > 50) { // Swipe left to close
                sidebar.removeAttribute('expanded');
                sidebar.style.transform = 'translateX(-100%)';
                overlay.style.display = 'none';
                document.body.style.overflow = '';
            }
        }
    });
    
    console.log('[App.js] Mobile features initialized');
}



// Run on DOM ready
document.addEventListener('DOMContentLoaded', initializeApp);

// Run on Livewire navigation  
document.addEventListener('livewire:navigated', initializeApp);

window.Swal = Swal;
window.Html5Qrcode = Html5Qrcode;



// QR CODE SCANNER
window.populateCameraList = function (selectId = 'camera-select') {
    const select = document.getElementById(selectId);
    if (!select) return;

    Html5Qrcode.getCameras().then(cameras => {
        select.innerHTML = '<option value="">Select a camera...</option>';
        cameras.forEach(camera => {
            const option = document.createElement("option");
            option.value = camera.id;
            option.textContent = camera.label || `Camera ${select.length}`;
            select.appendChild(option);
        });
    }).catch((error) => {
        console.error('Camera enumeration error:', error);
        select.innerHTML = '<option value="">Unable to fetch camera list</option>';
    });
};

window.startQrScanner = function ({
    elementId = 'qr-reader',
    scanSoundId = 'scanSound',
    onScan = null,
    emitEvent = null,
    cameraId = null,
    qrboxSize = 250,
}) {
    if (!cameraId) {
        throw new Error('No camera selected!');
    }

    let html5Qr = new Html5Qrcode(elementId);
    let isScanned = false;
    let isRunning = false;
    const scanSound = document.getElementById(scanSoundId);

    const config = {
        fps: 10,
        qrbox: { width: qrboxSize, height: qrboxSize },
        aspectRatio: 1.0
    };

    const startPromise = html5Qr.start(
        cameraId,
        config,
        (decodedText) => {
            if (isScanned) return;
            isScanned = true;

            // Play scan sound
            if (scanSound) {
                scanSound.currentTime = 0;
                scanSound.play().catch((error) => {
                    console.warn('Sound playback failed:', error);
                });
            }

            // Call onScan callback if provided
            if (onScan && typeof onScan === 'function') {
                onScan(decodedText);
            }

            // Show success message
            setTimeout(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'QR Code Scanned',
                    text: decodedText,
                    confirmButtonColor: '#16a34a',
                    heightAuto: false
                }).then(() => {
                    isScanned = false;
                });
            }, 1500);
        },
        (errorMessage) => {
            // Handle scanning errors (optional)
            console.warn('QR scanning error:', errorMessage);
        }
    );

    return startPromise.then(() => {
        isRunning = true;
        return {
            stop: () => {
                if (!isRunning) {
                    return Promise.resolve();
                }
                isRunning = false;
                return html5Qr.stop().then(() => {
                    html5Qr.clear();
                    return true;
                }).catch((error) => {
                    console.error('Stop error:', error);
                    throw error;
                });
            },
            isRunning: () => isRunning,
            html5Qr: html5Qr
        };
    }).catch((error) => {
        console.error('Scanner start error:', error);
        throw new Error('Failed to start scanner: ' + error.message);
    });
};