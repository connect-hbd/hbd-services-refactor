export default function destinationExplorer(config = {}) {
    const destinations = Array.isArray(config.destinations) ? config.destinations : [];

    return {
        destinations,
        assetBaseUrl: config.assetBaseUrl || '',
        search: config.initialSearch || '',
        country: config.initialCountry || '',
        region: config.initialRegion || '',
        sort: config.initialSort === 'popular' ? 'popular' : 'rating',
        showFilters: false,
        observer: null,

        get countryOptions() {
            return [...new Set(this.destinations.map(item => item.country).filter(Boolean))]
                .sort((a, b) => a.localeCompare(b));
        },

        get regionOptions() {
            return [...new Set(this.destinations
                .filter(item => !this.country || item.country === this.country)
                .map(item => item.region).filter(Boolean))]
                .sort((a, b) => a.localeCompare(b));
        },

        get filteredDestinations() {
            const needle = this.search.trim().toLocaleLowerCase();
            return this.destinations
                .filter(item => {
                    if (this.country && item.country !== this.country) return false;
                    if (this.region && item.region !== this.region) return false;
                    if (!needle) return true;

                    const text = [item.name, item.title, item.country, item.region,
                        item.description, item.avgCost, item.visaProcessing,
                        ...(item.topUniversities || []), ...(item.popularFor || [])]
                        .filter(value => value !== null && value !== undefined)
                        .join(' ').toLocaleLowerCase();
                    return text.includes(needle);
                })
                .sort((a, b) => this.sort === 'popular'
                    ? (Number(b.reviewsCount) || 0) - (Number(a.reviewsCount) || 0)
                        || (Number(b.rating) || 0) - (Number(a.rating) || 0)
                    : (Number(b.rating) || 0) - (Number(a.rating) || 0)
                        || (Number(b.reviewsCount) || 0) - (Number(a.reviewsCount) || 0));
        },

        get count() { return this.filteredDestinations.length; },
        get total() { return this.destinations.length; },

        imageUrl(path) {
            if (!path) return '';
            if (/^https?:\/\//i.test(path)) return path;
            return `${this.assetBaseUrl.replace(/\/$/, '')}/${String(path).replace(/^\//, '')}`;
        },

        setCountry(value) {
            this.country = value;
            if (!this.regionOptions.includes(this.region)) this.region = '';
        },

        setRegion(value) { this.region = value; },
        setSort(value) { this.sort = value === 'popular' ? 'popular' : 'rating'; },

        resetFilters() {
            this.search = '';
            this.country = '';
            this.region = '';
            this.sort = 'rating';
        },

        init() {
            this.$nextTick(() => this.initRevealObserver());
            this.$watch('filteredDestinations', () => this.$nextTick(() => this.observeRevealElements()));
        },

        initRevealObserver() {
            if (!('IntersectionObserver' in window)) {
                this.$root.querySelectorAll('[data-reveal]').forEach(el => el.classList.add('is-visible'));
                return;
            }
            this.observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
            this.observeRevealElements();
        },

        observeRevealElements() {
            if (!this.observer) return;
            this.$root.querySelectorAll('[data-reveal]:not(.is-visible)')
                .forEach(el => this.observer.observe(el));
        },

        destroy() {
            if (this.observer) this.observer.disconnect();
        },
    };
}
