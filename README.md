# SilverWare Open Graph Module

Works in conjunction with [SilverWare][silverware] to add [Open Graph][opengraph] metadata to pages.

## Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Issues](#issues)
- [To-Do](#to-do)
- [Contribution](#contribution)
- [Maintainers](#maintainers)
- [License](#license)

## Requirements

- [SilverWare][silverware]

## Installation

Installation is via [Composer][composer]:

```
$ composer require silverware/open-graph
```

## Configuration

As with all SilverStripe modules, configuration is via YAML. Within the `config.yml` file you will
find a series of Open Graph types, namespaces, and metadata mappings. Everything should work out of
the box, however you can expand upon these items if you wish.

## Usage

Open Graph tags are automatically added to a page's metadata using the `PageExtension`. This module
works in conjuction with SilverWare's metadata and site configuration extensions to automatically
populate Open Graph tags for pages within your site tree.

## Issues

Please use the [GitHub issue tracker][issues] for bug reports and feature requests.

## To-Do

- Tests

## Contribution

Your contributions are gladly welcomed to help make this project better.
Please see [contributing](CONTRIBUTING.md) for more information.

## Maintainers

[![Colin Tucker](https://avatars3.githubusercontent.com/u/1853705?s=144)](https://github.com/colintucker) | [![Praxis Interactive](https://avatars2.githubusercontent.com/u/1782612?s=144)](http://www.praxis.net.au)
---|---
[Colin Tucker](https://github.com/colintucker) | [Praxis Interactive](http://www.praxis.net.au)

## License

[BSD-3-Clause](LICENSE.md) &copy; Praxis Interactive

[silverware]: https://github.com/praxisnetau/silverware
[composer]: https://getcomposer.org
[opengraph]: http://ogp.me
[issues]: https://github.com/praxisnetau/silverware-open-graph/issues
