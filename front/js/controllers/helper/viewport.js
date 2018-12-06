let viewport = {
    xs: 480,
    sm: 768,
    md: 1024,
    lg: 1140,
    xl: 1300,
    isMobile() {
        return window.innerWidth < this.sm;
    },
};

export { viewport };
