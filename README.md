# drinks.com

A ficticious site for tracking user caffeine consumption.

This site was created to showcase a ReactJS app integrated with a Symfony 5 based Api.

# How To Install & Run
This repo comes with everything you need to run and install drinks.com locally within it own docker environment!

1. Clone down the repo
2. In the repo root folder, start the docker environment by running:

```docker-compose up -d```

3. Add the following entries to your local hosts file (/etc/hosts)
4. If this is the first time you are starting the environment, please wait ~1-2 minutes as the drinks-site container will need to perform an npm install.
```
127.0.0.1 api.drinks.com
127.0.0.1 www.drinks.com
127.0.0.1 drinks.com
```

### Caffeine Limit Configuration
You can adjust the maximum caffeine user limit by updating the "max_caffeine_mg" value in the drinks.configuration database table.

# Urls
# http://www.drinks.com
The drinks.com site.

![drinks_site](https://github.com/mkosolofski/drinks/blob/master/pics/site.png?raw=true)

# http://api.drinks.com:8080
Interactive Api Docs that you can use to directly request the API.

![drinks_site](https://github.com/mkosolofski/drinks/blob/master/pics/api.png?raw=true)
