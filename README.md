# Bar Locator app

Bar Locator is example application that communicates using simple REST api.
Basically it is gateway to Google Places API. However it doesnt just proxy results but interprets them and returns formatted results.

Most emphasis was put on object/class structure and testing. That caused really small feature set of the REST API. There are just radar lookup and place detail actions. User has possibility to choose output format type by setting ?format query parameter . Currently 2 formatters are available: json and xml. "xml" is a bit quirky but works.

Everything runs on Silex [which became my bane :P]. At first Silex looked like a good choice - (hey! it's just simple REST API). but Symfony would be much better (much nicer annotations, swagger auto-documenting, ORM, yada-yada-yada. Unfortunatelly i was too deep into project (and too close to dead line) to perform full rewrite. 

Whole application has 100% code coverage in tests. Everything is written in Kahlan. I chose that framework because of its flexibility and format that closesly resembles Jasmine (which I like :).

To run tests launch
```
run-tests.sh in app directory
```
To run tests with code coverage:
```
run-tests.sh --coverage=4
```

In "app/gui" directory resides simple JS app that utilizes API. After launching docker it can be accessed via http://<docker host>/gui/index.html. Please note that Google Maps API key is required. It needs to be put in app.js:8 in requirejs !async dependency URL ...maps/api/js?key=<ENTER_MAPS_API_KEY_HERE>&sensor=false...   

In "docker" directory resides docker-compose.yml for launching application. By default it has mapped host port 30000 to nginx 80. Application *requires* valid Google Places API key to be put in docker-compose in "fpm" container configuration.   

In "docs" resides is Swagger yaml definition describing API.