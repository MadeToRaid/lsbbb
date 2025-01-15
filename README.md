<p align="center">
    <h1 align="center">lsbBB</h1>
</p>

<p align="center">
<a href="https://www.gnu.org/licenses/gpl-3.0"><img src="https://img.shields.io/badge/License-GPLv3-blue.svg"/></a>
<a href="https://github.com/MadeToRaid/lsbBB/pulls"><img src="https://img.shields.io/badge/contributions-welcome-brightgreen.svg?style=flat"/></a>
</p>

Welcome to lsbBB, an open source extension for phpBB that provides integrations with [LandSandBoat server](https://github.com/LandSandBoat/server/).

## Features

* Create accounts in the XI database when user's register through phpBB
* Activate accounts in the XI database after phpBB user validation is complete
* Change account passwords in the XI database when phpBB password is changed
* Migrate existing users in the XI database into phpBB
* Adds accounts logged into the XI server to the Who Is Online list
* Auction House index page showing the latest listings
* Browse the Auction House by category
* Vana'diel World Map with Conquest information
* Zone pages displaying NPCs, Mobs, and Fishing for each zone
* Character profiles
* Item pages for every item in the XI database showing recipes, drop information and fishing information
* Search for any item

## Getting Started
Install phpBB into a new database on the same database server as your XI database. Grant your phpBB forum user the necessary permissions to your XI database.

Copy the extension to the ext/madetoraid/lsbbb folder in your phpBB installation.

Go to "ACP" > "Customise" > "Extensions" and enable the "lsbBB" extension.

### Demo
You can see a working copy of lsbBB at the [Made to Raid Forums](https://forums.madetoraid.com).

## LICENSE

lsbBB is licensed under [GNU GPL v3](https://github.com/MadeToRaid/lsbBB/blob/base/LICENSE)

## Thanks

Thanks to the maintainers of [LandSandBoat](https://github.com/LandSandBoat/). This project does not exist without their hard work.
