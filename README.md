**THIS PLUGIN IS DEPRECATED.**

> This plugin is no longer maintained. You can find the new, maintained version for WinterCMS at this repository: https://github.com/nathanlesage/wn-patreonlist-plugin

# OctoberCMS PatreonList Plugin

> Automate managing your patron list on your website!

This plugin automates the process of keeping your patron list in sync with the patrons on your Patreon account. It allows you to simply upload a CSV file as Patreon allows you to download them from the relationship manager, and write the data into OctoberCMS's database. Then, you can customize how the patrons are displayed on your page.

## Install

Simply install the plugin from the OctoberCMS marketplace. The ID is `HendrikErz.PatreonList`. Alternatively, you can also download this repository and place it in your `/plugins`-directory. When you refresh the OctoberCMS backend, it should be installed automatically.

## Usage

The PatreonList-plugin ships with two components and tries to mimick the relationship manager in the backend. The components display either a simple list of all patrons, or displays a list of all tiers. In principle, you can access all information from both components, only the page variable will be different.

### Adding Patrons to the Website

The first step is to fill in the database with some patrons. To do so, go to your backend, click on the Patreon List button, and either click the blue Create-button in order to create one single patron, or click on "Import Patrons" to import a full CSV file.

The CSV file is one you can download from Patreon's relationship manager. You can either pre-filter the patrons who will be downloaded directly on Patreon, or you can simply export all and decide to perform additional sorting via the plugin. The latter is recommended (for more information, see below).

Drag a file into the respective field, or click the "Upload" button, and point October to your file. You can customize how the CSV file looks, if something seems odd, but the default file format has been adapted to the way Patreon's CSV files are formatted.

The database fields have been named so that the "Auto match columns" button should automatically match the fields in the CSV file to the fields in the database. **Attention**: The ID, Created On, Updated On, and Removed On-fields are internally used to manage the patron records. Likewise, some fields from the CSV file have not been mapped. This has two reasons: first, uploading a lot of personal data constitutes a potential risk, which is why only fields that make sense are imported. The second reason is that the plugin's purpose is solely to display a list of patrons, for which we only need the name and the respective tier.

After all fields are mapped onto the database fields, click Import to begin adding them to the database. **Attention**: The tier information will be used to automatically create all tiers for you during import. This means *you do not have to create the tiers up front*. The pledge amount of each tier will be inferred by looking at the "Current Pledge" field of the first patron that is on a tier not yet in the database, as this should in most cases be the correct amount of the tier.

After the upload is done, check the log for potential warnings. Click Ok to be redirected to the patron list, which should now be filled. You can sort, filter, and search the list in a similar fashion as you can do in the relationship manager on Patreon.

### Updating Patrons

Of course, the status of your patrons will change month by month. For your convenience, the importer will not create duplicates on subsequent imports, but try to match Patreon's user IDs to the users in the database. This way, changes of the same user across several months will be tracked, and updated names, emails, or the patron status will be applied to the users.

What will never be updated during import are the extra fields, such as the hidden-flag or the extra information. These are fields added to the plugin for your convenience. These have to be updated manually. One exception would be if you give your users, e.g., the option to have a link to their website displayed on the supporter list. In that case, you could, for instance, manually drag the "Extra (1)"-field onto the "Additional Info" field provided in the CSV-file.

### Extra Fields for Patrons

While it is common to simply include a rolling list of supporters at the end of videos supported by patrons, it might be interesting to do something more for the patrons on a website. For these cases, the plugin supports two extra fields for some information regarding your patrons. As the hidden-flag, this will not be updated during import (unless you mapped any CSV file field onto that database column, which you can do, if you like) and can be used to hold, e.g., links to images (avatars or logos), to websites (by the patron), or whatever you like.

The plugin contains two fields, one as a simple text input, and one textarea, which you can use freely. **Note**: Patreon supports "Additional Info" in your patron's data, so one idea would be to map the Extra 1-field onto "Additional Info" in the CSV-file. You can access them with `patron.extra1` and `patron.extra2` on your CMS pages.

### On Patrons' Anonymity

Some patrons want to support you, but express the wish not to be listed on your website. To accomodate for that, each patron within the plugin has a flag that can be set to hide them from all lists. If the flag is set, the respective patron will be hidden from each list and filtered out, so regardless of any option on the components, these patrons will not be displayed. This flag will not be updated during subsequent CSV imports.

### Definition of "Active Patrons"

Patrons can be in one of multiple different states, so this plugin has to make a concession when a patron counts as "active". Currently, based on what the CSCV-fields from Patreon look like, the following conditions must be met in order for this plugin to consider a patron "active", e.g. eligible to be displayed on the list of patrons:

1. The patron must be active, that is: In their entry the "Status" must be the string "Active patron" (which internally is translated into a boolean true/false dichotomy).
2. The patron must have a current pledge greater than zero dollars.
3. The patron's last charge status must not be null, that is: Patreon must have charged them at least once (albeit the plugin does _not_ differentiate between the different states such as Paid, Pending, Refused, Fraud, or Refund -- all of them satisfy this criterium).

If you check the "Only Active" option (default: checked) on one of the components, patrons not fulfilling these three criteria will be filtered out and not displayed. This is to ensure that new patrons will only be listed as soon as they were charged at least once.

### Managing Tiers

The tiers are a unique feature of Patreon and are central to this plugin as well. If you import a CSV file, the tiers will be created automatically. You can afterwards change their name or the pledge amount on the Tier page. This list will additionally show you how many patrons are on each tier. The description is initially empty, but can be populated by you for informative purposes. You can either copy the description from Patreon or create a new one.

Please note that changing the names of the tiers is not recommended, as the importer will search for tiers by comparing the tier name as given in the CSV file with the tiers in the database, case-sensitive. Therefore, on subsequent uploads it would create duplicates.

Reordering tiers will affect how they are displayed on the page. This is because neither name nor regular pledge amount can adequately describe the importance -- maybe the most important tier for you is not the one with the highest pledge amount.

### Displaying the Patron Lists via the Components

The plugin ships with two components that you can use to display patron lists. The first one lets you simply display a list of all your patrons. It provides you with options to sort them according to your likings. By default, the patron list will be sorted by current pledge descending, but you can change that to name, lifetime pledge, or simply the database ID. The default partial for this component renders a 2nd-order heading and a list of all your patrons with the tier, if applicable, in the format `Patron name (Tier name)`.

The second component renders the patrons as well, but this time based on their tier. It will therefore exclude all patrons that are not on any tier. By default, it will display all tiers, even empty ones, but provides you with an option to exclude them. The default partial for this component renders a 2nd-order heading and a list with all tiers, and nested lists containing all patrons. The format is as follows:

```
* Tier name
  The tier description, if provided
    * Patron
    * Patron
* A second tier without description
    * Patron
    * Patron
```

In case you want to have more control over how the patrons are displayed (for instance, render certain tiers with different markup), simply remove the partial component from the HTML markup (e.g. the `{% component 'patreonList' %}`), and use the data directly. The patron list component offers you the patrons in the variable `patreonList.patrons`, whereas the tier list offers you the tiers in the variable `tierList.tiers`. Remember to adapt the alias according to your settings. You can then access the tiers from the patrons and the patrons from the tiers like so:

```
patron.tier.name // Displays the patron's tier name
tier.patrons // Contains a list of all patrons on that tier
```

### Things to consider and Additional Info

A few notes on how the plugin will behave. It is made so that you can upload a new CSV list every month, which will update old patron data, and create new entries for all new patrons. However, it does not delete old ones. But by exporting all your patrons (including former ones) every time, former patrons will have their active patron-status revoked, so if you simply upload a full list, they won't display anymore. The system uses the Patreon ID to identify already existing patrons.

A last note on data privacy: This plugin handles a lot of personal data, which should never leave your page. While OctoberCMS itself is pretty secure, remember that when you render components, you potentially could display the patron's emails or their overall lifetime pledge amount by writing something like `{{ patron.email }}` in your CMS pages. **Don't do that, under no circumstances!** Your patrons won't be happy to see that (except, of course, they gave their consent) and you don't want to have any lawsuits. I know that most of you reading this will already know not to do that, but as we're on the internet, sometimes people forget things like that. Thank you for your attention.

### Reference: Field Names

For your convenience, here is a list of field names accessible on the patrons' and tiers' arrays. Please remember **to only use sensitive information to filter out certain patrons, but never actually display these**.

- Patrons
  - `id`
  - `patreon_id`
  - `created_at`
  - `updated_at`
  - `name`
  - `email`
  - `twitter`
  - `patron_status`
  - `follows_you`
  - `current_pledge`
  - `lifetime_pledge`
  - `tier` (refers to a `Tier`)
  - `patron_since`
  - `max_amount`
  - `last_charge`
  - `charge_status`
  - `extra1`
  - `extra2`
- Tiers
  - `created_at`
  - `updated_at`
  - `sort_order`
  - `name`
  - `description`
  - `pledge_amount`
  - `patrons` (refers to a list of `Patron`s)

## License

This plugin is licensed via the GNU GPL v3 license, which means both that this plugin comes without any warranty, but its source code is freely available on GitHub, and you can build upon it. However, any derivations need to be open sourced as well. For more, see the LICENSE file.
