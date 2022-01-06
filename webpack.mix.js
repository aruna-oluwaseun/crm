const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

/*
mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        //

    ]);*/

/* Admin stuff */
mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/checkout.js', 'public/assets/js')
    .js('resources/js/admin/apps/calendar.js', 'public/assets/js/admin/apps')
    .js('resources/js/admin/editors.js', 'public/assets/js/admin')
    .js('resources/js/admin/scripts.js', 'public/assets/js/admin')
    .js('resources/js/admin/sales-order.js', 'public/assets/js/admin')
    .js('resources/js/admin/sales-order-create.js', 'public/assets/js/admin')
    .js('resources/js/admin/attribute.js', 'public/assets/js/admin')
    .js('resources/js/admin/categories-list.js', 'public/assets/js/admin')
    .js('resources/js/admin/category.js', 'public/assets/js/admin')
    .js('resources/js/admin/customer.js', 'public/assets/js/admin')
    .js('resources/js/admin/customer-list.js', 'public/assets/js/admin')
    .js('resources/js/admin/production-order.js', 'public/assets/js/admin')
    .js('resources/js/admin/production-order-list.js', 'public/assets/js/admin')
    .js('resources/js/admin/product.js', 'public/assets/js/admin')
    .js('resources/js/admin/product-list.js', 'public/assets/js/admin')
    .js('resources/js/admin/product-create.js', 'public/assets/js/admin')
    .js('resources/js/admin/staff.js', 'public/assets/js/admin')
    .js('resources/js/admin/staff-list.js', 'public/assets/js/admin')
    .js('resources/js/admin/invoice-detail.js', 'public/assets/js/admin')
    .js('resources/js/admin/suppliers.js', 'public/assets/js/admin')
    .js('resources/js/admin/helpers.js', 'public/assets/js/admin')
    .js('resources/js/admin/purchase-order-create.js', 'public/assets/js/admin')
    .js('resources/js/admin/purchase-order.js', 'public/assets/js/admin')
    .js('resources/js/admin/vat.js', 'public/assets/js/admin');

mix.sass('resources/admin_sass/dashlite.scss', 'public/assets/css/admin/admin.css')
    .sass('resources/admin_sass/custom.scss', 'public/assets/css/admin')
    .sass('resources/admin_sass/skins/theme-orange.scss', 'public/assets/css/admin/skins')
    .sass('resources/admin_sass/invoice.scss', 'public/assets/css/admin/invoice.css')
    .sass('resources/admin_sass/editors/summernote.scss', 'public/assets/css/editors');
