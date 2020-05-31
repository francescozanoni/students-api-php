const path = require("path");

module.exports = {
    entry: "./resources/client.js",
    mode: "development",
    output: {
        path: path.resolve(__dirname, "public"),
        filename: "client.js",
        library: "libraries",
        libraryTarget: "var",
    }
};
