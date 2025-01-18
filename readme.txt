=== PRG Bibliography Shortcode ===
Contributors: masuhara
Tags: bibtex, bibliography, citation
Requires at least: 5.2
Tested up to: 6.6.1
Stable tag: v0.1.13-alpha
Requires PHP: 7.2
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Shortcode to embed publication lists for PRG.

== Description ==
This plugin provides `prg-bib` shortcode that embeds a bibliography entry in a page.

It is customized for PRG\'s website where `bibtexbrowser.php` is installed under `/papers/`, the bibliography files like `prg-e.bib` are available under the same path.  

Usage
```
[prg-bib key='alice2099abc,bob2088def' more='false']
```

To show a list of the papers authored by Hidehiko:
```
[prg-bib author='Hidehiko Masuhara' more='false']
```

Parameters:
- key: specifies entries as a comma-separated list of bib keys.
- more: displays a "(more...)" link to the bibliography page instead of embedding the bibliography frame in a page, when the page is shown in a front page of a website.  This option is true by default, and turned off when specified a value other than 'true'.
- index: displays a list of paper titles before the embedded paper entries.  The list is always displayed; i.e., even when 'more' is set true.
  	 For example `[prg-bib key='alice2099abc,bob2088def' index='true']` shows the titles of the two papers first, followed by two frames each of which embeds the bibliography of each key.  In a front page of a website, it shows a list of titles followed by the "(more...)" link.

= How to update =
1. Decide the release name (e.g., v0.1.3-alpha)
2. Write the release name on Stable tag in readme.txt and prg-bib.php.
3. Add a Changelog section in readme.txt
4. Locally commit changes
5. Locally add a release tag (git tag v0.1.3-alpha)
6. Push the tag to github (git push --tags)
7. Make an archive by locally running rake
8. On https://github.com/prg-titech/bib-shortcode/tags, select the pushed tag
9. Create a release from a tag
10. Upload the archive (created at step 7) to the release

== Changelog ==

- v0.1.13-alpha
  NEW: index option 

- v0.1.12-alpha
  author list switches bib files based on the current blog language

- v0.1.11-alpha
  code refactoring

- v0.1.10-alpha
  FIX: include the missing .css file

- v0.1.9-alpha
  FIX: missing semicolon

- v0.1.8-alpha
  FIX: the "author" option now shows English papers only

- v0.1.7-alpha
  IMPROVEMENT: add the "author" option

- v0.1.6-alpha
  IMPROVEMENT: add the "more" option

- v0.1.5-alpha
  IMPROVEMENT: support multiple keys

- v0.1.4-alpha
  FIX: check release tag in .php

- v0.1.3-alpha
  FIX: .js and readme files are included.

- v0.1.2-alpha
  Another test release with a feature to build a release archive.

- v0.1.1-alpha
  A test release for checking how it would look like in the installation.

- v0.1-alpha
  The initial release.
