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
| `--mix-output` |          | Directory where `webpack.mix.js` file will be saved | root (empty) |

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

Dưới đây là nội dung README bằng tiếng Anh dành cho class `DownloadTemplateCommand`, giải thích chức năng và cách sử dụng lệnh Artisan này một cách rõ ràng:

---

## Download Template

The `DownloadTemplateCommand` is a Laravel Artisan command that allows you to **download and convert a section of an HTML page** from a specified URL into a Blade template file. It is helpful when you're importing static HTML templates into a Laravel project.

---

### 🧾 Command Signature

```bash
php artisan html:download
```

---

### 🛠 Features

* Downloads HTML content from a provided URL.
* Extracts a specific container (`div`, `section`, etc.) using a CSS selector.
* Wraps the downloaded content inside a Blade template that extends a layout.
* Saves the file to a specified directory under `resources/views` or a custom path.

---

### 🔧 Options

| Option     | Shortcut | Description                    | Default                   |
| ---------- | -------- | ------------------------------ | ------------------------- |
| `--output` | `-o`     | Output path for the Blade file | `resources/views`         |
| `--layout` |          | Blade layout to extend         | `layouts.app` |

---

### 💬 Interactive Prompts

You will be prompted to input:

| Prompt          | Description                                       | Default            |
| --------------- | ------------------------------------------------- | ------------------ |
| `Url Template?` | The URL of the HTML page to download              | Last used value    |
| `Container?`    | The CSS selector of the container to extract      | `.container-fluid` |
| `File?`         | The filename to save the Blade file (e.g. `home`) | `index.blade.php`  |

If the file name does not end in `.php`, `.blade.php` will be appended automatically.

---

### 📂 Output Example

Assuming:

* URL: `https://example.com/home.html`
* Container: `.main-content`
* File: `home`

The result will be saved as:

```
resources/views/home.blade.php
```

With contents similar to:

```blade
@extends('layouts.app')

@section('content')
    <div class="main-content">
        <!-- Downloaded content here -->
    </div>
@endsection
```

---

### 🧪 Example Usage

```bash
php artisan html:download --output=resources/views/templates --layout=layouts.app
```

Then follow the prompts:

```
Url Template? https://example.com/index.html
Container? .content
File? homepage
```

This will generate:

```
resources/views/templates/homepage.blade.php
```

---

### 🧩 Extend & Customize

You can extend this command to:

* Automatically download and transform multiple pages.
* Extract and replace assets like images or CSS links.
* Apply Laravel components or sections dynamically to the template.

---

### 📌 Notes

* The HTML content is parsed using the `Juzaweb\HtmlDom\HtmlDom` package.
* The command does not validate if the container exists — if it doesn't, the generated Blade file may be empty or invalid.
* Make sure external assets are handled manually or by pairing with `style:download`.

# License

This package is open-sourced software licensed under the [MIT license](./LICENSE).
