jQuery(document).ready(function($)
{
    let Cookies = require('js-cookie'),
    bodyTag = $('body'),
    currentSelection = (bodyTag.hasClass('theme-dark') ? 'dark' : (bodyTag.hasClass('theme-light') ? 'light' : ''))
;

    if (!currentSelection)
    {
        currentSelection = (window.matchMedia('(prefers-color-scheme: dark)').matches) ? 'dark' : 'light';
        updateTheme(currentSelection);
    }

    $('.toggle-dark-mode').on('click', function()
    {
        let opposite = currentSelection === 'dark' ? 'light' : 'dark';
        updateTheme(opposite);
    });

    function updateTheme(theme)
    {
        currentSelection = theme;

        Cookies.set('pegnet_theme', theme, { expires: 365 });

        bodyTag.removeClass('theme-light');
        bodyTag.removeClass('theme-dark');

        bodyTag.addClass('theme-' + theme);
    }
});
