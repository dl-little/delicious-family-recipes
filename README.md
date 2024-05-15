## delicious-family-recipes

This is a plugin that uses the WordPress settings API to create a custom admin page under 'Appearance' in the dashboard. This plugin also creates a custom 'Category Query' block, and it provides several extensions of core blocks:
![Screenshot of the available custom blocks in the editor.](./images/recent-posts.png?raw=true "Delicious Family Recipes blocks")

![Screenshot of the available custom blocks in the editor.](./images/blocks.png?raw=true "Delicious Family Recipes blocks")

## Development

```sh
git clone https://github.com/dl-little/delicious-family-recipes.git
cd delicious-family-recipes/
npm install
npm start
```

npm start will call wp-scripts start, which will compile your code and then watch for changes to files in the entries defined in webpack.config.js. By default, wp scripts will scan src directory on the lookout for block.json files, but because this plugin adds extensions (that don't use block.json), I had to define a custom entrypoint in webpack.config. Compiled code is added to `build` directory, and the files there should be enqueued following standard wp conventions.

`npm run zip-for-prod` will produce a zipped copy of the plugin that is ready for installation on a WordPress site.

In development, to watch for changes to scss files, you can call `npm run watch:scss`. To build scss files, call `npm run build:scss`.

In the includes folder, any php file with the namespace `DFR` will be included in the composer autoloader.

## Category Settings

This plugin allows users to upload featured posts for categories. The attachment ID for the chosen image is saved as a piece of term_meta. To set a featured image for your categories, head to ‘Posts’ -> ‘Categories’. Select a category and scroll down to the ‘Category Image’ section:

![Screenshot of the custom settings for inserting a category image.](./images/category-image.png?raw=true "Delicious Family Recipes category image")

## Admin Settings

This plugin creates an admin page under the 'appearance' option in the WP admin dash:
![Screenshot of the admin settings.](./images/admin-settings.png?raw=true "Delicious Family Recipes admin settings")

This plugin is built for sites that are not using the Full Site Editor. It's meant to serve as a theme-agnostic way for publishers to add quick customizations to their site without having to configure themes. The settings make use of the WordPress Settings API. The setting page serves as a preview for how the changes will affect the front end of their site:

![Gif of the admin settings.](./images/settings-preview.gif?raw=true "Delicious Family Recipes admin settings preview")

### Use Display Settings
Toggling this setting will prevent the styles from loading on the front end of your site. This means the styles will fallback to whichever are provided by the theme.

### Body Font Family
This dropdown provides a set of Web Safe fonts to choose for your body font family. Unless otherwise set on specific blocks in the editor, this will set the font family for all non-heading text across the site.

### Header Font Family
This provides Web Safe fonts for use as the Headings across your site.

### Colors
The palette set here will determine how the custom blocks appear both in the editor and on the front end. The Primary color also sets the color for headings across the site. The secondary color determines the hover color for links in the custom blocks.

## Category Query Block
This custom block allows you to list categories and display a featured image for the category, as well as link to the category archive:
![Screenshot of the category block on front end.](./images/category-block.png?raw=true "Delicious Family Recipes category query block.")

Like all blocks, the settings in the sidebar are built using React. By default, the block lists the categories to which the most posts are attributed. If you’d like to hand select the categories, disable sort by count, then either type the categories into the text input, or use the dropdown to select the desired categories:
![Screenshot of the category block settings.](./images/category-block-settings.png?raw=true "Delicious Family Recipes category query block settings.")
