# About

This package provides a simple command to download styles and scripts from a given template URL. It also generates a `webpack.mix.js` file for easy asset compilation.

# Installation

```bash
composer require juzaweb/laravel-download-template --dev
```

# Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=template-downloader-config
```


# Usage

## Download Styles

This command is particularly useful when you're integrating a static template into a Laravel project and want to fetch its external style and script files directly.

---

### 🧾 Command Signature

```bash
php artisan style:download
```

---

### 🛠 Features

* Downloads all external CSS and JS files from a given template URL.
* Excludes certain domains from being downloaded.
* Optionally generates a `webpack.mix.js` file for easy asset compilation.
* Parses and downloads assets referenced within CSS files (e.g., fonts, images — if enabled).

---

### 🔧 Options

| Option         | Shortcut | Description                              | Default      |
| -------------- | -------- | ---------------------------------------- | ------------ |
| `--output`     | `-o`     | Output directory to save files           | `resources`  |
| `--mix`        |          | Generate `webpack.mix.js` file           | false        |
| `--mix-output` |          | Directory where Mix output will be saved | `''` (empty) |

---

### 🧪 Example Usage

```bash
php artisan style:download --output=resources/template --mix --mix-output=resources/template
```

You will be prompted to input the template URL:

```
Url Template? https://example.com/template
```

---

### 📂 Output Structure

After downloading, your `resources` folder might look like:

```
resources/
└── template/
    ├── css/
    │   └── style.css
    ├── js/
    │   └── script.js
    └── webpack.mix.js  (if --mix is used)
```

---

### ⚙️ Laravel Mix File Example (generated)

```js
const mix = require('laravel-mix');

mix.styles([
    'resources/template/css/style.css'
], 'resources/template/css/main.css');

mix.combine([
    'resources/template/js/script.js'
], 'resources/template/js/main.js');
```

---

### 📌 Notes

* Only files with valid URLs will be downloaded.
* Files from excluded domains are skipped.
