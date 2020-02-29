# OctoberCMS PatreonList Plugin

> Automate managing your patron list on your website!

This plugin automates the process of keeping your patron list in sync with the patrons on your Patreon account. It allows you to simply upload a CSV file as Patreon allows you to download them from the relationship manager, and write the data into OctoberCMS's database. Then, you can customize how the patrons are displayed on your page.

## Install

Simply install the plugin from the OctoberCMS marketplace. The ID is `hendrikerz.patreonlist`. Alternatively, you can also download this repository and place it in your `/plugins`-directory. When you refresh the OctoberCMS backend, it should be installed automatically.

## Usage

The PatreonList-plugin ships with two components and tries to mimick the relationship manager in the backend. The components display either a simple list of all patrons, or displays a list of all tiers. In principle, you can access all information from both components, only the page variable will be different.

### Adding Patrons to the Website

The first step is to fill in the database with some patrons. To do so, go to your backend, click on the Patreon List button, and either click the blue Create-button in order to create one single patron, or click on "Import Patrons" to import a full CSV file.

The CSV file is one you can download from Patreon's relationship manager. You can either pre-filter the patrons who will be downloaded directly on Patreon, or you can simply export all and decide to perform additional sorting via the plugin.

Drag a file into the respective field, or click the "Upload" button, and point October to your file. You can customize how the CSV file looks, if something seems odd, but the default file format has been adapted to the way Patreon's CSV files are formatted.

The database fields have been named so that the "Auto match columns" button should automatically match the fields in the CSV file to the fields in the database. **Attention**: The ID, Created On, Updated On, and Removed On-fields are internally used to manage the patron records. Likewise, some fields from the CSV file have not been mapped. This has two reasons: first, uploading a lot of personal data constitutes a potential risk, which is why only fields that make sense are imported. The second reason is that the plugin's purpose is solely to display a list of patrons, for which we only need the name and the respective tier.

After all fields are mapped onto the database fields, click Import to begin adding them to the database. **Attention**: The tier information will be used to automatically create all tiers for you during import. This means *you do not have to create the tiers up front*. The pledge amount of each tier will be inferred by looking at the "Current Pledge" field of the first patron that is on a tier not yet in the database, as this should in most cases be the correct amount of the tier.

After the upload is done, check the log for potential warnings. For instance, an email is recommended, as during subsequent uploads the plugin will check if the email is already registered, and will then update the respective entry, not create a new one. This is for your convenience, because then you can simply upload a new CSV file each month without having to worry about creating duplicates. Click Ok to be redirected to the patron list, which should now be filled. You can sort, filter, and search the list in a similar fashion as you can do in the relationship manager on Patreon.

### Managing Tiers

The tiers are a unique feature of Patreon and are central to this plugin as well. If you import a CSV file, the tiers will be created automatically. You can afterwards change their name or the pledge amount on the Tier page. This list will additionally show you how many patrons are on each tier. The description is initially empty, but can be populated by you for informative purposes. You can either copy the description from Patreon or create a new one.

Please note that changing the names of the tiers is not recommended, as the importer will search for tiers by comparing the tier name as given in the CSV file with the tiers in the database, case-sensitive. Therefore, on subsequent uploads it would create duplicates.

Reordering tiers will affect how they are displayed on the page. This is because neither name nor regular pledge amount can adequately describe the importance -- maybe the most important tier for you is not the one with the highest pledge amount.

### Displaying the Patron Lists via the Components

The plugin ships with two components that you can use to display patron lists. The first one lets you simply display a list of all your patrons. It provides you with some options to sort them according to your likings. By default, the patron list will be sorted by current pledge descending, but you can change that to name, lifetime pledge, or simply the database ID. The default partial for this component renders a 2nd-order heading and a list of all your patrons with the tier, if applicable, in the format `Patron name (Tier name)`.

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

### Things to consider

A few notes on how the plugin will behave. It is made so that you can upload a new CSV list, which will update old patron data, and create new entries for all new patrons. However, it does not delete old ones. However, if you export all your patrons, former patrons will have their active patron-status revoked, so if you simply upload a full list, they won't display, if you turned on the respective settings.

The system uses the email as a unique identifier, both because Patreon does not allow multiple uses of the same email address, and because the email is the least likely to change. However, please keep in mind that patron's may decide to change their email, in which case you would have a duplicate. Make sure to check that!

A last note on data privacy: This plugin handles a lot of personal data, which should never leave your page. While OctoberCMS itself is pretty secure, remember that when you render components, you potentially could display the patron's emails or their overall lifetime pledge amount by writing something like `{{ patron.email }}` in your CMS pages. **Don't do that, under no circumstances!** Your patrons won't be happy to see that (except, of course, they gave their consent) and you don't want to have any lawsuits. I know that most of you reading this will already know not to do that, but as we're on the internet, sometimes people forget things like that. Thank you for your attention.

## License

This plugin is licensed via the GNU GPL v3 license, which means both that this plugin comes without any warranty, but its source code is freely available on GitHub, and you can build upon it. However, any derivations need to be open sourced as well. For more, see the LICENSE file.
