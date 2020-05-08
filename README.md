# koning_mailchimp_signup

This extension allows website visitors to sign up for a MailChimp list. It features:

- A Scheduler task to retrieve MailChimp lists
- Frontend plugin to show the form
- Validation: the form will check if the e-mail address already exists in the MailChimp list
- Easily override the template

# Setup

- Install the extension
- Install ``drewm/mailchimp-api`` using composer (``composer require drewm/mailchimp-api``) or include it manually
- Configure the extension in the Extension Manager
- Configure Scheduler tasks

**Extension Manager setup**

You will need an API key. Check [http://kb.mailchimp.com/accounts/management/about-api-keys](http://kb.mailchimp.com/accounts/management/about-api-keys) for more information.

**Scheduler tasks**

- Configure the Extbase CommandController Task ``mailchimp:import:audiences`` to synchronise audience lists from MailChimp to TYPO3 (recommended to run once a day).

# Frontend

When the MailChimp lists are synced to TYPO3 you can add the ``MailChimp sign up form`` to your page. Configure the list it should save subscribers to and you're done! The Scheduler task should now update your MailChimp list with new entries.

All data (lists and subscribers) is saved on the rootpage (page=0).

**Override the template**

You can override the template by using standard TypoScript:

    plugin.tx_koningmailchimpsignup_form {
        view {
            templateRootPaths {
                10 = EXT:tx_koningmailchimp_signup/Resources/Private/Templates
                15 = EXT:your_extension/Resources/Private/Templates
            }
            partialRootPaths {
                10 = EXT:tx_koningmailchimp_signup/Resources/Private/Partials
                15 = EXT:your_extension/Resources/Private/Partials
            }
            layoutRootPaths {
                10 = EXT:tx_koningmailchimp_signup/Resources/Private/Layouts
                15 = EXT:your_extension/Resources/Private/Layouts
            }
        }
    }
