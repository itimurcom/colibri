# Runtime compatibility notes

## M0 / P15 mail console and date compatibility bundle

- fixed dynamic property deprecation hot spots in `itModal`, `itButton`, `itForm2`
- fixed deprecated `strftime()` usage in shared date helpers and mail subject generation
- kept existing runtime model and did not remove legacy delivery code

