const path = require("path");

module.exports = {
    entry: "./resources/client.js",
    mode: "development",
    output: {
        path: path.resolve(__dirname, "public/client"),
        filename: "lib.js",
        library: "lib",
        libraryTarget: "var",
    }
};
