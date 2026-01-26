# Tasmir Media Manager

A powerful, standalone, and customizable Media Manager for Laravel applications. This package provides a seamless way to manage uploads, browse media, and select files via a beautiful AJAX-powered interface.

## Features

- 🚀 **Asynchronous Uploads**: Drag-and-drop or select files for instant upload with progress bars.
- 🖼️ **Media Picker Component**: Easy-to-use Blade component for single or multiple file selection.
- 🎨 **Standalone CSS/JS**: Works even without Tailwind CSS; assets are minified and easy to publish.
- ⚙️ **Highly Configurable**: Customize upload paths, design options, and asset locations.
- 📝 **CKEditor Integration**: Built-in support for image uploads from rich text editors.
- 🗑️ **Soft Deletes**: Built-in trash management for media files.

---

## Installation

Then register the service provider in `bootstrap/providers.php`:

```php
return [
    App\Providers\AppServiceProvider::class,
    Tasmir\MediaManager\MediaManagerServiceProvider::class, // Add this
];
```

Run the migrations:
```bash
php artisan migrate
```

---

## Configuration

Publish the configuration file:
```bash
php artisan vendor:publish --tag=media-manager-config
```

The config file (`config/media-manager.php`) allows you to customize:
- `assets`: Paths to CSS/JS files.
- `upload_path`: Where files are stored (default: `media`).
- `active_tailwindcss`: Set to `false` to load the standalone CSS file.
- `button_class` & `button_text`: Default styling for the picker button.

---

## Assets Management

Publish the CSS and JS assets:
```bash
php artisan vendor:publish --tag=media-manager-assets
```

Include the scripts in your main layout (usually before `</body>`):
```html
@include('media-manager::partials.manager-scripts')
```

---

## Usage

### Blade Component
The easiest way to use the media manager is via the `<x-media-picker />` component.

**Single Selection (Returns ID):**
```html
<x-media-picker name="banner_id" label="Banner" :value="$hall->banner_id" />
```

**Multiple Selection (Returns JSON Array of IDs):**
```html
<x-media-picker name="gallery" label="Gallery" :value="$hall->gallery" type="multiple" returnType="array"/>
```

**Multiple Selection (Returns Comma-Separated String of IDs):**
```html
<x-media-picker name="gallery" label="Gallery" :value="$hall->gallery" type="multiple" returnType="string"/>
```

**Custom Design:**
```html
<x-media-picker 
    name="avatar" 
    button-text="Change Avatar" 
    button-class="btn btn-primary" 
/>
```

### Manual Trigger (JavaScript)
You can trigger the media manager manually from any element:

```javascript
MediaManager.open({
    type: 'single', // 'single' or 'multiple'
    targetInput: 'my-input-id',
    targetPreview: 'my-preview-container-id',
    onSelect: function(value, items) {
        console.log('Selected value:', value);
        console.log('Selected items:', items);
    }
});
```

---

## CKEditor Integration

The package includes a route for CKEditor image uploads. Set your CKEditor `uploadUrl` to:
`{{ route('ck.image.upload') }}`

---

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
