# University Library Management System

Simple Library Management System

## Description
A Library Management System which aids library to coordinate the borrowing and returning operations along with students. Just by registering, a book borrower is easily tracked with the books he/she borrowed. In addition, the borrower can easily browse books and filter the list based on his/her chosen criteria.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

What things you need to install the software and how to install them

```
XAMPP
MySql
Web Browser (for sure :D)
```

### Installing

A step by step series of examples that tell you how to get a development env running

1-Set database password to nothing and allow login without password
```
password=""
```
Or you can change database config in the code in the following files
```
inc/DB.php
inc/config.php
```
2-In order to test the code, you need to import the database LIBRARY or create a new database with the data in the schema below. Make sure the name of the database is “LIBRARY” and has no password 
![Screenshot_1](https://user-images.githubusercontent.com/51229687/85100673-02359980-b201-11ea-8173-c162dea1ff3b.png).
or you can just import it from the file here into you database.

3-Clone the whole project into htdoc in Xampp folder.

4-Run both Apache and MySql servers.

5-Finally, enter localhost through your browser
## Project Demo

You can check the demo video from [Here](https://youtu.be/oad4JhejyQc). I've tried many scenarios and you can see all the outputs



## Built With
* HTML,CSS and Bootstrap 3
* Javascript 
* AJAX
* PHP

## Developer Notes

This project took me 2 days to fully implement it, it was part of the college course assignment.
Hopefully you like it.
I didn't care that much about the ui design, I just made as simple as I could. The main focus was working with sessions and how to handle it.
Using AJAX was really helpful and has done great job in Books page, where you can see filter result immediately.
Although it's not my first web project, I found it really enjoyable. I may concern continue learning web developing.
I appreciate any comments you can give on this project.
Thanks
