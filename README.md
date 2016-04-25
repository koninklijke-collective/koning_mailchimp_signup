# koning_mailchimp_signup

This extension allows website visitors to sign up for a MailChimp list. It features:

- A Scheduler task to retrieve MailChimp lists
- Frontend plugin to show the form
- Validation: the form will check if the e-mail address already exists in the MailChimp list
- Subscribers are first saved into TYPO3: a Scheduler task will save them into to MailChimp database to optimise frontend performance
- Easily override the template

# Setup

- Install the extension
- Install `drewm/mailchimp-api`` using composer (``composer require drewm/mailchimp-api``) or include it manually
- Configure the extension in the Extension Manager
- Configure Scheduler tasks

**Extension Manager setup**

You will need an API key. Check [http://kb.mailchimp.com/accounts/management/about-api-keys](http://kb.mailchimp.com/accounts/management/about-api-keys) for more information.

Make sure composer can autoload. For example, place this code in ``typo3conf/AdditionalConfiguration.php``:

    // Load the autoload for composer
    if (file_exists(PATH_site . 'vendor/autoload.php')) {
       require_once(PATH_site . 'vendor/autoload.php');
    }

**Scheduler tasks**

- Configure the Extbase CommandController Task ``koning_mailchimp_signup:mailchimp:lists`` to synchronise subscriber lists from MailChimp to TYPO3 (recommende to run once a day).
- Configure the Extbase CommandController Task ``koning_mailchimp_signup:mailchimp:subscribers`` to synchronise subscribers from TYPO3 to MailChimp (depending on how busy your site is I would recommend to run this once every hour).

# Frontend

When the MailChimp lists are synced to TYPO3 you can add the ``MailChimp sign up form`` to your page. Configure the list it should save subscribers to and you're done! The Scheduler task should now update your MailChimp list with new entries.

All data (lists and subscribers) is saved on the rootpage (page=0). Subscribers are removed after they are saved in the MailChimp list

**Override the template**

You can override the template by using standard TypoScript:

    plugin.tx_koningmailchimp_signup {
        view {
            templateRootPaths {
                5 = EXT:your_extension/Resources/Private/Templates
                10 = EXT:tx_koningmailchimp_signup/Resources/Private/Templates
            }
            partialRootPaths {
                5 = EXT:your_extension/Resources/Private/Partials
                10 = EXT:tx_koningmailchimp_signup/Resources/Private/Partials
            }
            layoutRootPaths {
                5 = EXT:your_extension/Resources/Private/Layouts
                10 = EXT:tx_koningmailchimp_signup/Resources/Private/Layouts
            }
        }
    }

