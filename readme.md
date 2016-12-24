# anfildro

Anfildro (ANonymous FILe DROp) is a lightweight [Lumen](https://lumen.laravel.com/)-based REST API for providing anonymous file uploads and sharing. It also includes a [Vue.js](https://vuejs.org/) single page frontend that leverages [Bootstrap](http://getbootstrap.com/) for CSS and [Axios](https://github.com/mzabriskie/axios) for http requests.

## Using the frontend

Deploying the included frontend requires an [npm](https://www.npmjs.com/)-compatible package manager (I use [yarn](https://yarnpkg.com/)) and bundling is handled via [webpack](https://webpack.js.org/). After installing dependencies via your package manager, run webpack to create the public/bundle.js file which contains the frontend and its dependencies. After that, just set ANFILDRO_CLIENT_ENABLED=true in your Lumen .env file, and the frontend will be served by Lumen.

## License

Copyright (c) 2016 [Josh Diaz](http://joshdiaz.com)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
