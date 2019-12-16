# Setup

## Clone the repository
```$xslt
$ git clone https://github.com/MantasGra/AnimalAds.git
```
## Navigate to projects directory
```
$ cd AnimalAds
```
## Install all packages
```$xslt
$ composer install
```
## Install assets

You can either use yarn or npm

##### yarn:
```$xslt
$ yarn install
$ yarn build
```

##### npm:
```$xslt
$ npm install
$ npm run build
```

## Connect your database in .env.local
After configuring your connection run
```$xslt
$ composer reset-db
```
