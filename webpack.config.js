module.exports = {
    entry: {
        frontend: [
            './resources/js/frontend.js',
            './resources/css/frontend.css'
        ],
        backend: [
            './resources/js/backend.js',
            './resources/css/backend.css'
        ],
    },
    output: {
        path: __dirname + '/public/assets',
        publicPath: '/assets/',
        filename: '[name].js'
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /(node_modules)/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env']
                    }
                }
            },
            {
                test: /\.css$/,
                use: [
                    {
                        loader: "style-loader"
                    },
                    {
                        loader: "css-loader",
                    }
                ]
            }
        ]
    }
};
