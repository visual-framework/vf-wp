## Pantheon development

A Pantheon sandbox is being used for initial development and demos. Only the WordPress theme and plugins are committed to the GitLab repo. Gulp tasks are set up to watch and copy plugin and theme files.

* [Lando](https://docs.devwithlando.io/)
* [Docker](https://www.docker.com/) (can be installed by Lando)
* Access to **VF Theme prototype** on [Pantheon](https://pantheon.io/)

Enter a new empty directory outside of this one:

```bash
mkdir ../vf-wp-dev
cd ../vf-wp-dev
```

Make sure Docker is running. To get your Machine Token login to Pantheon and go to `My Dashboard > Account > Machine Tokens`. Setup containers with Lando:

```bash
# Enter your Machine Token when prompted
# Select the site "vf-theme-prototype"
lando init pantheon
```

Start the containers:

```bash
lando start
```

And pull in the WordPress install from Pantheon:

```bash
lando pull --code=dev --database=dev --files=dev
```

Local URL: [http://vfthemeprototype.lndo.site/](http://vfthemeprototype.lndo.site/)

Remote URL: [https://dev-vf-theme-prototype.pantheonsite.io/](https://dev-vf-theme-prototype.pantheonsite.io/)
