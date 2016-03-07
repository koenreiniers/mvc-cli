# MvcCli

Not yet available on packagist, so `git clone` and `composer install` to get it running.

## Examples

The example app can be found in the example directory.

You can run the example with `php example/run.php`

## TODO
- Rename tags to directives
- Decouple more from DOM
- Reimplement tags implemented with OldTagInterface with current TagInterface
- Figure out a better way to handle tag options/configuration
- Design better way to design tags
- Multiple tags per "tag"
- Better way to add new DomTwig filters
- DomTwig Filter priority
- LoopFilter parser cleanup
- Improve expression parser (or replace with existing library)
- Remove <command> from command description
- Html functions + filters (<v>function(foo)|filter</v>)
- Improve built in CLI
- Add more events