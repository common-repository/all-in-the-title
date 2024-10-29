# WordPress Coding standards

This repository uses version `2.0.0` of these standards. We are intentionally keeping this in version control so that
we may exercise control over when we upgrade; to avoid different engineers having different versions depending on when
they work on the project.

PHPCS version 3.x is required to use these standards; either run `composer global update` or manually re-install
depending on your set-up. It is also recommended that you `<PATH-TO-YOUR>/phpcs --config-delete installed_paths` to
prevent pointers to any locally hosted copies of WPCS from causing issues.

NOTE: Updating your global Composer dependencies has as strong change of breaking Valet; you will have to re-run Valet install to fix the issue. Also, you may receive an error along these lines `dyld: Library not loaded: /usr/local/opt/icu4c/lib/libicui18n.62.dylib` when PHPCS runs, this can be resolved by `composer global update` OR, `brew upgrade && brew cleanup` depending on your setup.

Native WPCS "whitelist" style comments such as `// WPCS: XSS OK.` have been deprecated in favour of native PHPCS
comments, for more information visit: https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/wiki/Whitelisting-code-which-flags-errors

Pre-commit checking takes place thanks to Husky, meaning that `phpcbf` automatically runs to deal with minor issues,
followed by `phpcs` that will check the code being committed against our ruleset. Errors/warnings will be written to
`./wpcs-errors.md` allowing you to address them before re-committing. The `--no-verify` flag is still available for
instances where standards just **have** to be bypassed; though this should be used sparingly.
