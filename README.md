# additional-contents
### Author: Prakai Nadee (https://github.com/prakai)

## Description
WonderCMS plugin for adding additional contents / new editable areas.

## Preview
![Plugin preview](/preview.jpg)

## How to use
1. Login to your WonderCMS website.
2. Click "Settings" and click "Plugins".
3. Find plugin in the list and click "install".
4. Plugin will be automatically activated.
5. You can now see an additional "+" anywhere under editable areas. This feature enables you to add new editable areas.
6. A new area is added at the top. To change the order, click "Move up" on an area: it swaps places with the area above it, with its content and its visible or hidden setting.

## Header area
Each page can also have an editable header (hero) area. Add this line to your theme's `theme.php` where the header should appear, for example above `<?= $Wcms->page('content') ?>`:

```php
<?= $Wcms->page('header') ?>
```

While logged in, click the area to edit it; it is saved per page. Visitors see nothing until you save something in it. A theme without the line is not changed.

