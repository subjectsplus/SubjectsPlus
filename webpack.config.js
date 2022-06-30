const Encore = require('@symfony/webpack-encore');
const hq = require('alias-hq');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */

    // Record-Search component
    .addEntry('record_search', './assets/react/components/record-search/RecordSearch.tsx')

    // React-Select component intializer
    .addEntry('react_select_init', './assets/react/entrypoints/select-field-type/initSelect.js')

    // Media-Browser component loader
    .addEntry('media_browser_loader', './assets/react/entrypoints/media_browser_loader.tsx')

    // Guide-Bulder component loader
    .addEntry('guide_builder_loader', './assets/react/entrypoints/guide_builder_loader.tsx')

    // Guide-Metadata component loader
    .addEntry('guide_metadata_loader', './assets/react/entrypoints/guide_metadata_loader.tsx')

    // Record Component
    .addEntry('record_index_container', './assets/react/components/record/RecordIndexContainer.js')
    .addEntry('alphabet_list', './assets/react/components/record/AlphabetList.js')
    .addEntry('record_results', './assets/react/components/record/RecordResults.js')

    // Backend CSS & JS
    .addEntry('backend_main', './assets/backend/BackendMain.js')
    .addEntry('mode_switcher', './assets/backend/javascript/ModeSwitcher.js')
    .addEntry('bs_tooltips', './assets/backend/javascript/BSTooltipPopoverInit.js')
    .addEntry('generate_shortform', './assets/backend/javascript/GenerateShortform.js')
    
    //Front-end Main CSS & JS
    .addEntry('frontend_main', './assets/frontend/FrontendMain.js')
    .addEntry('bs_tabs_anchor', './assets/frontend/javascript/BootstrapTabsAnchor.js')

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * Enable React
     */
    .enableReactPreset()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // enables Sass/SCSS support
    .enableSassLoader()

    // enables PostCSS, autoprefixing
    .enablePostCssLoader((options) => {
        options.postcssOptions = {
            config: './postcss.config.js',
        }
    })

    // copy images to reference in templates
    .copyFiles({
        from: './assets/backend/images',
        to: 'images/backend/[path][name].[ext]'
    })

    .copyFiles({
        from: './assets/frontend/images',
        to: 'images/frontend/[path][name].[ext]'
    })
    
    // CKEditor 4
    .copyFiles([
        {from: './node_modules/ckeditor4/', to: 'ckeditor/[path][name].[ext]', pattern: /\.(js|css)$/, includeSubdirectories: false},
        {from: './node_modules/ckeditor4/adapters', to: 'ckeditor/adapters/[path][name].[ext]'},
        {from: './node_modules/ckeditor4/lang', to: 'ckeditor/lang/[path][name].[ext]'},
        {from: './node_modules/ckeditor4/plugins', to: 'ckeditor/plugins/[path][name].[ext]'},
        {from: './node_modules/ckeditor4/skins', to: 'ckeditor/skins/[path][name].[ext]'},
        {from: './node_modules/ckeditor4/vendor', to: 'ckeditor/vendor/[path][name].[ext]'},
        
        // CKEditor Custom Config and Plugins
        {from: './src/CKEditorConfig/', to: 'ckeditor/[path][name].[ext]', pattern: /\.(js|css)$/, includeSubdirectories: false},
        {from: './src/CKEditorConfig/plugins', to: 'ckeditor/plugins/[path][name].[ext]'},
    ])

    // uncomment if you use TypeScript
    .enableTypeScriptLoader()

    // optionally enable forked type script for faster builds
    // https://www.npmjs.com/package/fork-ts-checker-webpack-plugin
    // requires that you have a tsconfig.json file that is setup correctly.
    .enableForkedTypeScriptTypesChecking()

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    //.enableIntegrityHashes(Encore.isProduction())

    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()
;

let config = Encore.getWebpackConfig();

config.resolve.alias = hq.get('webpack');

module.exports = config;