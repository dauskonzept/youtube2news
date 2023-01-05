How to contribute
=================
Hey, great you want to contribute to ``youtube2news``.

Submitting feedback
===================
Please report feedback, bugs and feature requests on [GitHub](https://github.com/dauskonzept/youtube2news/issues)
Note, that the GitHub issue tracker is not a support forum.

I'm always willing to help user of ``youtube2news`` with potential problems, but please understand, that I will
not fix templates, code or misconfigured TYPO3 websites in commercial projects for free. If you need
commercial support, please contact me by email.

Submitting new features
=======================
Not every feature is relevant for the bulk of ``youtube2news`` users, so please discuss new features in the
issue tracker on [GitHub](https://github.com/dauskpnzept/youtube2news/issues) before starting to code.

Submitting changes
==================
* Create a fork of the ``youtube2news``  repository on GitHub
* Create a new branch from the current main branch
* Make your changes
* Make sure your code complies with the coding standard
* Make sure all unit- and functional tests are working (will also automatically be checked by GitHub Actions)
* Add new unit-, functional and/or acceptance tests for your new code
* Extend the existing documentation if required
* Commit your changes and make sure to add a proper commit message
    * Examples for a proper [commit message](https://docs.typo3.org/typo3cms/ContributionWorkflowGuide/Appendix/GeneralTopics/CommitMessage.html)
* Create a Pull Request on GitHub
    * Describe your changes. The better you describe your change and the reasons for it the more likely it is that it will be accepted.

Coding Standards
================
The ``youtube2news`` codebase follows [PSR-1](https://www.php-fig.org/psr/psr-1/),
[PSR-2](https://www.php-fig.org/psr/psr-2/) and [PSR-12](https://www.php-fig.org/psr/psr-12/) standards for code formatting.

Testing
=======
A wide range of the codebase of ``youtube2news`` is covered by unit- and functional tests. If you submit a pull
request without tests, this is ok, but please note, that it may take longer time to merge your pull requests in
this case, since I may have to create the tests for your code.
