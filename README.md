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

## Requirements
* You must already have a working install of [phpBB](https://github.com/phpbb/phpbb) version >=3.3
* You must have the [LandSandBoat](https://github.com/LandSandBoat/server) database installed on the same server as phpBB's database

## Installation
`git clone https://github.com/MadeToRaid/lsbbb.git phpbbroot/ext/madetoraid/lsbbb`

You will need a set of icons for all items in game. FFXIAH conveniently hosts them [here](https://www.ffxiah.com/dev/), or you can source your own.
`tar -xf icons.tgz -C phpbbroot/ext/madetoraid/lsbbb/styles/all/theme/images/icons/`

If you want maps to work, you will need to source the images. The [Remapster](https://github.com/AkadenTK/remapster_maps) project has created new maps for many areas. Download their "1024" maps and place them in:
`phpbbroot/ext/madetoraid/lsbbb/styles/all/theme/images/maps/1024/`

Go to your phpBB admin control panel's Customize tab and enabled the lsbBB extension

### Demo
You can see a working copy of lsbBB at the [Made to Raid Forums](https://forums.madetoraid.com).

## LICENSE

lsbBB is licensed under [GNU GPL v3](https://github.com/MadeToRaid/lsbBB/blob/base/LICENSE)

## Thanks

Thanks to the maintainers of [LandSandBoat](https://github.com/LandSandBoat/) and [phpBB](https://github.com/phpbb/phpbb). This project does not exist without their hard work.
