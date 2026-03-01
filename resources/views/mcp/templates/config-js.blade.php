// Configuration
const Config = {
    Debug: false,

    // Add your config options here
    Options: {
        example: true
    }
};

// Export for use in other files
if (typeof exports !== 'undefined') {
    exports('GetConfig', () => Config);
}
