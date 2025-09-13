<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}" />

<title>{{ $title ?? 'Gentle Walker' }}</title>

@fluxAppearance

<!-- Sidebar and Flux setup script (Flux manages theming) -->
<script>
(function() {
    try {
        // Prevent multiple Flux registrations
        if (!window.fluxRegistered) {
            window.fluxRegistered = true;
            
            // Override the define function to prevent duplicate registrations
            const originalDefine = window.customElements.define;
            window.customElements.define = function(name, constructor, options) {
                if (window.customElements.get(name)) {
                    console.log('[Head] Flux component already registered:', name);
                    return;
                }
                return originalDefine.call(this, name, constructor, options);
            };
        }
        
        // Ensure sidebarState function is available globally before Alpine.js loads
        window.sidebarState = function() {
            return {
                storageKey: 'gentle-walker-sidebar-state',
                groupStates: {},
                expandedGroupKey: 'gentle-walker-expanded-group',
                expandedGroupId: null,
                activeItemKey: 'gentle-walker-sidebar-active-item',
                activeItem: null,
                
                init() {
                    console.debug('[Sidebar] init: start');
                    this.loadStates();
                    this.loadActiveItem();
                    this.loadExpandedGroup();
                    console.debug('[Sidebar] init: loaded', { groupStates: this.groupStates, activeItem: this.activeItem });
                    if (this.$nextTick) {
                        this.$nextTick(() => {
                            this.setupObserver();
                            console.debug('[Sidebar] init: observer attached');
                        });
                    }
                },
                
                loadStates() {
                    console.debug('[Sidebar] loadStates: reading from localStorage', this.storageKey);
                    try {
                        const saved = localStorage.getItem(this.storageKey);
                        if (saved) {
                            this.groupStates = JSON.parse(saved);
                            console.debug('[Sidebar] loadStates: restored', this.groupStates);
                        } else {
                            // Initialize all groups as collapsed by default
                            this.groupStates = {};
                            console.debug('[Sidebar] loadStates: no saved state, initializing empty');
                        }
                        
                        // Ensure all groups start collapsed unless explicitly saved as expanded
                        const allGroups = ['requisition', 'supplies', 'sales', 'shipment', 'supplier', 'customer', 'setup', 'warehouse'];
                        allGroups.forEach(groupId => {
                            if (this.groupStates[groupId] === undefined) {
                                this.groupStates[groupId] = false;
                            }
                        });
                    } catch (e) {
                        console.warn('Could not load sidebar states:', e);
                        this.groupStates = {};
                    }
                },
                
                saveStates() {
                    console.debug('[Sidebar] saveStates: writing', this.groupStates);
                    try {
                        localStorage.setItem(this.storageKey, JSON.stringify(this.groupStates));
                    } catch (e) {
                        console.warn('Could not save sidebar states:', e);
                    }
                },
                
                // Whether a group should be expanded in the UI
                shouldGroupBeExpanded(groupId, isCurrentRoute) {
                    // Keep default collapsed behavior; only expand if user expanded previously
                    const expanded = this.expandedGroupId === groupId;
                    console.debug('[Sidebar] shouldGroupBeExpanded', { groupId, expanded });
                    return expanded;
                },
                
                saveGroupState(groupId, isExpanded) {
                    console.debug('[Sidebar] saveGroupState', { groupId, isExpanded });
                    this.groupStates[groupId] = isExpanded;
                    this.saveStates();
                },
                
                toggleGroup(groupId) {
                    // Exclusive expand: keep expanded unless another group is clicked
                    console.debug('[Sidebar] toggleGroup: before', { from: this.expandedGroupId, to: groupId });
                    this.setExpandedGroup(groupId);
                    console.debug('[Sidebar] toggleGroup: after', { expandedGroupId: this.expandedGroupId });
                },
                
                setupObserver() {
                    // Watch for user clicks on expandable groups
                    const groups = this.$el.querySelectorAll('flux-navlist-group[expandable]');
                    console.debug('[Sidebar] setupObserver: groups found', groups.length);
                    
                    groups.forEach(group => {
                        const ref = group.getAttribute('x-ref');
                        if (!ref) return;
                        
                        const groupId = ref.replace('group-', '');
                        
                        // Listen for changes to the expanded attribute
                        const observer = new MutationObserver((mutations) => {
                            mutations.forEach((mutation) => {
                                if (mutation.type === 'attributes' && mutation.attributeName === 'expanded') {
                                    const isExpanded = group.hasAttribute('expanded');
                                    console.debug('[Sidebar] MutationObserver: expanded changed', { groupId, isExpanded });
                                    
                                    if (!this.navigationTriggeredChange) {
                                        this.saveGroupState(groupId, isExpanded);
                                    }
                                }
                            });
                        });
                        
                        observer.observe(group, {
                            attributes: true,
                            attributeFilter: ['expanded']
                        });
                    });
                },
                
                navigationTriggeredChange: false,
                
                setNavigationTriggeredChange(value) {
                    this.navigationTriggeredChange = value;
                },
                
                // Function to reset all groups to collapsed state
                resetAllGroups() {
                    const allGroups = ['requisition', 'supplies', 'sales', 'shipment', 'supplier', 'customer', 'setup', 'warehouse'];
                    allGroups.forEach(groupId => {
                        this.groupStates[groupId] = false;
                    });
                    this.saveStates();
                },
                
                // Persist the currently active nav item (for highlighting)
                loadActiveItem() {
                    console.debug('[Sidebar] loadActiveItem: reading', this.activeItemKey);
                    try {
                        const savedActive = localStorage.getItem(this.activeItemKey);
                        this.activeItem = savedActive ? JSON.parse(savedActive) : null;
                        console.debug('[Sidebar] loadActiveItem: restored', this.activeItem);
                    } catch (e) {
                        console.warn('Could not load active nav item:', e);
                        this.activeItem = null;
                    }
                },
                saveActiveItem() {
                    console.debug('[Sidebar] saveActiveItem: writing', this.activeItem);
                    try {
                        localStorage.setItem(this.activeItemKey, JSON.stringify(this.activeItem));
                    } catch (e) {
                        console.warn('Could not save active nav item:', e);
                    }
                },
                setActiveItem(itemId) {
                    console.debug('[Sidebar] setActiveItem', { from: this.activeItem, to: itemId });
                    this.activeItem = itemId;
                    this.saveActiveItem();
                },

                // Exclusive expanded group persistence
                loadExpandedGroup() {
                    console.debug('[Sidebar] loadExpandedGroup: reading', this.expandedGroupKey);
                    try {
                        const saved = localStorage.getItem(this.expandedGroupKey);
                        this.expandedGroupId = saved ? JSON.parse(saved) : null;
                        console.debug('[Sidebar] loadExpandedGroup: restored', this.expandedGroupId);
                    } catch (e) {
                        console.warn('Could not load expanded group:', e);
                        this.expandedGroupId = null;
                    }
                },
                saveExpandedGroup() {
                    console.debug('[Sidebar] saveExpandedGroup: writing', this.expandedGroupId);
                    try {
                        localStorage.setItem(this.expandedGroupKey, JSON.stringify(this.expandedGroupId));
                    } catch (e) {
                        console.warn('Could not save expanded group:', e);
                    }
                },
                setExpandedGroup(groupId) {
                    // Make this group the only expanded one
                    this.expandedGroupId = groupId;
                    this.saveExpandedGroup();
                    // Also sync legacy groupStates for compatibility
                    Object.keys(this.groupStates).forEach(id => { this.groupStates[id] = (id === groupId); });
                    this.saveStates();
                },
            };
        };
        
    } catch (e) {
        console.error('[Head] Theme initialization failed:', e);
    }
})();
</script>

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

@vite(['resources/css/app.css', 'resources/js/app.js'])
@livewireStyles
<audio id="scanSound" src="{{ asset('scan.mp3') }}"></audio>