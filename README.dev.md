Steps for releasing:

1. Make a release branch
2. Review open issues and PRs to see if any can easily be fixed, closed, or
   merged.
3. Bump copyright year in `README.md`, if necessary.
4. Review `CHANGELOG.md` for completeness and correctness. Update its release
   date.
5. Install or update [gh](https://github.com/cli/cli) as it used by the
   release script.
6. Run `./dev-bin/release.sh`. This will build the phar, generate the docs,
   tag the release, push it to origin, and update the GH releases with the
   release notes and Phar.
7. Verify the release on [GitHub](https://github.com/maxmind/minfraud-api-php/releases)
   and [Packagist](https://packagist.org/packages/maxmind/minfraud).
