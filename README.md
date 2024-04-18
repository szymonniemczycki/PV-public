# RCE importer

## Description

"RCE importer" is an application, which is able to download and save in database actual energy prices.<br />
Making more comfortable, "RCE importer" runs process for downloading automaticly forecast price of electricity for the current day.<br />
Except that, each user can download manually historical electricity prices (since 2018-01-01).<br />
Application is avaiable here: [RCE-imoporter](https://szymonniemczycki.pl/rce-importer/).<br />
Loging only for users created by Administrator.<br />
So, you can use belows credentionals:<br />
- with a User role:
  - login: repo-user
  - pass: repo-user
- with a Manager role:
  - login: repo-mode
  - pass: repo-mode
<br /><br />


## Technology

### Language
- PHP
  - MVC (as a software design pattern)
  - PDO (for connecting with DB)
- HTML & CSS (no frameworks)
- SQL (for requesting in DB)

### Database
- MySQL (database managment system)
<br />

## Features

### Download prices

Each day at 00:05 is running crone, which in automaticly mode download forecast price of electricity for the current day. <br />
Apart from that - each user can manually download prices - by "import prices" button.<br />
When necessary, application alows to overwriting downloaded before prices.



### Logs

Application logs in database each imports (and overwrites) prices, remembering the time and which user made the update.<br />
Also any internal issues and errors from exception, application is saving in log-file. 

### Roles

In application was implemented division in roles:<br />
1. User, with permissions:
- loggin
- show prices
- import prices
  
2. Moderator, with permissions:
- loggin
- show prices
- import prices
- overwrite prices (force downloaded)
- show in application logs
  
3. Administrator, with permissions:
- loggin
- show prices
- import prices
- overwrite prices (force downloaded)
- show in application logs
- list in errors with application (occured exceptions)
