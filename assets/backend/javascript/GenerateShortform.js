import slugify from 'slugify';

document.addEventListener("DOMContentLoaded", () => {
    const guideTitleField = document.getElementById('guide_subject');
    const shortformField = document.getElementById('guide_shortform');
    const genShortformButton = document.getElementById('generate-shortform-btn');
    const bannedShortforms = ['api', 'control'];
    
    genShortformButton.addEventListener('click', () => {
        const guideTitleValue = guideTitleField.value;
        const isBannedShortform = bannedShortforms.includes(guideTitleValue);
        const formIsValid = guideTitleField.reportValidity();

        if (guideTitleValue && !isBannedShortform && formIsValid) {
            const slug = slugify(guideTitleValue, {
                replacement: '-',
                lower: true,
                strict: true,
                locale: APP_LOCALE
            });

            shortformField.value = slug;
        }
    })
});